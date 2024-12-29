@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">الدول</li>
    <li class="breadcrumb-item active">إضافة دولة</li>
@endsection


@section('content')
<livewire:admin.countries.create-country-component/>
@endsection
