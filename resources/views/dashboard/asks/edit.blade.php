@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">أشئلة طلب الوكالة</li>
    <li class="breadcrumb-item active">تعديل سؤال {{$ask->ask}}</li>
@endsection


@section('content')
    <livewire:dashboard.ask.edit-ask-component :ask="$ask"/>
@endsection
