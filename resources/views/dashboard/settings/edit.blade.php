@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">الإعدادات</li>
    <li class="breadcrumb-item active">تعديل الإعدادات </li>
@endsection


@section('content')
    <livewire:admin.setting.edit-setting-component :setting="$setting"/>
@endsection
