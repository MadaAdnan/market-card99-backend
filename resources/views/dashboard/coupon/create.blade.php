@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">الكوبونات</li>
    <li class="breadcrumb-item active">إضافة كوبون</li>
@endsection


@section('content')

<livewire:admin.coupon.create-coupon-component/>
@endsection
