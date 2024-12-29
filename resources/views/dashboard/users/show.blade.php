@extends('dashboard.layouts.master')


@section('bread')
    <li class="breadcrumb-item ">المستخدمين</li>
    <li class="breadcrumb-item active">حركة رصيد المستخدم {{$user->name}}</li>
@endsection


@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card card-body">
                        <p class="text-center">الرصيد الحالي</p>
                        <p class="text-center">{{$user->balance}} $</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card card-body">
                        <p class="text-center">المشتريات من {{now()->startOfMonth()->format('Y-m-d')}}</p>
                        <p class="text-center">{{$user->debit}} $</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <livewire:admin.users.bill-user-component :user="$user" />
        </div>
        <div class="col-md-12">
            <livewire:admin.users.order-user-component :user="$user" />
        </div>
        <div class="col-md-12">
            <livewire:admin.users.balance-user-component :user="$user" />
        </div>
    </div>
</div>
@endsection

