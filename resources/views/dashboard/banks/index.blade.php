@extends('dashboard.layouts.master')


@section('bread')
    <li class="breadcrumb-item ">الطلبات</li>
    <li class="breadcrumb-item active">جميع الطلبات</li>
@endsection


@section('content')
   <livewire:admin.banks.index-bank-component/>

@endsection

