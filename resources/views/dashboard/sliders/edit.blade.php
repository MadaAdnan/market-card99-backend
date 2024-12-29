@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">الإعلانات</li>
    <li class="breadcrumb-item active">تعديل الإعلان </li>
@endsection


@section('content')
    <livewire:admin.slider.edit-slider-component :slider="$slider"/>
@endsection
