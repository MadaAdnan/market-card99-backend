@extends('dashboard.layouts.master')


@section('bread')
    <li class="breadcrumb-item ">المنتجات</li>
    <li class="breadcrumb-item active">جميع المنتجات</li>
@endsection


@section('content')
    <livewire:admin.products.index-product-component :category_id="$category_id"  />
@endsection

