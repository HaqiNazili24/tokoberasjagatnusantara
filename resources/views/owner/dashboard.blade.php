@extends('layouts.owner')
@section('page-title','Owner Dashboard')
@section('content')

{{-- Welcome --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 p-4 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #1E3932 0%, #006241 100%); color: white;">
            <div class="d-flex align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->full_name }}!</h3>
                    <p class="mb-0 opacity-75">Pantau kinerja penjualan, stok real-time, dan aktivitas operasional toko beras Anda di sini.</p>
                </div>
                <div class="ms-auto d-none d-md-block" style="font-size: 60px; color: #cba258;">
                    🌾
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Metrics Row --}}
<div class="row g-4 mb-4">
    {{-- Total Penjualan --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; background-color: white;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #d4e9e2; color: #006241;">
                        <i class="bi bi-currency-dollar fs-5"></i>
                    </div>
                    <span class="ms-auto fw-bold text-muted small">TOTAL REVENUE</span>
                </div>
                <h3 class="fw-bold mb-1" style="color: #1E3932;">Rp {{ number_format($totalRevenue,0,',','.') }}</h3>
                <small class="text-success"><i class="bi bi-arrow-up-right me-1"></i>Penjualan Selesai</small>
            </div>
        </div>
    </div>

    {{-- Jumlah Transaksi --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; background-color: white;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #faf6ee; color: #cba258;">
                        <i class="bi bi-receipt fs-5"></i>
                    </div>
                    <span class="ms-auto fw-bold text-muted small">TOTAL ORDER</span>
                </div>
                <h3 class="fw-bold mb-1" style="color: #1E3932;">{{ $totalOrders }}</h3>
                <small class="text-muted">Dari Seluruh Pengguna</small>
            </div>
        </div>
    </div>

    {{-- Margin Keuntungan Kasar (15%) --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; background-color: white;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #d4e9e2; color: #00754A;">
                        <i class="bi bi-graph-up-arrow fs-5"></i>
                    </div>
                    <span class="ms-auto fw-bold text-muted small">EST. KEUNTUNGAN</span>
                </div>
                <h3 class="fw-bold mb-1" style="color: #00754A;">Rp {{ number_format($estimatedProfit,0,',','.') }}</h3>
                <small class="text-muted">Estimasi Margin 15%</small>
            </div>
        </div>
    </div>

    {{-- Akun Karyawan & Kurir --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; background-color: white;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #edebe9; color: #1E3932;">
                        <i class="bi bi-people-fill fs-5"></i>
                    </div>
                    <span class="ms-auto fw-bold text-muted small">TIM OPERASIONAL</span>
                </div>
                <h4 class="fw-bold mb-1" style="color: #1E3932;">{{ $countKaryawan }} Karyawan / {{ $countKurir }} Kurir</h4>
                <small class="text-muted"><a href="{{ route('owner.users.index') }}" style="color: #00754A; font-weight: 600; text-decoration: none;">Kelola Akun &rarr;</a></small>
            </div>
        </div>
    </div>
</div>

{{-- Stock Alerts & Product Table --}}
<div class="row g-4 mb-4">
    {{-- Alerts Stok Kritis --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Stok Kritis (&lt; 10 kg)</h5>
                
                @forelse($lowStockProducts as $p)
                <div class="d-flex align-items-center mb-3 p-3 rounded-3" style="background-color: rgba(220,53,69,0.05); border: 1px solid rgba(220,53,69,0.1);">
                    <div>
                        <span class="fw-bold text-dark d-block">{{ $p->name }}</span>
                        <small class="text-muted">{{ $p->weight_label }} &middot; Rp {{ number_format($p->price,0,',','.') }}</small>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-danger rounded-pill px-3 py-2">Stok: {{ $p->stock }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-shield-check-fill fs-1 text-success d-block mb-2"></i>
                    Semua stok beras dalam kondisi aman.
                </div>
                @endforelse
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: #1E3932;"><i class="bi bi-pie-chart-fill me-2" style="color: #cba258;"></i>Statistik Akun</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Total Owner</span>
                        <span class="badge bg-dark rounded-pill">1</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Total Karyawan</span>
                        <span class="badge rounded-pill" style="background-color: #00754A;">{{ $countKaryawan }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Total Kurir</span>
                        <span class="badge rounded-pill" style="background-color: #cba258;">{{ $countKurir }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Total Customer Terdaftar</span>
                        <span class="badge bg-secondary rounded-pill">{{ $countCustomer }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4" style="color: #1E3932;"><i class="bi bi-clock-history me-2" style="color: #00754A;"></i>Aktivitas Transaksi Terbaru</h5>
                
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead style="background-color: #edebe9; color: #1E3932;">
                            <tr>
                                <th style="padding: 12px 16px;">No Pesanan</th>
                                <th style="padding: 12px 16px;">Customer</th>
                                <th style="padding: 12px 16px;">Total</th>
                                <th style="padding: 12px 16px;">Metode</th>
                                <th style="padding: 12px 16px;">Kurir</th>
                                <th style="padding: 12px 16px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr style="border-bottom: 1px solid #edebe9;">
                                <td style="padding: 12px 16px;">
                                    <span class="fw-bold text-dark d-block">#{{ $order->order_number }}</span>
                                    <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td style="padding: 12px 16px;">
                                    <span class="fw-semibold text-dark">{{ $order->shipping_recipient }}</span>
                                    <small class="text-muted d-block">{{ $order->shipping_phone }}</small>
                                </td>
                                <td class="fw-bold" style="color: #00754A; padding: 12px 16px;">
                                    Rp {{ number_format($order->total,0,',','.') }}
                                </td>
                                <td style="padding: 12px 16px;">
                                    <span class="badge {{ $order->payment_method === 'cod' ? 'bg-info' : 'bg-primary' }} text-uppercase">
                                        {{ $order->payment_method }}
                                    </span>
                                </td>
                                <td style="padding: 12px 16px;">
                                    @if($order->courier)
                                        <span class="badge text-dark" style="background-color: #faf6ee; border: 1px solid #dfc49d;">{{ $order->courier->full_name }}</span>
                                    @else
                                        <span class="text-muted small">Belum ditugaskan</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 16px;">
                                    <span class="badge bg-{{ $order->status_color }} rounded-pill px-3 py-2">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada aktivitas transaksi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
