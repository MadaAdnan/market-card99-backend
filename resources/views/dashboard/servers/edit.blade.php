@extends('dashboard.layouts.master')

@section('bread')

@endsection


@section('content')
<livewire:admin.servers.edit-server-component :server="$server"/>
@endsection
