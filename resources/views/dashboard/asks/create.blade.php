@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">أسئلة طلب الوكالة</li>
    <li class="breadcrumb-item active">إضافة سؤال</li>
@endsection


@section('content')
<livewire:dashboard.ask.create-ask-component/>
@endsection
