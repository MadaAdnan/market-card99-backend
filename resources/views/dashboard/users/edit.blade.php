@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">المستخدمين</li>
    <li class="breadcrumb-item active">تعديل مستخدم {{$user->name}}</li>
@endsection


@section('content')
    <livewire:admin.users.edit-user-component :user="$user"/>
@endsection
