@extends('layouts.karyawan')
@section('page-title','Karyawan Dashboard & Stok')
@section('content')

<div class="row g-4 mb-4">
    {{-- LIST STOK PRODUK BERAS --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-1" style="color: #1E3932;"><i class="bi bi-box-seam me-2" style="color: #00754A;"></i>Update Stok Real-time</h5>
                <p class="text-muted small mb-4">Sebagai Karyawan, Anda hanya memiliki hak untuk mengubah level stok beras saat ini.</p>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead style="background-color: #edebe9; color: #1E3932;">
                            <tr>
                                <th style="padding: 12px 16px;">Nama Beras</th>
                                <th style="padding: 12px 16px;" class="text-center">Stok</th>
                                <th style="padding: 12px 16px; width: 40%;" class="pe-4">Ubah Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $p)
                            <tr style="border-bottom: 1px solid #edebe9;">
                                <td style="padding: 12px 16px;">
                                    <span class="fw-bold text-dark d-block">{{ $p->name }}</span>
                                    <span class="badge" style="background-color: #d4e9e2; color: #006241; font-size: 11px;">{{ $p->weight_label }}</span>
                                </td>
                                <td class="text-center" style="padding: 12px 16px;">
                                    @if($p->stock <= 5)
                                        <span class="badge bg-danger rounded-pill px-3 py-1">{{ $p->stock }}</span>
                                    @elseif($p->stock <= 20)
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1">{{ $p->stock }}</span>
                                    @else
                                        <span class="badge bg-success rounded-pill px-3 py-1">{{ $p->stock }}</span>
                                    @endif
                                </td>
                                <td class="pe-4" style="padding: 12px 16px;">
                                    <form action="{{ route('karyawan.products.stock', $p) }}" method="POST" class="d-flex gap-2">
                                        @csrf
                                        <input type="number" name="stock" class="form-control form-control-sm" style="border-radius: 6px; width: 80px;" value="{{ $p->stock }}" min="0" required>
                                        <button type="submit" class="btn btn-sm text-white" style="background-color: #00754A; border-radius: 6px; font-weight: 600; font-size: 12px; white-space: nowrap;">
                                            <i class="bi bi-save me-1"></i> Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($products->hasPages())
                <div class="mt-3">{{ $products->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    {{-- LIST TRANSAKSI / PESANAN CUSTOMER --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-1" style="color: #1E3932;"><i class="bi bi-receipt me-2" style="color: #00754A;"></i>Kelola Pesanan & Pembayaran</h5>
                <p class="text-muted small mb-4">Verifikasi pembayaran transfer, ubah status pesanan, dan tugaskan kurir pengiriman.</p>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead style="background-color: #edebe9; color: #1E3932;">
                            <tr>
                                <th style="padding: 12px 16px;">Pesanan</th>
                                <th style="padding: 12px 16px;">Penerima / Total</th>
                                <th style="padding: 12px 16px;">Status</th>
                                <th style="padding: 12px 16px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr style="border-bottom: 1px solid #edebe9;">
                                <td style="padding: 12px 16px;">
                                    <span class="fw-bold text-dark d-block">#{{ $order->order_number }}</span>
                                    <small class="text-muted">{{ $order->created_at->format('d/m H:i') }}</small>
                                    <span class="badge bg-secondary d-block mt-1 text-uppercase" style="font-size: 9px; width: fit-content;">{{ $order->payment_method }}</span>
                                </td>
                                <td style="padding: 12px 16px;">
                                    <span class="fw-semibold d-block text-dark small">{{ $order->shipping_recipient }}</span>
                                    <span class="fw-bold text-success small">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                </td>
                                <td style="padding: 12px 16px;">
                                    <span class="badge bg-{{ $order->status_color }} rounded-pill" style="font-size: 11px;">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td style="padding: 12px 16px;">
                                    <a href="{{ route('karyawan.orders.show', $order) }}" class="btn btn-sm btn-outline-success" style="border-radius: 50px; font-weight: 600; font-size: 11px; padding: 4px 12px;">
                                        Kelola
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada pesanan masuk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                <div class="mt-3">{{ $orders->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
