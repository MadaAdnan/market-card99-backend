@extends('site.layouts.master')


@section('content')
    <div class="grid grid-cols-4 gap-3">
        <div class="bg-blue-700 col-span-2 md:col-span-1 md:col-start-2 ">
            <div class="flex flex-col items-center p-2">
                <h1 class="text-white text-bold my-2">مجموع المشتريات خلال شهر {{now()->startOfMonth()->subDay()->monthName}}</h1>
           @php
               $total=auth()->user()->bills()->where('status','complete')->whereBetween('created_at',[now()->startOfMonth()->subDay()->startOfMonth(),now()->startOfMonth()->subDay()->endOfMonth()])->sum('price')
           @endphp
                <p class="text-white text-bold">{{number_format($total,2)}} $</p>
            </div>
        </div>
        <div class="bg-red-700 col-span-2 md:col-span-1">
            <div class="flex flex-col items-center p-2">
                <h1 class="text-white text-bold my-2">مجموع المشتريات خلال الشهر الحالي </h1>
                @php
                    $total=auth()->user()->bills()->where('status','complete')->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()])->sum('price')
                @endphp
                <p class="text-white text-bold">{{number_format($total,2)}} $</p>
            </div>
        </div>
    </div>
    <livewire:site.list-bills-component/>
@endsection
