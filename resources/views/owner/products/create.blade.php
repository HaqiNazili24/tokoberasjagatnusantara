@extends('layouts.owner')
@section('page-title','Tambah Produk Beras')
@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color: #006241;">Tambah Produk Beras Baru</h4>
    <p class="text-muted">Buat detail produk beras baru untuk dijual di katalog.</p>
</div>

<form action="{{ route('owner.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('owner.products._form')
</form>

@endsection
