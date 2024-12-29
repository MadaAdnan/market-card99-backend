@extends('dashboard.layouts.master')


@section('bread')
    <li class="breadcrumb-item ">الإشعارات</li>
    <li class="breadcrumb-item active">إرسال إشعار</li>
@endsection


@section('content')
   <div class="container-fluid">
       <div class="row">
           <div class="col-md-12">
               <livewire:admin.notifications.create-notification-component :user_id="request()->input('user_id')"/>
           </div>
       </div>
   </div>
@endsection

