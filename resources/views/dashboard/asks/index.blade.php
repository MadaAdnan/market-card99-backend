@extends('dashboard.layouts.master')


@section('bread')
    <li class="breadcrumb-item ">الأسئلة</li>
    <li class="breadcrumb-item active">جميع الأسئلة</li>
@endsection


@section('content')
    <livewire:dashboard.ask.list-ask-component/>
@endsection

