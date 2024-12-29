@extends('site.layouts.master')


@section('content')
    <img src="{{asset('dist/img/offers.jpg')}}" alt="" class="  bg-cover h-[200px] w-[200px] m-auto">
    <h4 class="text-gray-500 text-center mb-2">قسم العروض</h4>

    <section class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 mt-2 ">

        @foreach($products as $product)

            <div class="flex   h-24 mx-3 mb-2 shadow-md  items-center  justify-evenly p-2 ">
                <div class="h-20 w-20   bg-cover bg-center m-3 relative "
                     style="background-image: url('{{$product->getImage()}}')">
                    @if($product->is_discount)
                        <img src="{{asset('dist/img/offer.png')}}" class="absolute md:w-[100px] md:h-[60px] md:top-[-16px] md:right-[38%] top-[-16px] right-[38%] "  alt="">

                    @endif

                </div>
                <div class="h-full w-[1px] bg-slate-500 ml-3"></div>
                <div class="flex flex-col justify-center items-start flex-1">
                    <h1>{{$product->name}} </h1>
                    <p class="text-primary">{{$product->total_price}}</p>
                </div>
                <div class="h-full w-[1px] bg-slate-500 m-3 "></div>
                <div class="flex flex-col justify-center items-start m-3">
                    @if(!$product->is_available)

                        <span class="text-red-600">غير متوفر </span>
                    @elseif($product->type==\App\Enums\ProductTypeEnum::DEFAULT && $product->items()->active()->count()==0)
                       <span class="text-red-600">غير متوفر </span>

                    @else
                        <a href="{{route('products.show',$product)}}">
                    <i class="fa-solid fa-cart-shopping text-slate-500"></i>
                    <p>
                      شراء
                    </p>
                        </a>
                    @endif
                </div>
            </div>
            <!--  -->
        @endforeach






    </section>

@endsection

