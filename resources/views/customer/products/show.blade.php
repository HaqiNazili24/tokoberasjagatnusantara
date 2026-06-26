@extends('layouts.customer')
@section('title',$product->name)
@section('content')
<nav class="mb-4 small"><a href="{{ route('home') }}" class="text-success text-decoration-none fw-semibold">Beranda</a> <span class="text-mutedmx-1">/</span> <span class="text-muted">{{ $product->name }}</span></nav>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm overflow-hidden rounded-4 bg-white p-3">
            <img src="{{ $product->primary_image_url }}"
                 class="img-fluid rounded-3 w-100"
                 style="aspect-ratio:1/1; object-fit:cover;"
                 alt="{{ $product->name }}"
                 onerror="this.src='https://placehold.co/500x500/e8f5e9/2D5016?text=Beras'">
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-success-subtle text-success">{{ $product->subCategory->category->name }}</span>
                <span class="text-muted">/</span>
                <span class="text-muted small">{{ $product->subCategory->name }}</span>
            </div>
            
            <h2 class="fw-bold mb-2 text-dark" style="font-size: 1.8rem; letter-spacing: -0.01em;">{{ $product->name }}</h2>
            
            <div class="text-success fs-3 fw-bold mb-4">Rp {{ number_format($product->price,0,',','.') }}</div>
            
            <div class="row g-2 mb-4">
                <div class="col-6">
                    <div class="p-3 bg-light rounded-3">
                        <small class="text-muted d-block mb-1">Kemasan</small>
                        <span class="fw-semibold text-dark">{{ $product->weight_label }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 bg-light rounded-3">
                        <small class="text-muted d-block mb-1">Status Stok</small>
                        @if($product->stock > 0)
                            <span class="fw-semibold text-success">{{ $product->stock }} tersedia</span>
                        @else
                            <span class="fw-semibold text-danger">Stok Habis</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <h6 class="fw-bold mb-2 text-dark">Deskripsi Produk</h6>
            <p class="text-muted lh-base mb-4">{{ $product->description }}</p>

            @auth
                @if($product->stock > 0 && auth()->user()->isCustomer())
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-auto">
                        @csrf
                        <div class="row g-2 align-items-end">
                            <div class="col-4">
                                <label class="form-label small fw-semibold text-muted mb-1">Jumlah</label>
                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control" style="height: 44px;">
                            </div>
                            <div class="col-8">
                                <button class="btn btn-tb-primary w-100 d-flex align-items-center justify-content-center gap-2" style="height: 44px;">
                                    <i class="bi bi-cart-plus fs-5"></i> Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <button class="btn btn-secondary w-100 mt-auto py-2" disabled>Stok Habis</button>
                @endif
            @else
                <div class="mt-auto">
                    <a href="{{ route('login') }}" class="btn btn-tb-primary w-100 py-2">Login untuk Membeli</a>
                </div>
            @endauth
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-star-fill text-warning me-2"></i>Ulasan Pelanggan ({{ $product->reviews->count() }})</h4>
            
            <div class="d-flex align-items-center mb-4 p-3 rounded" style="background-color: #f2f0eb; max-width: 320px;">
                <div class="fs-1 fw-bold text-success me-3">{{ $product->average_rating }}</div>
                <div>
                    <div class="text-warning">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= round($product->average_rating))
                                ⭐
                            @else
                                
                            @endif
                        @endfor
                    </div>
                    <small class="text-muted">Rata-rata rating beras ini</small>
                </div>
            </div>

            <div class="list-group list-group-flush">
                @forelse($product->reviews as $review)
                <div class="list-group-item px-0 py-3" style="border-color: #edebe9 !important;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong class="text-dark d-block mb-1">{{ $review->user->full_name }}</strong>
                            <div class="text-warning mb-2" style="font-size: 12px;">
                                @for($i=1; $i<=5; $i++)
                                    {{ $i <= $review->rating ? '⭐' : '' }}
                                @endfor
                            </div>
                            <p class="text-dark mb-0" style="font-size: 14px;">{{ $review->comment }}</p>
                        </div>
                        <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-chat-left-dots fs-1 opacity-25 d-block mb-2"></i>
                    Belum ada ulasan untuk produk ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection