@extends('dashboard.layouts.master')


@section('bread')
    <li class="breadcrumb-item ">المستخدمين</li>
    <li class="breadcrumb-item active">جميع المستخدمين</li>
@endsection


@section('content')
    <livewire:admin.orders.index-order-component />
@endsection

