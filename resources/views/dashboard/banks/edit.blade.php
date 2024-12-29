@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">طرق الدفع</li>
    <li class="breadcrumb-item active">تعديل طريقة الدفع {{$bank->name}}</li>
@endsection


@section('content')
   <livewire:admin.banks.edit-bank-component :bank="$bank"/>
@endsection
