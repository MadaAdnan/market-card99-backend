@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">المنتجات</li>
    <li class="breadcrumb-item active">إضافة منتج</li>
@endsection


@section('content')
<livewire:admin.products.create-product-component/>
@endsection
