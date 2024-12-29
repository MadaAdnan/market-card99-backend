@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">الأقسام</li>
    <li class="breadcrumb-item active">تعديل قسم {{$category->name}}</li>
    <li class="breadcrumb-item active">ترتيب المنتجات</li>
@endsection


@section('content')
  <livewire:admin.products.sortable-product-component :category="$category"/>
@endsection
