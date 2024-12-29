@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">الأقسام</li>
    <li class="breadcrumb-item active">إضافة قسم</li>
@endsection


@section('content')
<livewire:admin.categories.create-category-component/>
@endsection
