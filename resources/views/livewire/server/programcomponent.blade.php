<div>
    <section class="  fex flex-col w-full h-36 justify-center items-center">
        <div class=" flex  flex-col justify-center items-center my-5">
            <div class=" shadow-xl h-32 flex flex-col w-32 ">
                <img src="{{$country->getImage()}}" alt="img" class="w-full h-[70%]">
                <h1 class="flex justify-center items-center">{{$country->name}}</h1>
            </div>
            <div class="w-full h-8 px-10 flex items-center justify-center  ">
                <div class="h-[1px] bg-slate-500 w-full"></div>
            </div>
        </div>
    </section>


    <section class="grid  grid-cols-1 md:grid-cols-2 ">
        @if($order!=null && $order->id !=null)
        <livewire:site.order-component/>
        @endif

@foreach($programs as $program)
            <div class="flex   h-24 mx-3 mb-2 shadow-md  items-center  justify-evenly p-2 ">
                <div class="h-20 w-20 rounded-full bg-cover bg-center m-3  "
                     style="background-image: url('{{$program->getImage()}}')"></div>
                <div class="h-full w-[1px] bg-slate-500 ml-3"></div>
                <div class="flex flex-col justify-center items-start flex-1">
                    <h1 class="truncate min-w-[100px] max-w-[200px] ">{{$program->name}} </h1>
                    <p class="text-title font-sans">
                        @if(auth()->user()->hasRole('partner'))
                            {{number_format($program->pivot->price-( $program->pivot->price*$setting->discount_delegate_online),2)}}

                        @else
                            {{number_format($program->pivot->price,2)}}
                        @endif
                       </p>
                </div>
                <div class="h-full w-[1px] bg-slate-500 m-3 "></div>

                <div class="flex flex-col justify-center items-start m-3">

                    <button wire:click="buy({{$program->id}})" wire:loading.class="hidden">
                        <i class="fa-solid fa-cart-shopping text-slate-500"></i>
                        شراء</button>
                        <div wire:loading wire:target="buy({{$program->id}})" class="relative flex flex-row">
                           <span>  يرجى الإنتظار وعدم تحديث الصفحة جاري الحصول على رقم ...</span>
                            <svg class="spinner" viewBox="0 0 50 50">
                                <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                            </svg>

                        </div>
                </div>
            </div>
            <!--  -->
@endforeach
    </section>
</div>
