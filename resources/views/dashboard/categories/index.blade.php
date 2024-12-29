@extends('dashboard.layouts.master')


@section('bread')
    <li class="breadcrumb-item ">الأقسام</li>
    <li class="breadcrumb-item active">جميع الأقسام</li>
@endsection


@section('content')
    <livewire:admin.categories.index-category-component />
@endsection

