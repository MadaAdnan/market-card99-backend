@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">المستخدمين</li>
    <li class="breadcrumb-item active">إضافة رصيد للمستخدم {{$user->name}}</li>
@endsection


@section('content')
<livewire:admin.users.create-balance-component :user="$user"/>
@endsection
