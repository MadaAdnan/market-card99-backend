@extends('site.layouts.master')


@section('content')

    <section class="grid grid-cols-4 gap-4 mt-3">
        @foreach($groups as $group)
            <div
                class="col-span-4 col-start-1 md:col-span-2  md:col-start-2 shadow-md rounded py-1 px-2  border-[2px] border-gray-200 border-dashed @if(auth()->user()->group_id==$group->id) bg-[#F9AC44]  @endif ">
                <div class="grid grid-cols-3">
                    <div class="col-span-1">
                        <div
                            class="relative transition duration-[1000ms] ease-in-out filter @if(auth()->user()->group->sort > $group->sort)  grayscale-[100%] hover:grayscale-[0%] @else  grayscale-[100%]  @endif">
                            <img
                                class="h-[75px] w-fit rounded object-cover cursor-pointer "
                                src="{{$group->getFirstMediaUrl('image','webp')}}" alt="">
                            @if(auth()->user()->group->sort > $group->sort)
                                <span class="absolute top-[32px] left-[50%] text-white text-shadow-md">
       {{-- <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#00FF80" class="bi bi-check2-all"
             viewBox="0 0 16 16">
  <path
      d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
  <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708"/>
</svg>--}}
    </span>
                            @else

                            @endif
                        </div>
                    </div>
                    <div
                        class="col-span-2">
                        <div class="flex flex-col">
                            <h5 class="px-2 font-bold underline ">
                                {{$group->name}} @if(auth()->user()->group_id==$group->id) <span class="text-green-700">(أنت هنا)</span> @endif
                            </h5>
                            @if(auth()->user()->group_id==$group->id)
                                @php
                                    $bills=DB::table('bills')->where('user_id',auth()->id())->where('status',\App\Enums\BillStatusEnum::COMPLETE->value)
        ->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()])
        ->selectRaw('SUM(price+0) as total')->first()->total;
        $max=$group->min_value;
                                        if($loop->last){

    }else{
        $max=$groups[$loop->iteration]->min_value;
    }
    $total=$bills/($max+1)
                                @endphp
                                <p class="text-center">
                                    @if($loop->last)
                                        انت في أعلى مستوى
                                    @else
                                        الهدف للإنتقال إلى المستوى الأعلى <span class="text-red-700">{{$max}}</span>
                                    @endif
                                </p>
                                <div class="relative w-full md:w-1/2 h-[20px] bg-gray-500 rounded-lg m-auto">
                                    <div class="absolute top-0 left-0  h-[20px] bg-gradient-to-r from-red-400 via-red-500 to-green-600 rounded-lg"
                                         style="width:@if($total>100) 100% @else {{$total*100}}% @endif">
                                        <span class="absolute top-0 text-white"
                                              style="left:@if(($total*100)>100) 50% @else {{($total*100)/2}}% @endif">{{$bills+0}}</span>
                                    </div>
                                </div>
                            @endif


                        </div>
                    </div>

                </div>
            </div>
            {{--<div class="rounded my-1 shadow @if($notification->read_at==null) bg-info-gradient @else bg-white @endif">
               <h3 class="font-bold text-[25px] m-2">{{$notification->data['title']}}</h3>
               <p class="m-2">{{$notification->data['body']}}</p>
               @if(isset($notification->data['img']) && !empty($notification->data['img']) )
                   <img src="{{asset('storage/'.$notification->data['img'])}}" class="w-1/2 h-[120px]" alt="Notification">
                   @endif
               <div class="text-left p-2">
               <a class="text-left" href="{{$notification->data['route']}}">
               <i class="fa fa-arrow-circle-left text-blue-300 "></i>
               </a>
               </div>
            </div>--}}
        @endforeach
        <div class="col-span-4   rounded py-1 px-2 h-[50px]">
        </div>
    </section>
@endsection
