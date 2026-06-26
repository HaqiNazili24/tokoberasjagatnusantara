@extends('layouts.kepala_toko')
@section('page-title','Edit Produk')
@section('content')
<form action="{{ route('kepala-toko.products.update',$product) }}" method="POST" enctype="multipart/form-data">@csrf @method('PATCH')
    @include('admin.products._form')
</form>
@endsection
