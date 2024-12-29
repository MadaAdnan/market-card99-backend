@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">المنتجات</li>
    <li class="breadcrumb-item active">تعديل نتج {{$product->name}}</li>
@endsection


@section('content')
    <livewire:admin.products.edit-product-component :product="$product"/>
@endsection
