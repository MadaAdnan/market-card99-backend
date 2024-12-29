@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">الأقسام</li>
    <li class="breadcrumb-item active">تعديل قسم {{$category->name}}</li>
@endsection


@section('content')
    <livewire:admin.categories.edit-category-component :category="$category"/>
@endsection
