@extends('dashboard.layouts.master')


@section('bread')
    <li class="breadcrumb-item ">الدول</li>
    <li class="breadcrumb-item active">جميع الدول</li>
@endsection


@section('content')
    <livewire:admin.slider.index-slider-component />
@endsection

