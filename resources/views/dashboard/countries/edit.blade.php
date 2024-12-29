@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">الدول</li>
    <li class="breadcrumb-item active">تعديل دولة {{$country->name}}</li>
@endsection


@section('content')
    <livewire:admin.countries.edit-country-component :country="$country"/>
@endsection
