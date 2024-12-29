<section>

    <section class="pt-4">
        <h1 class="text-title font-bold text-2xl ">اخترا لسيرفر </h1>
        <p class=" pb-5">عند اختيارك للسيرفر ستظهر الدولل المتاحه </p>
        <div class="flex flex-col lg:flex-row lg:gap-2">
            <!-- drow drop down  -->
            <div class=" flex   items-center justify-center w-full ">
                <div x-cloak class="inline-block relative w-full" x-data="{open: false}">
                    <button @click="open = !open" @click.away="open = false"
                            class="shadow pr-2 cursor-pointer w-full outline outline-1 flex justify-between h-10 items-center  "
                            :class="{ 'shadow-none border-gray-300': open}">
                        @if($server_id!=null)
                            {{$server->name}}
                        @else
                        اختر السيرفر

                        @endif
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" :class="{'-rotate-180': open}"
                             class="ml-1 transform duration-200 inline-block fill-current text-gray-500 w-6 h-6">
                            <path fill-rule="evenodd"
                                  d="M15.3 10.3a1 1 0 011.4 1.4l-4 4a1 1 0 01-1.4 0l-4-4a1 1 0 011.4-1.4l3.3 3.29 3.3-3.3z"/>
                        </svg>
                    </button>

                    <ul x-show="open"
                        class="bg-white z-[10000] absolute left-0 shadow-lg w-full rounded text-gray-600 origin-top outline outline-1 "
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-y-50"
                        x-transition:enter-end="opacity-100 transform scale-y-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-end="opacity-0 transform scale-y-50">


                            <!--  -->
                        @foreach($servers as $serv)
                            <li
                                class="py-1 px-3 block hover:bg-gray-200 border-solid border-b border-b-slate-400 h-[75px]">
                                <button class="bg-transparent w-full h-full" wire:click="changeServerId({{$serv->id}})">
                                   <span class="flex justify-evenly items-center w-[100%]">
                                <span>{{$serv->name}}</span>
                                @if($serv->img!=null)
                                           <img src="{{$serv->getImage()}}" class="img-fluid" width="60" alt="{{$serv->name}}">
                                       @endif
                            </span>
                                </button>

                            </li>
                        @endforeach

                        <!--  -->
                    </ul>
                </div>
            </div>
            <!-- drow search field  -->
            <label class="relative block  my-3  w-full ">
                    <span class="absolute inset-y-0  flex items-center left-0 pl-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                <input class=" w-full bg-transparent  outline outline-1 h-10 pl-10 focus:outline-primary pr-2   "
                       placeholder=" ابحث عن دوله .." type="text" wire:model="search"/>
            </label>
        </div>
    </section>
    <section class="pt-7 ">
        <h1 class="text-title font-bold text-2xl ">اخترا الدوله </h1>
        <p>يمكنك اختيار دوله للذهاب الى المنتجات </p>
        <div class="grid grid-cols-3 lg:grid-cols-5 md:grid-cols-4 gap-3 mt-5">

            @foreach($countries as $co)
                <div class=" shadow-xl h-32 flex flex-col " wire:click="changeCountry({{$co->id}})">

                    <img src="{{$co->getImage()}}" alt="img" class="w-full h-[70%]">
                    <h1 class="flex justify-center items-center">{{$co->name}}</h1>

                </div>
            @endforeach

        </div>
    </section>



</section>
