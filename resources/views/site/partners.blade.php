@extends('site.layouts.master')


@section('content')

    <section class="block">
        <marquee class="bg-secondary h-[50px] flex items-center text-white ">
            {{getSettings('news')}}
        </marquee>
    </section>




               <div class="grid  md:grid-cols-2 lg:grid-cols-3 grid-cols-1  gap-3">
                   @foreach($partners as $partner)
                   <div class=" flex-row md:flex-col md:max-w-xl rounded-lg bg-white shadow-lg ">
                       {{--<img class=" w-full h-96 md:h-auto object-cover md:w-48 rounded-t-lg md:rounded-none md:rounded-l-lg"
                            src="https://mdbootstrap.com/wp-content/uploads/2020/06/vertical.jpg" alt="" />--}}
                       <div class="p-6 flex flex-col justify-center items-center">
                           <h5 class="text-gray-700 text-base my-4">اسم الوكيل : {{$partner->name}}</h5>
                           <div class="border border-[#ebebeb] border-2 w-full"></div>
                           <p class="text-gray-700 text-base my-4">
                               العنوان : {{$partner->address}}
                           </p>
                           <div class="border border-[#ebebeb] border-2 w-full"></div>
                           <p class="text-gray-700 text-base my-4">رقم الهاتف : <a href="https://api.whatsapp.com/send?phone={{$partner->phone}}" class="text-blue-600">{{$partner->phone}}</a></p>
                       </div>
                   </div>
                   @endforeach
               </div>







@endsection

