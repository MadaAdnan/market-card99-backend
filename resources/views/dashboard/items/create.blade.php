@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">رفع أكواد</li>
    <li class="breadcrumb-item active">{{$product->name}}</li>
@endsection


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <livewire:admin.items.create-items-component :product="$product"/>


            </div>
            <div class="col-md-6">
                <livewire:admin.items.index-items-component :product="$product"/>
            </div>
        </div>
    </div>

@endsection
