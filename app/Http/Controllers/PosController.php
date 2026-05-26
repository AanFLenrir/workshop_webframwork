<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class PosController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    public function index()
    {
        $barangs = Barang::all();
        return view('js.pos', compact('barangs'));  // ← sesuai folder js
    }

    public function cariBarang(Request $request)
    {
        $barang = Barang::where('id_barang', $request->kode)
            ->orWhere('nama', 'like', '%' . $request->q . '%')
            ->first();

        if (!$barang) {
            return response()->json(['error' => 'Barang tidak ditemukan'], 404);
        }

        return response()->json(['data' => $barang]);
    }

    public function bayar(Request $request)
    {
        $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:barang,id_barang',
            'items.*.qty'    => 'required|integer|min:1',
            'payment_method' => 'required|in:virtual_account,qris,tunai',
        ]);

        if ($request->payment_method === 'tunai') {
            return $this->bayarTunai($request);
        }

        $customer = Customer::create([
            'name' => Customer::generateGuestName(),
        ]);

        $total      = 0;
        $orderItems = [];
        $snapItems  = [];

        foreach ($request->items as $item) {
            $barang   = Barang::where('id_barang', $item['id'])->firstOrFail();
            $subtotal = $barang->harga * $item['qty'];
            $total   += $subtotal;

            $orderItems[] = [
                'barang_id' => $barang->id_barang,
                'quantity'  => $item['qty'],
                'price'     => $barang->harga,
                'subtotal'  => $subtotal,
            ];

            $snapItems[] = [
                'id'       => (string) $barang->id_barang,
                'price'    => (int) $barang->harga,
                'quantity' => (int) $item['qty'],
                'name'     => substr($barang->nama, 0, 50),
            ];
        }

        $order = Order::create([
            'order_code'   => 'ORD-' . strtoupper(Str::random(8)),
            'customer_id'  => $customer->id,
            'total_amount' => $total,
        ]);

        $order->items()->createMany($orderItems);

        $midtransOrderId = 'POS-' . $order->order_code . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $midtransOrderId,
                'gross_amount' => (int) $total,
            ],
            'customer_details' => [
                'first_name' => $customer->name,
            ],
            'item_details'     => $snapItems,
            'enabled_payments' => $request->payment_method === 'virtual_account'
                ? ['bca_va', 'bni_va', 'bri_va', 'mandiri_bill']
                : ['gopay', 'qris'],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Midtrans error',
                'message' => $e->getMessage(),
            ], 500);
        }

        Payment::create([
            'order_id'          => $order->id,
            'midtrans_order_id' => $midtransOrderId,
            'amount'            => $total,
            'status'            => 'pending',
        ]);

        return response()->json([
            'snap_token' => $snapToken,
            'order_id'   => $order->id,
            'order_code' => $order->order_code,
            'total'      => $total,
            'customer'   => $customer->name,
        ]);
    }

    private function bayarTunai(Request $request)
    {
        $customer = Customer::create([
            'name' => Customer::generateGuestName(),
        ]);

        $total      = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $barang   = Barang::where('id_barang', $item['id'])->firstOrFail();
            $subtotal = $barang->harga * $item['qty'];
            $total   += $subtotal;

            $orderItems[] = [
                'barang_id' => $barang->id_barang,
                'quantity'  => $item['qty'],
                'price'     => $barang->harga,
                'subtotal'  => $subtotal,
            ];
        }

        $order = Order::create([
            'order_code'     => 'ORD-' . strtoupper(Str::random(8)),
            'customer_id'    => $customer->id,
            'total_amount'   => $total,
            'payment_status' => 'lunas',
        ]);

        $order->items()->createMany($orderItems);

        return response()->json([
            'success'    => true,
            'order_code' => $order->order_code,
            'total'      => $total,
            'customer'   => $customer->name,
            'message'    => 'Pembayaran tunai berhasil!',
        ]);
    }

    public function webhook(Request $request)
    {
        $notif             = new \Midtrans\Notification();
        $transactionStatus = $notif->transaction_status;
        $orderId           = $notif->order_id;
        $fraudStatus       = $notif->fraud_status ?? null;

        $payment = Payment::where('midtrans_order_id', $orderId)->firstOrFail();

        if ($transactionStatus === 'capture') {
            $status = ($fraudStatus === 'accept') ? 'settlement' : 'pending';
        } elseif ($transactionStatus === 'settlement') {
            $status = 'settlement';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $status = $transactionStatus;
        } else {
            $status = 'pending';
        }

        $payment->update([
            'status'         => $status,
            'transaction_id' => $notif->transaction_id ?? null,
            'payment_type'   => $notif->payment_type ?? null,
            'paid_at'        => $status === 'settlement' ? now() : null,
        ]);

        if ($status === 'settlement') {
            $payment->order->update(['payment_status' => 'lunas']);
        }

        return response()->json(['status' => 'ok']);
    }

    public function riwayat()
    {
        $orders = Order::where('payment_status', 'lunas')
            ->with(['customer', 'items.barang', 'payment'])
            ->latest()
            ->paginate(15);

        return view('js.riwayat', compact('orders')); // ← fix dari pos.riwayat
    }
}