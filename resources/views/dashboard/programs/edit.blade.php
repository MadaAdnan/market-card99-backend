@extends('dashboard.layouts.master')

@section('bread')

@endsection


@section('content')
<livewire:admin.programs.edit-program-component :program="$program"/>
@endsection
