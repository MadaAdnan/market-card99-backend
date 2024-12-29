@extends('dashboard.layouts.master')

@section('bread')

@endsection


@section('content')
<livewire:admin.country-relation-server-component :server="$server"/>
@endsection
