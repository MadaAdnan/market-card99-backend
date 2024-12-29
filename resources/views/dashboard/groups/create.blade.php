@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">الفئات</li>
    <li class="breadcrumb-item active">إضافة فئة</li>
@endsection


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <livewire:admin.groups.create-group-component/>
            </div>
        </div>
    </div>

@endsection
