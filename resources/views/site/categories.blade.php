@extends('site.layouts.master')


@section('content')
    <section class="relative">


    @if($sliders->count()>0)
        <!-- build slider images  section -->
            <section class="md:w-1/2 m-auto">
                <div class="swiper h-[200px]  ">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        @foreach($sliders as $slider)
                            <img src="{{$slider->getImage()}}" alt=""
                                 class="swiper-slide  bg-cover h-full w-full">
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
        <marquee class="bg-secondary h-[50px] m-auto md:w-1/2 flex items-center text-white " direction="right">
            {{getSettings('news')}}
        </marquee>
    </section>
    <section class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-4 m-auto md:w-1/2 mt-4  gap-3 px-2 ">

        @foreach($categories as $category)
            <a @if($category->is_available) href="{{route('products.index',$category)}}" @endif class="rainbow">
                <div class=" shadow-xl h-32 flex flex-col relative">
                    @if(!$category->is_available)
                        <span class="absolute bg-red-700 text-white text-[10px] rounded ">غير متوفر</span>
                    @endif
                    <img src="{{$category->getImage()}}" alt="img" class="w-full h-[70%]">
                    <h4 class="flex text-12 justify-center items-center text-ellipsis">{{$category->name}} </h4>
                </div>
            </a>

        @endforeach


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
