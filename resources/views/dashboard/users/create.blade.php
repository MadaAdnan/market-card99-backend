@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">المستخدمين</li>
    <li class="breadcrumb-item active">إضافة مستخدم</li>
@endsection


@section('content')
<livewire:admin.users.create-user-component/>
@endsection
