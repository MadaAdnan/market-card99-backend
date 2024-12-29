@extends('dashboard.layouts.master')

@section('bread')
    <li class="breadcrumb-item ">الفئات</li>
    <li class="breadcrumb-item active">تعديل فئة {{$group->name}}</li>
@endsection


@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <livewire:admin.groups.edit-group-component :group="$group"/>
            </div>
        </div>
    </div>

@endsection
