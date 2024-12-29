@extends('site.layouts.master')


@section('content')
    @if($notifications->count()>0)

        <div class="w-full absolute h-[100%] bg-[#000000AE] top-0 left-0 z-[10000] flex justify-center items-center ">
            <div
                class="relative rainbow w-[90%] md:w-1/2 px-4 h-1/3 bg-white m-auto rounded shadow justify-around items-center  flex flex-col p-4">
                <h3 class="text-center text-[30px]">{{$notification->data['title']}}</h3>
                <p>{{$notification->data['body']}}</p>
               @if(isset($notification->data['img']) && !empty($notification->data['img']))
                    <img src="{{asset('storage/'.$notification->data['img'])}}" class="h-1/3 w-1/2" alt="">
                   @endif
                <div class=" border-top w-full border-[#FFBF5B] border-[1px]"></div>
                <a href="{{route('notifications.index')}}" class="bg-[#FFBF5B] px-4 py-1.5 mb-2 rounded text-white">إذهب</a>
            </div>
        </div>
    @endif
    <section class="relative">


    @if($sliders->count()>0)
        <!-- build slider images  section -->
            <section class="md:w-1/2 m-auto">
                <div class="swiper h-[200px]  ">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        @foreach($sliders as $slider)
                            <a class="swiper-slide" target="_blank" @if(!empty($slider->face)) href="{{$slider->face}}" @elseif(!empty($slider->whats)) href="https://wa.me/{{ltrim(ltrim($slider->whats,'+'),'00')}}" @endif>
                            <img src="{{$slider->getImage()}}" alt=""
                                 class="  bg-cover h-full w-full">
                            </a>
                        @endforeach


                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>
                    <!-- If we need navigation buttons -->
                    {{--                <div class="swiper-button-prev"></div>--}}
                    {{--                <div class="swiper-button-next"></div>--}}
                </div>
            </section>
        @endif
        <marquee class="bg-secondary h-[50px] md:w-1/2 m-auto flex items-center text-white " direction="right">
            {{getSettings('news')}}
        </marquee>
    </section>
    <section class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 mt-4 md:w-1/2 m-auto  gap-3 px-2  align-items-center align-content-center">

        <a href="{{route('online.index')}}" class="rainbow">
            <div class=" shadow-xl h-32 flex flex-col ">
                <img src="{{asset('assets/images/online_main.jpg')}}" alt="img" class="w-full h-[70%]">
                <h4 class="flex text-12 justify-center items-center text-ellipsis">أونلاين </h4>
            </div>
        </a>
        @if(getSettings('is_active_sim90')=='active')
            <a href="{{route('online2.index')}}" class="rainbow">
                <div class=" shadow-xl h-32 flex flex-col ">
                    <img src="{{asset('assets/images/online_main.jpg')}}" alt="img" class="w-full h-[70%]">
                    <h4 class="flex text-12 justify-center items-center text-ellipsis">أونلاين </h4>
                </div>
            </a>
        @endif
        @foreach($categories as $category)
            <a @if($category->is_available) href="@if($category->has('categories')) {{route('category.show',$category)}} @else {{route('products.index',$category)}} @endif"  @endif class="rainbow">
                <div class=" shadow-xl h-32 flex flex-col relative ">
                    @if(!$category->is_available)
                    <span class="absolute bg-red-700 text-[10px] text-white rounded ">غير متوفر</span>
                    @endif
                    <img src="{{$category->getImage()}}" alt="img" class="w-full h-[70%]">
                    <h4 class="flex text-12 justify-center items-center text-ellipsis">{{$category->name}} </h4>
                </div>
            </a>

        @endforeach
        <a  href="{{route('offers.index')}}"  class="rainbow">
            <div class=" shadow-xl h-32 flex flex-col relative ">
                <img src="{{asset('dist/img/offers.jpg')}}" alt="img" class="w-full h-[70%]">
                <h4 class="flex text-12 justify-center items-center text-ellipsis">قسم العروض </h4>
            </div>
        </a>

    </section>
@endsection
@push('js')

    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {

            loop: true,
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
            },
            lazy: true,
            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },


        });
    </script>

@endpush
