@extends('layouts.owner')
@section('page-title','Edit Produk Beras')
@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color: #006241;">Edit Detail Produk Beras</h4>
    <p class="text-muted">Perbarui data produk beras, harga, atau stok real-time.</p>
</div>

<form action="{{ route('owner.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('owner.products._form')
</form>

@endsection
