@extends('dashboard.layouts.master')


@section('bread')
    <li class="breadcrumb-item ">الكوبونات</li>
    <li class="breadcrumb-item active">جميع الكوبونات</li>
@endsection


@section('content')
  <livewire:admin.coupon.list-coupon-component/>

@endsection

