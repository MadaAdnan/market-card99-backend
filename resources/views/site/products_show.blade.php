@extends('site.layouts.master')


@section('content')
    <img src="{{$product->getImage()}}" alt="" class="  bg-cover h-[200px] w-[200px] m-auto">
    <h4 class="text-gray-500 text-center mb-2">{{$product->name}}</h4>
    @if($product->info!=null)
        <div class="bg-secondary h-auto p-3 text-white md:w-1/2 w-[100%] m-auto">
            {{$product->info}}
        </div>
    @endif
    <div class="block p-6 max-w-1/2 m-auto mt-12 rounded-lg shadow-lg bg-white max-w-sm align-content-center">

        @if($product->type==\App\Enums\ProductTypeEnum::DEFAULT)
            <livewire:site.products.buy-items-conponent :product="$product"/>
        @else
            @if(!$product->is_free)
                <h4 class="text-red-400">سعر المنتج : {{number_format($product->getPrice(),2)}} $</h4>
            @endif
            <livewire:site.products.buy-product-component :product="$product" />
        @endif

    </div>



@endsection

