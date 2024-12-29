@extends('dashboard.layouts.master')


@section('bread')
    <li class="breadcrumb-item ">الطلبات</li>
    <li class="breadcrumb-item active">جميع الطلبات</li>
@endsection


@section('content')
   <div class="container-fluid">

{{--           <livewire:admin.balances.balance-goreg-sms-component/>--}}
           <livewire:admin.statistics.total-balance-component/>

       <div class="row">
           <div class="col-md-12">
               <livewire:admin.statistics.order-component/>
           </div>
       </div>
       <div class="row">
           <div class="col-md-12">
               <livewire:life-cash-component/>
           </div>
       </div>
   </div>
@endsection

