@extends('site.layouts.master')


@section('content')

    <section class="grid grid-cols-4 gap-4">
        @foreach($notifications as $notification)
            <div
                class="col-span-4 col-start-1 md:col-span-2  md:col-start-2 shadow-md rounded py-1 px-2  border-[2px] border-gray-200 border-dashed @if($notification->read_at==null) bg-[#F9AC44]  @endif ">
                <div class="grid grid-cols-3">
                    <div class="col-span-1">
                        @if(isset($notification->data['img']) && !empty($notification->data['img']))
                            <a href="{{asset('storage/'.$notification->data['img'])}}" target="_blank">
                                <img class="h-[150px] w-fit object-cover cursor-pointer"
                                     src="{{asset('storage/'.$notification->data['img'])}}" alt="">
                            </a>

                        @endif
                    </div>
                    <div
                        class="col-span-2 @if(!(isset($notification->data['img']) && !empty($notification->data['img']))) order-first @endif">
                        <div class="flex flex-col">
                            <h5 class="px-2 font-bold underline">
                                @if(isset($notification->data['route']))
                                    <a
                                       href="{{$notification->data['route']}}">{{$notification->data['title']}}</a>
                                @endif
                            </h5>
                            <p class="px-2 py-2">{{$notification->data['body']}}</p>


                        </div>
                    </div>

                </div>
            </div>
        @endforeach
        <div class="col-span-2 col-start-2 mb-2">
            {{$notifications->links()}}
        </div>
    </section>
@endsection
