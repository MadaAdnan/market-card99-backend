@extends('site.layouts.master')


@section('content')
    <p class="p-2 shadow border-[#D6D6D6] rounded">
        {!! nl2br(getSettings('info_present')) !!}
    </p>
    <form action="{{route('asks.store')}}" method="post">
        @csrf
        @foreach($asks as $ask)

            <div class="form-group mb-6">
                <label  class="form-label inline-block mb-2 text-gray-700">{{$ask->ask}}</label>
                <input type="text" class="form-control
        block
        w-full
        px-3
        py-1.5
        text-base
        font-normal
        text-gray-700
        bg-white bg-clip-padding
        border border-solid border-gray-300
        rounded
        transition
        ease-in-out
        m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none @error('answers.'.$ask->id)  is-invalid @enderror"
                        required value="{{old('answers.'.$ask->id)}}" name="answers[{{$ask->id}}]">
                @error('answers.'.$ask->id)
                <small  class="block mt-1 text-xs text-gray-600">{{$message}}</small>
                @enderror
            </div>
        @endforeach

        <button type="submit"  class="
      px-6
      py-2.5
      bg-primary
      text-white
      font-medium
      text-xs
      leading-tight
      uppercase
      rounded
      shadow-md
      hover:bg-blue-700 hover:shadow-lg
      focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0
      active:bg-blue-800 active:shadow-lg
      transition
      duration-150
      ease-in-out">
            <span>إرسال الطلب</span>
        </button>

    </form>
@endsection

