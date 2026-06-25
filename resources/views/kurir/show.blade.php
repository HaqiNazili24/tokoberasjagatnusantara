@extends('layouts.kurir')
@section('title', 'Detail Pengiriman #' . $order->order_number)
@section('content')

<div class="mb-4">
    <a href="{{ route('kurir.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-3" style="font-size: 13px; font-weight: 600;">
        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
    </a>
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h3 class="fw-bold mb-1" style="color: #1E3932;">Tugas Pengantaran #{{ $order->order_number }}</h3>
            <p class="text-muted mb-0">Daftar item beras, alamat pengantaran, dan upload bukti.</p>
        </div>
        <span class="badge bg-{{ $order->status_color }} fs-6 px-3 py-2 rounded-pill">
            {{ $order->status_label }}
        </span>
    </div>
</div>

<div class="row g-4">
    {{-- LEFT: Detail Pelanggan & Alamat --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: white;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: #1E3932;"><i class="bi bi-person-circle me-2" style="color: #00754A;"></i>Informasi Penerima</h5>
                
                <div class="mb-3">
                    <small class="text-muted d-block small">Nama Penerima</small>
                    <span class="fw-bold text-dark fs-5">{{ $order->shipping_recipient }}</span>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block small">Nomor Telepon</small>
                    <a href="tel:{{ $order->shipping_phone }}" class="btn btn-outline-success btn-sm rounded-pill mt-1" style="font-weight: 600;">
                        <i class="bi bi-telephone-fill me-1"></i> Hubungi: {{ $order->shipping_phone }}
                    </a>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block small">Alamat Lengkap Pengiriman</small>
                    <span class="fw-semibold text-dark">{{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</span>
                    <div class="mt-2">
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($order->shipping_address . ' ' . $order->shipping_city) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill" style="font-size: 12px; font-weight: 600;">
                            <i class="bi bi-map-fill me-1"></i> Buka Google Maps
                        </a>
                    </div>
                </div>

                @if($order->notes)
                <div class="p-3 bg-light rounded" style="border-left: 4px solid #fbc02d;">
                    <small class="text-muted d-block font-weight-bold">Catatan Pengantaran:</small>
                    <span class="text-dark">{{ $order->notes }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Detail Item Beras --}}
        <div class="card border-0 shadow-sm" style="border-radius: 12px; background-color: white;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: #1E3932;"><i class="bi bi-box2-fill me-2" style="color: #00754A;"></i>Beras Yang Dibawa</h5>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead style="background-color: #edebe9; color: #1E3932;">
                            <tr>
                                <th style="padding: 10px;">Nama Beras</th>
                                <th class="text-center" style="padding: 10px;">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td style="padding: 10px;" class="fw-bold text-dark">{{ $item->product_name_snapshot }}</td>
                                <td style="padding: 10px;" class="text-center fw-bold">{{ $item->quantity }} karung/kemasan</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Status Updates, COD, and Bukti Foto --}}
    <div class="col-md-5">
        {{-- COD Panel --}}
        @if($order->payment_method === 'cod')
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: #faf6ee; border: 1px solid #dfc49d;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-2" style="color: #1E3932;"><i class="bi bi-cash-coin me-2" style="color: #cba258;"></i>Pembayaran Tunai (COD)</h5>
                <p class="text-muted small">Pesanan ini menggunakan metode COD. Anda wajib menagih total pembayaran ke pelanggan.</p>
                <div class="p-3 bg-white rounded border text-center mb-3">
                    <span class="text-muted d-block small">Tagihan Tunai</span>
                    <span class="fs-3 fw-bold text-success">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>

                @if($order->status === 'dikirim')
                @if(!$order->delivery_proof_url)
                    <div class="alert alert-warning py-2 mb-2 small" style="border-radius: 8px;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Harap upload foto bukti pengiriman di bawah terlebih dahulu.
                    </div>
                @endif
                <form action="{{ route('kurir.orders.confirm-cod', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda sudah menerima uang pembayaran tunai senilai Rp {{ number_format($order->total, 0, ',', '.') }}?')">
                    @csrf
                    <button type="submit" class="btn text-white w-100" style="background-color: #00754A; border-radius: 50px; padding: 12px; font-weight: 600; border: none;" @disabled(!$order->delivery_proof_url)>
                        <i class="bi bi-check-circle-fill me-1"></i> Konfirmasi Terima Uang COD
                    </button>
                </form>
                @elseif($order->status === 'selesai')
                <div class="alert alert-success py-2 text-center mb-0 small" style="border-radius: 8px;">
                    <i class="bi bi-check-circle-fill me-1"></i> Pembayaran COD selesai dikonfirmasi.
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Bukti Foto Pengiriman --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: white;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: #1E3932;"><i class="bi bi-camera-fill me-2" style="color: #00754A;"></i>Foto Bukti Pengiriman</h5>
                
                @if($order->delivery_proof_url)
                <div class="mb-3 text-center">
                    <img src="{{ asset('storage/'.$order->delivery_proof_url) }}"
                         class="img-fluid rounded border shadow-sm"
                         style="max-height: 250px; object-fit: contain;"
                         alt="Bukti Pengiriman">
                </div>
                @endif

                @if($order->status === 'dikirim')
                <form action="{{ route('kurir.orders.proof', $order) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small">Pilih atau Ambil Foto Bukti</label>
                        <input type="file" name="delivery_proof" class="form-control" accept="image/*" capture="environment" style="border-radius: 8px;" required>
                    </div>
                    <button type="submit" class="btn btn-outline-success w-100" style="border-radius: 50px; font-weight: 600; padding: 10px;">
                        <i class="bi bi-upload me-1"></i> {{ $order->delivery_proof_url ? 'Ganti Foto Bukti' : 'Unggah Foto Bukti' }}
                    </button>
                </form>
                @elseif(!$order->delivery_proof_url)
                <div class="text-center py-3 text-muted">
                    <small>Belum ada foto bukti pengiriman diunggah.</small>
                </div>
                @endif
            </div>
        </div>

        {{-- Update Status Pengiriman --}}
        @if($order->status === 'diproses' || ($order->status === 'dikirim' && $order->payment_method !== 'cod'))
        <div class="card border-0 shadow-sm" style="border-radius: 12px; background-color: white;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: #1E3932;"><i class="bi bi-arrow-repeat me-2" style="color: #00754A;"></i>Update Status Pengantaran</h5>
                
                <form action="{{ route('kurir.orders.status', $order) }}" method="POST">
                    @csrf
                    
                    @if($order->status === 'diproses')
                    <input type="hidden" name="status" value="dikirim">
                    <button type="submit" class="btn text-white w-100" style="background-color: #0288d1; border-radius: 50px; padding: 12px; font-weight: 600; border: none;">
                        <i class="bi bi-bicycle me-1"></i> Mulai Kirim Beras
                    </button>
                    @elseif($order->status === 'dikirim' && $order->payment_method === 'transfer')
                    <input type="hidden" name="status" value="selesai">
                    <p class="text-muted small">Untuk pesanan Transfer, pastikan Anda sudah mengunggah foto bukti pengiriman di atas sebelum menandai pesanan selesai.</p>
                    <button type="submit" class="btn text-white w-100" style="background-color: #2e7d32; border-radius: 50px; padding: 12px; font-weight: 600; border: none;" @disabled(!$order->delivery_proof_url)>
                        <i class="bi bi-check-circle-fill me-1"></i> Tandai Pengantaran Selesai
                    </button>
                    @endif
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
