@extends('site.layouts.master')

@section('content')

    <livewire:server.programcomponent :server="$server" :country="$country"/>
@endsection
