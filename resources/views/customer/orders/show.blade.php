@extends('layouts.customer')
@section('title','Detail Pesanan '.$order->order_number)
@section('content')
@php
    if ($order->payment_method === 'cod') {
        $steps = ['diproses','dikirim','selesai'];
    } else {
        $steps = ['menunggu_pembayaran','pembayaran_dikirim','pembayaran_dikonfirmasi','diproses','dikirim','selesai'];
    }
    $currentIdx = array_search($order->status, $steps);
    if ($currentIdx === false) $currentIdx = -1;
@endphp

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <nav class="mb-2 small"><a href="{{ route('orders.index') }}" class="text-success text-decoration-none fw-semibold">Pesanan Saya</a> <span class="text-muted mx-1">/</span> <span class="text-muted">{{ $order->order_number }}</span></nav>
        <h4 class="fw-bold mb-1 text-dark">{{ $order->order_number }}</h4>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted small">{{ $order->created_at->format('d M Y H:i') }}</span>
            <span class="badge bg-light text-dark border">{{ $order->payment_method === 'cod' ? 'COD (Bayar di Tempat)' : 'Transfer Bank' }}</span>
        </div>
    </div>
    <span class="badge bg-{{ $order->status_color }} status-badge fs-6 py-2 px-3">{{ $order->status_label }}</span>
</div>

@if($order->status !== 'dibatalkan')
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
    <div class="d-flex justify-content-between flex-wrap align-items-center position-relative">
        @foreach($steps as $i=>$s)
            <div class="text-center flex-grow-1 position-relative px-2 z-1">
                <div class="rounded-circle d-inline-flex justify-content-center align-items-center mb-2 shadow-sm"
                    style="width:32px; height:32px; background:{{ $i<=$currentIdx?'var(--color-primary)':'#E2E3E5' }}; color:#fff; font-weight:600; font-size:14px;">
                    @if($i<$currentIdx)<i class="bi bi-check-lg"></i>@else{{ $i+1 }}@endif
                </div>
                <small class="d-block fw-semibold text-dark" style="font-size: 0.75rem;">{{ \App\Models\Order::STATUSES[$s] }}</small>
            </div>
            @if(!$loop->last)
                <div class="flex-grow-1 align-self-center d-none d-md-block" style="height:3px; background:{{ $i<$currentIdx?'var(--color-primary)':'#E2E3E5' }}; margin: 0 -15px 18px; position:relative; z-index:0;"></div>
            @endif
        @endforeach
    </div>
</div>
@endif

<div class="row g-4">
<div class="col-md-7">
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
        <h5 class="fw-bold text-dark mb-4">Item Pesanan</h5>
        @foreach($order->items as $it)
            <div class="d-flex justify-content-between border-bottom py-3 align-items-center" style="border-color: #f1eeeb !important;">
                <div>
                    <strong class="text-dark d-block" style="font-size: 0.95rem;">{{ $it->product_name_snapshot }}</strong>
                    <small class="text-muted">{{ $it->quantity }} x Rp {{ number_format($it->price_snapshot,0,',','.') }}</small>
                </div>
                <strong class="text-dark">Rp {{ number_format($it->subtotal,0,',','.') }}</strong>
            </div>
        @endforeach
        
        <div class="d-flex justify-content-between mt-4 mb-2 small text-muted">
            <span>Subtotal</span>
            <span class="text-dark fw-semibold">Rp {{ number_format($order->subtotal,0,',','.') }}</span>
        </div>
        <div class="d-flex justify-content-between mb-3 small text-muted">
            <span>Ongkos Kirim</span>
            <span class="text-dark fw-semibold">Rp {{ number_format($order->shipping_cost,0,',','.') }}</span>
        </div>
        <hr class="my-3" style="border-color: #f1eeeb;">
        <div class="d-flex justify-content-between align-items-center">
            <strong class="text-dark fs-5">Total</strong>
            <strong class="text-success fs-4">Rp {{ number_format($order->total,0,',','.') }}</strong>
        </div>
    </div>

    @if($order->status === 'selesai')
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-star-fill text-warning me-2"></i>Ulasan & Rating Produk</h5>
        <p class="text-muted small">Berikan ulasan dan rating Anda untuk beras yang Anda beli agar dapat membantu kami meningkatkan pelayanan.</p>
        
        <form action="{{ route('orders.review', $order) }}" method="POST">
            @csrf
            @foreach($order->items as $idx => $it)
                @php
                    $existingReview = \App\Models\Review::where('order_id', $order->id)->where('product_id', $it->product_id)->first();
                @endphp
                <div class="mb-3 p-3 rounded" style="background-color: #f2f0eb;">
                    <strong class="text-dark d-block mb-2">{{ $it->product_name_snapshot }}</strong>
                    <input type="hidden" name="reviews[{{ $idx }}][product_id]" value="{{ $it->product_id }}">
                    
                    <div class="mb-2">
                        <label class="form-label text-muted small fw-bold">Rating:</label>
                        <select name="reviews[{{ $idx }}][rating]" class="form-select form-select-sm" style="width: 180px; border-radius: 8px;" required>
                            <option value="5" @selected($existingReview?->rating == 5)>⭐⭐⭐⭐⭐ (5 - Sangat Puas)</option>
                            <option value="4" @selected($existingReview?->rating == 4)>⭐⭐⭐⭐ (4 - Puas)</option>
                            <option value="3" @selected($existingReview?->rating == 3)>⭐⭐⭐ (3 - Biasa Saja)</option>
                            <option value="2" @selected($existingReview?->rating == 2)>⭐⭐ (2 - Kurang Puas)</option>
                            <option value="1" @selected($existingReview?->rating == 1)>⭐ (1 - Buruk)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label text-muted small fw-bold">Ulasan:</label>
                        <textarea name="reviews[{{ $idx }}][comment]" class="form-control form-control-sm" rows="2" style="border-radius: 8px;" placeholder="Tulis ulasan Anda tentang kualitas beras ini...">{{ $existingReview?->comment ?? '' }}</textarea>
                    </div>
                </div>
            @endforeach
            <button type="submit" class="btn text-white w-100" style="background-color: #00754A; border-radius: 50px; padding: 10px; font-weight: 600; border: none; transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
                Kirim Ulasan & Rating
            </button>
        </form>
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
        <h5 class="fw-bold text-dark mb-3">Alamat Pengiriman</h5>
        <div class="p-3 bg-light rounded-3">
            <strong class="text-dark d-block mb-1">{{ $order->shipping_recipient }} ({{ $order->shipping_phone }})</strong>
            <span class="text-muted small d-block mb-0">{{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</span>
        </div>
        @if($order->tracking_number)
            <div class="mt-3 p-3 border rounded-3 bg-white d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted d-block">Nomor Resi Pengiriman</small>
                    <strong class="text-dark">{{ $order->tracking_number }}</strong>
                </div>
                <span class="badge bg-success-subtle text-success py-1 px-3 rounded-pill"><i class="bi bi-truck me-1"></i> Dalam Pengiriman</span>
            </div>
        @endif
    </div>
</div>

<div class="col-md-5">
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
        <h5 class="fw-bold text-dark mb-4">Informasi Pembayaran</h5>

        @if($order->payment_method === 'cod')
            <div class="card border-0 bg-light-subtle border-start border-3 border-info p-3 mb-0">
                <div class="d-flex gap-2">
                    <i class="bi bi-cash-coin text-info fs-5"></i>
                    <div>
                        <strong class="text-dark d-block small">Cash On Delivery (COD)</strong>
                        <span class="text-muted small d-block mt-1">Pembayaran dilakukan secara tunai langsung kepada kurir saat pesanan tiba di alamat tujuan Anda.</span>
                    </div>
                </div>
            </div>

            @if($order->status==='dikirim')
                <div class="alert alert-warning mt-4 small mb-0 rounded-3">
                    <i class="bi bi-info-circle-fill me-1"></i> Pesanan sedang dalam pengiriman. Mohon siapkan uang tunai pas sebesar <strong>Rp {{ number_format($order->total,0,',','.') }}</strong>.
                </div>
                <form action="{{ route('orders.received', $order) }}" method="POST" class="mt-3"
                      onsubmit="return confirm('Konfirmasi pesanan sudah diterima dan dibayar?')">@csrf
                    <button class="btn btn-success w-100 py-2 fs-6 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Pesanan Diterima & Dibayar
                    </button>
                </form>
            @endif

        @else
            <div class="rounded-3 mb-3 overflow-hidden" style="border: 1.5px solid #e0e7ef;">
                {{-- Header BCA --}}
                <div class="d-flex align-items-center gap-3 px-4 py-3" style="background: #f0f6ff; border-bottom: 1.5px solid #e0e7ef;">
                    <img src="{{ asset('assets/images/Logo-BCA.jpeg') }}" alt="Bank BCA" style="height: 28px; object-fit: contain;">
                    <strong class="text-dark small">Tujuan Transfer Bank</strong>
                </div>
                {{-- Body --}}
                <div class="px-4 py-3 bg-white">
                    <div class="small text-muted mb-1">Bank</div>
                    <div class="fw-bold text-dark mb-3">{{ config('app.store.bank_name') }}</div>

                    <div class="small text-muted mb-1">No. Rekening</div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="fw-bold text-dark fs-5" id="order-rekening-number" style="letter-spacing: 0.06em;">{{ config('app.store.bank_account') }}</span>
                        <button type="button" onclick="copyOrderRekening()" id="order-copy-btn"
                            class="btn btn-sm d-flex align-items-center gap-1"
                            style="background:#e8f5e9; color:#2E7D32; border:1px solid #c8e6c9; border-radius:8px; font-size:12px; padding:4px 10px; font-weight:600;">
                            <i class="bi bi-copy" id="order-copy-icon"></i>
                            <span id="order-copy-label">Salin</span>
                        </button>
                    </div>

                    <div class="small text-muted mb-1">Atas Nama</div>
                    <div class="fw-bold text-dark">{{ config('app.store.bank_holder') }}</div>
                </div>
            </div>
            <script>
            function copyOrderRekening() {
                const num = document.getElementById('order-rekening-number').innerText.trim();
                navigator.clipboard.writeText(num).then(function() {
                    const label = document.getElementById('order-copy-label');
                    const icon  = document.getElementById('order-copy-icon');
                    const btn   = document.getElementById('order-copy-btn');
                    label.textContent = 'Tersalin!';
                    icon.className = 'bi bi-check-lg';
                    btn.style.background = '#c8e6c9';
                    btn.style.color = '#1B5E20';
                    setTimeout(function() {
                        label.textContent = 'Salin';
                        icon.className = 'bi bi-copy';
                        btn.style.background = '#e8f5e9';
                        btn.style.color = '#2E7D32';
                    }, 2000);
                });
            }
            </script>


            @if($order->rejection_reason && $order->status==='menunggu_pembayaran')
                <div class="alert alert-danger mt-3 small rounded-3">
                    <i class="bi bi-exclamation-octagon-fill me-1"></i> <strong>Pembayaran Ditolak:</strong> {{ $order->rejection_reason }}. Mohon unggah kembali bukti transfer yang valid.
                </div>
            @endif

            @if($order->status==='menunggu_pembayaran')
                <form action="{{ route('orders.upload-proof',$order) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Unggah Bukti Transfer (JPG, PNG, PDF - maks 5MB)</label>
                        <input type="file" name="payment_proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>
                    <button class="btn btn-tb-primary w-100 py-2 fs-6 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-upload"></i> Kirim Bukti Pembayaran</button>
                </form>
                <form action="{{ route('orders.cancel',$order) }}" method="POST" class="mt-2" onsubmit="return confirm('Batalkan pesanan ini?')">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm w-100 border-0">Batalkan Pesanan</button>
                </form>

            @elseif($order->status==='dikirim')
                <div class="alert alert-info mt-3 small rounded-3">
                    <i class="bi bi-truck me-1"></i> Pesanan sedang dikirim oleh kurir ke alamat Anda.
                </div>
                <form action="{{ route('orders.received', $order) }}" method="POST" class="mt-2"
                      onsubmit="return confirm('Konfirmasi pesanan sudah diterima?')">@csrf
                    <button class="btn btn-success w-100 py-2 fs-6 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Konfirmasi Barang Diterima
                    </button>
                </form>

            @elseif($order->payment_proof_url)
                <div class="mt-4">
                    <strong class="text-dark small d-block mb-2">Bukti Pembayaran yang Dikirim:</strong>
                    @if(str_ends_with($order->payment_proof_url, '.pdf'))
                        <a href="{{ asset('storage/'.$order->payment_proof_url) }}" target="_blank" class="btn btn-sm btn-tb-outline w-100 py-2"><i class="bi bi-file-earmark-pdf me-1"></i> Lihat Dokumen PDF</a>
                    @else
                        <div class="border rounded p-2 bg-light text-center">
                            <img src="{{ asset('storage/'.$order->payment_proof_url) }}" class="img-fluid rounded shadow-sm" style="max-height: 250px; object-fit: contain;">
                        </div>
                    @endif
                </div>
            @endif
        @endif
    </div>
</div>
</div>
@endsection