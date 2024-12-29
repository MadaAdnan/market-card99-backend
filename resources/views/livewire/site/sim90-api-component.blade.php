<section>

    <section class="pt-4">
        <h1 class="text-title font-bold text-2xl ">اختر hلسيرفر </h1>
        <p class=" pb-5">عند اختيارك للسيرفر ستظهر الدولل المتاحه </p>
        <div class="flex flex-col lg:flex-row lg:gap-2">
            <!-- drow drop down  -->
            <div class=" flex   items-center justify-center w-full ">
                <div class="inline-block relative w-full">

                    <select class="w-1/2 rounded " wire:model="server_id">
                        <option value="">إختر السيرفر</option>
                        @foreach($servers as $serv)
                            <option value="{{$serv['id']}}">{{$serv['name']}}</option>
                        @endforeach
                    </select>


                </div>
            </div>
        </div>
    </section>
    <section class="pt-7 ">
        <h1 class="text-title font-bold text-2xl ">اختر الدوله </h1>
        <div class="grid grid-cols-3 lg:grid-cols-5 md:grid-cols-4 gap-3 mt-5">

            @foreach($countries as $co)
                <div
                    class=" shadow-xl h-32 flex flex-col cursor-pointer @if($country_id==$co['id']) border-[#FF0000] border-[3px]  @endif"
                    wire:click="changeCountry({{$co['id']}})">

                    <img src="{{$co['img']}}" alt="img" class="w-full h-[70%]">
                    <h1 class="flex justify-center items-center">{{$co['name']}}</h1>

                </div>
            @endforeach

        </div>
    </section>

    <section class="pt-7 ">
        <h1 class="text-title font-bold text-2xl ">اختر التطبيق </h1>

        <div class="grid grid-cols-3 lg:grid-cols-5 md:grid-cols-4 gap-3 mt-5">

            @foreach($apps as $app)
                <div
                    class=" shadow-xl h-32 flex flex-col cursor-pointer @if($app_id==$app['id']) border-[#FF0000] border-[3px]  @endif">

                    <img src="{{$app['img']}}" alt="img" class="w-full h-[70%]">
                    <h1 class="flex justify-center items-center">{{$app['name']}}</h1>

                    <button type="button" wire:click="buyNumber({{$app['id']}})"
                            class="inline-flex justify-around items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <!-- Heroicon name: mini/check -->
                        {{--<svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                  clip-rule="evenodd"/>
                        </svg>--}}

                            <span>شراء</span>
                            <span> {{$app['price']+($app['price']*getSettings('win_sim90_ratio')??0)}}</span>


                    </button>
                </div>
            @endforeach

        </div>
    </section>


</section>
