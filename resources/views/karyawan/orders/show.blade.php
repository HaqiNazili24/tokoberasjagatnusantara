@extends('layouts.karyawan')
@section('page-title','Detail & Kelola Pesanan')
@section('content')

{{-- Header --}}
<div class="d-flex align-items-start justify-content-between mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('karyawan.dashboard') }}" class="btn btn-sm btn-outline-secondary py-1 px-2" style="border-radius: 50px;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <h4 class="mb-0 fw-bold" style="color: #1E3932;">Pesanan #{{ $order->order_number }}</h4>
        </div>
        <div class="d-flex align-items-center gap-2 ms-1">
            <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $order->created_at->format('d M Y, H:i') }}</small>
            <span class="badge bg-secondary text-uppercase">{{ $order->payment_method }}</span>
        </div>
    </div>
    <span class="badge bg-{{ $order->status_color }} fs-6 px-3 py-2 rounded-pill">
        {{ $order->status_label }}
    </span>
</div>

<div class="row g-4">
    {{-- LEFT COLUMN: Items and customer details --}}
    <div class="col-md-7">
        {{-- Items Card --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: #1E3932;"><i class="bi bi-bag-check me-2" style="color: #00754A;"></i>Item Beras yang Dipesan</h5>
                
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead style="background-color: #edebe9; color: #1E3932;">
                            <tr>
                                <th style="padding: 12px 16px;">Nama Beras</th>
                                <th style="padding: 12px 16px;" class="text-center">Qty</th>
                                <th style="padding: 12px 16px;" class="text-end">Harga</th>
                                <th style="padding: 12px 16px;" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr style="border-bottom: 1px solid #edebe9;">
                                <td style="padding: 12px 16px;" class="fw-bold text-dark">{{ $item->product_name_snapshot }}</td>
                                <td style="padding: 12px 16px;" class="text-center">{{ $item->quantity }}</td>
                                <td style="padding: 12px 16px;" class="text-end text-muted">Rp {{ number_format($item->price_snapshot,0,',','.') }}</td>
                                <td style="padding: 12px 16px;" class="text-end fw-bold" style="color: #00754A;">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 p-3 rounded" style="background-color: #f2f0eb; border: 1px solid #edebe9;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-semibold">Rp {{ number_format($order->subtotal,0,',','.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Ongkos Kirim</span>
                        <span class="fw-semibold">Rp {{ number_format($order->shipping_cost,0,',','.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between pt-2 border-top">
                        <span class="fw-bold">Total Pembayaran</span>
                        <span class="fw-bold text-success fs-5">Rp {{ number_format($order->total,0,',','.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Shipping Details Card --}}
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: #1E3932;"><i class="bi bi-truck me-2" style="color: #00754A;"></i>Tujuan Pengiriman</h5>
                
                <div class="row g-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block text-uppercase">Nama Penerima</small>
                        <span class="fw-bold text-dark">{{ $order->shipping_recipient }}</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block text-uppercase">Nomor Telepon</small>
                        <span class="fw-bold text-dark">{{ $order->shipping_phone }}</span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block text-uppercase">Alamat Lengkap</small>
                        <span class="text-dark">{{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</span>
                    </div>
                    @if($order->notes)
                    <div class="col-12">
                        <small class="text-muted d-block text-uppercase">Catatan Pelanggan</small>
                        <span class="text-warning fw-semibold">{{ $order->notes }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: Actions and Payment Proof --}}
    <div class="col-md-5">
        {{-- Payment Proof Verification --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: #1E3932;"><i class="bi bi-credit-card me-2" style="color: #00754A;"></i>Bukti Transfer</h5>
                
                @if($order->payment_method === 'cod')
                    <div class="alert alert-info py-3 mb-0" style="border-radius: 8px;">
                        <i class="bi bi-cash-coin fs-4 d-block mb-1"></i>
                        <strong>Metode Pembayaran COD (Bayar di Tempat)</strong>
                        <p class="mb-0 small mt-1">Pembayaran akan ditagih secara tunai oleh Kurir yang ditugaskan saat beras diantarkan.</p>
                    </div>
                @elseif($order->payment_proof_url)
                    <div class="mb-3 text-center">
                        <img src="{{ asset('storage/'.$order->payment_proof_url) }}"
                             class="img-fluid rounded border shadow-sm"
                             style="max-height: 280px; object-fit: contain;"
                             alt="Bukti Transfer">
                    </div>
                    
                    @if($order->status === 'pembayaran_dikirim')
                        <div class="d-grid gap-2 mt-3">
                            <form action="{{ route('karyawan.orders.confirm-payment', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn text-white w-100" style="background-color: #00754A; border-radius: 50px; font-weight: 600; padding: 10px;">
                                    <i class="bi bi-check-circle me-1"></i> Terima / Konfirmasi Pembayaran
                                </button>
                            </form>
                            
                            <hr class="my-2">
                            
                            <form action="{{ route('karyawan.orders.reject-payment', $order) }}" method="POST">
                                @csrf
                                <div class="mb-2">
                                    <label class="form-label text-muted small">Alasan Penolakan Bukti</label>
                                    <textarea name="rejection_reason" class="form-control" style="border-radius: 8px;" rows="2" placeholder="Sebutkan alasan penolakan..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-outline-danger w-100" style="border-radius: 50px; font-weight: 600; padding: 10px;">
                                    <i class="bi bi-x-circle me-1"></i> Tolak Pembayaran
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-success py-2 mb-0 small" style="border-radius: 8px;">
                            <i class="bi bi-check-circle-fill me-1"></i> Pembayaran transfer telah diverifikasi.
                        </div>
                    @endif
                @else
                    <div class="text-center py-4 text-muted border rounded" style="border-style: dashed !important; background-color: #faf6ee;">
                        <i class="bi bi-image fs-1 opacity-25 d-block mb-2"></i>
                        <small>Pelanggan belum mengunggah bukti transfer.</small>
                    </div>
                @endif
            </div>
        </div>

        {{-- Order Status Updates & Courier Assign --}}
        @if(in_array($order->status, ['pembayaran_dikonfirmasi', 'diproses', 'dikirim']))
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: #1E3932;"><i class="bi bi-pencil-square me-2" style="color: #00754A;"></i>Update Status & Kurir</h5>
                
                <form action="{{ route('karyawan.orders.update-status', $order) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Status Baru</label>
                        <select name="status" class="form-select" style="border-radius: 8px;">
                            <option value="diproses" @selected($order->status === 'pembayaran_dikonfirmasi' || $order->status === 'diproses')>Diproses (Dikemas)</option>
                            <option value="dikirim" @selected($order->status === 'dikirim')>Dikirim (Dalam Pengiriman)</option>
                            <option value="selesai" @selected($order->status === 'selesai')>Selesai</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Tugaskan Kurir Pengiriman</label>
                        <select name="courier_id" class="form-select" style="border-radius: 8px;">
                            <option value="">-- Pilih Kurir --</option>
                            @foreach($couriers as $courier)
                                <option value="{{ $courier->id }}" @selected($order->courier_id == $courier->id)>
                                    {{ $courier->full_name }} ({{ $courier->phone }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">Kurir yang dipilih akan menerima info tugas pengantaran ini di aplikasinya.</small>
                    </div>

                    <button type="submit" class="btn text-white w-100" style="background-color: #00754A; border-radius: 50px; font-weight: 600; padding: 12px; border: none;">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan Status
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
