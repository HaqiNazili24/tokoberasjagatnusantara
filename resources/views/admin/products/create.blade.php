@extends('layouts.kepala_toko')
@section('page-title','Produk Baru')
@section('content')
<form action="{{ route('kepala-toko.products.store') }}" method="POST" enctype="multipart/form-data">@csrf
    @include('admin.products._form')
</form>
@endsection
