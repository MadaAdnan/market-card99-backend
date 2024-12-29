@extends('site.layouts.master')


@section('content')
    <img src="{{$category->getImage()}}" alt="" class="  bg-cover h-[200px] w-[200px] m-auto">
    <h4 class="text-gray-500 text-center mb-2">{{$category->name}}</h4>
    @if($category->info!=null)
        <marquee class="bg-secondary h-[50px] flex items-center text-white " direction="right">
            {{$category->info}}
        </marquee>
    @endif
    <section class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 mt-2 gap-2 \ ">

        @foreach($products as $product)

            <div style="grid-template-columns: 100px calc(100% - 100px);" class="grid bg-[#000000AE] rounded w-full h-[100px]">
                <div class="p-2 rounded">
                    <img class="rounded" src="{{$product->getImage()}}" alt="">
                </div>
                <div style="grid-template-columns: repeat(3,33.33%);grid-template-rows: repeat(3,auto)" class="grid ">
                   <span style="grid-row: span 2" class="pt-2 text-white row-start-1 col-span-2 ">
                       {{Str::words($product->name,11)}}
                       @if($product->is_discount)
                           <span class="text-secondary text-[10px]">(عرض) <i class="fa fa-star"></i></span>

                       @endif
                   </span>
                   <span class="row-start-1 col-start-3 text-left text-white pt-1 px-2">
                        @if($product->is_free)
                           @php
                               try {
                               echo '<span class="text-secondary">$</span>'.number_format(($product->getPrice()/$product->amount)*$product->min_amount,2);
}catch (Exception|Error $e){

}
                           @endphp

                       @else
                           <span
                               class="text-secondary">$</span>{{number_format($product->getPrice(),2)}}
                       @endif
                   </span>
                    <span style="grid-row: span 2" class="row-start-3 col-start-3  text-left pt-1 px-2 ">

                        @if(!$product->is_available )
                                <a href="#"
                                   class="  px-2 py-1 bg-red-700 text-center text-[10px] text-white rounded">غير متوفر</a>
                            @else
                                <a href="{{route('products.show',$product)}}"
                                   class=" pt-1 px-2   bg-secondary text-center rounded">شراء</a>

                            @endif
                    </span>

                </div>
            </div>

           {{-- <div class="bg-[#000000AE] rounded">
                <div class="flex w-full">
                    <div class="p-3 relative w-[100px] h-[100px]">
                        <img class="w- rounded-lg w-fit  " src="{{$product->getImage()}}" alt="">
                    </div>
                    <div style="grid-template-rows:60% 40%;grid-template-columns: repeat(3,33.33%)" class="grid  text-white pr-2 h-100 w-full  ">

                        <span class="text-[15px] col-span-3 block  text-bold mt-2">
                            {{$product->name}}
                            @if($product->is_discount)
                                <span class="text-secondary text-[10px]">(عرض) <i class="fa fa-star"></i></span>

                            @endif
                        </span>
                        <span  class="text-[13px]  block row-start-2 col-start-3  text-left">
                           <span class="flex flex-col">
                               <span>
                                @if($product->is_free)
                                   @php
                                       try {
                                       echo '<span class="text-secondary">$</span>'.number_format(($product->getPrice()/$product->amount)*$product->min_amount,2);
        }catch (Exception|Error $e){

        }
                                   @endphp

                               @else
                                   <span
                                       class="text-secondary">$</span>{{number_format($product->getPrice(),2)}}
                               @endif
                                   </span>
                               <span>
 @if(!$product->is_available)
                                   <a href="#"
                                      class="  px-2 py-1 bg-red-700 text-center rounded">غير متوفر</a>
                               @else
                                   <a href="{{route('products.show',$product)}}"
                                      class=" px-2 py-1   bg-secondary text-center rounded">شراء</a>

                               @endif
                                   </span>
                              --}}{{-- <div class="relative w-full">

                                   <div class="mt-1 absolute bottom-1 left-1">
                                       <div class=" flex flex-col">
                                              <span class="text-left inline-block ">
                                   @if($product->is_free)
                                                      @php
                                                          try {
                                                          echo '<span class="text-secondary">$</span>'.number_format(($product->getPrice()/$product->amount)*$product->min_amount,2);
                           }catch (Exception|Error $e){

                           }
                                                      @endphp

                                                  @else
                                                      <span
                                                          class="text-secondary">$</span>{{number_format($product->getPrice(),2)}}
                                                  @endif

                               </span>

                                       </div>

                                       </div>
                               </div>--}}{{--

                           </span>
                            </span>
                    </div>
                </div>
            </div>--}}
        @endforeach


    </section>

@endsection

