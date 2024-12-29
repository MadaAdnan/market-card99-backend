@extends('dashboard.layouts.master')

@section('bread')

@endsection


@section('content')
<livewire:admin.program-relation-server-component :server="$server"/>
@endsection
