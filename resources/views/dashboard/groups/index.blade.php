@extends('dashboard.layouts.master')


@section('bread')
    <li class="breadcrumb-item ">الفئات</li>
    <li class="breadcrumb-item active">جميع الفئات</li>
@endsection


@section('content')
    <livewire:admin.groups.index-group-component />
@endsection

