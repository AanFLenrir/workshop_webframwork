<?php
namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customer.index', compact('customers'));
    }

    public function create1()
    {
        return view('customer.create1');
    }

    public function store1(Request $request)
    {
        // DEBUG — lihat semua data yang masuk
        \Log::info('store1 data:', [
            'name'       => $request->name,
            'phone'      => $request->phone,
            'photo_len'  => strlen($request->photo ?? ''),
        ]);

        $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|string',
        ]);

        Customer::create([
            'name'       => $request->name,
            'phone'      => $request->phone,
            'photo_blob' => $request->photo ?: null,
        ]);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil ditambahkan (blob)!');
    }

    public function create2()
    {
        return view('customer.create2');
    }

    public function store2(Request $request)
    {
        \Log::info('store2 data:', [
            'name'       => $request->name,
            'phone'      => $request->phone,
            'photo_len'  => strlen($request->photo ?? ''),
        ]);

        $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|string',
        ]);

        $photoPath = null;
        if ($request->photo) {
            $image    = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $request->photo));
            $filename = 'customer_' . time() . '.jpg';
            Storage::disk('public')->put('customers/' . $filename, $image);
            $photoPath = 'customers/' . $filename;
        }

        Customer::create([
            'name'       => $request->name,
            'phone'      => $request->phone,
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil ditambahkan (file)!');
    }

    public function webhook() {}

    public function riwayat() {}
}
