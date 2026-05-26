@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">📋 Riwayat Transaksi Lunas</h4>
        <a href="{{ route('pos.index') }}" class="btn btn-warning">
            ← Kembali ke POS
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="alert alert-info">Belum ada transaksi lunas.</div>
    @else
        @foreach($orders as $order)
        <div class="card mb-3 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">{{ $order->order_code }}</span>
                <div>
                    @if($order->payment)
                    <span class="badge bg-info me-1">
                        {{ strtoupper(str_replace('_', ' ', $order->payment->payment_type ?? 'tunai')) }}
                    </span>
                    @endif
                    <span class="badge bg-success">LUNAS</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-1">👤 <strong>{{ $order->customer->name }}</strong></p>
                        <ul class="mb-0 small text-muted">
                            @foreach($order->items as $item)
                            <li>
                                {{ $item->barang->nama }}
                                × {{ $item->quantity }}
                                &mdash;
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- QR CODE --}}
                    <div class="col-md-3 text-center">
                        <img src="{{ route('qrcode.generate', $order->order_code) }}"
                             alt="QR {{ $order->order_code }}"
                             style="width: 100px; height: 100px;"
                             title="QR Code: {{ $order->order_code }}">
                        <br>
                        <small class="text-muted">Scan QR</small>
                    </div>

                    <div class="col-md-3 text-end">
                        <p class="fw-bold text-danger fs-5 mb-1">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </p>
                        <small class="text-muted">
                            {{ $order->updated_at->format('d/m/Y H:i') }}
                        </small>
                        @if($order->payment && $order->payment->paid_at)
                        <br>
                        <small class="text-success">
                            ✅ Dibayar: {{ $order->payment->paid_at->format('d/m/Y H:i') }}
                        </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection