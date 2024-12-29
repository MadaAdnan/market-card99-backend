<section class="table w-full  " >

  <div class="overflow-x-auto relative m-4      rounded-t-2xl">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-white uppercase bg-main  text-center">
            <tr>
                @if(auth()->check())
                <th scope="col" class="py-2 px-6">
                    شراء
                </th>
                @endif
                <th scope="col" class="py-2 px-6">
                    السعر
                </th>

                <th scope="col" class="py-2 px-6">
                    صوره
                </th>
            </tr>
            </thead>
            <tbody class="text-center">
@foreach($apps as $app)
    <tr class="border-solid border-2 bordwe-[#ebebeb] bg-white text-center">
        @if(auth()->check())
        <td class="py-4 px-6  bg-main text-center">

            <button wire:click="buy({{$app->id}})" class="font-medium text-white hover:underline cursor-pointer">شراء</button>


        </td>
        @endif
        <td class="py-4 px-6 text-center">
            <span >{{$app->pivot->price}}</span>
        </td>

        <td
            class="py-2 px-6 font-medium  text-center text-blue-50 whitespace-nowrap dark:text-blue-100">
            <img src="{{$app->getImage()}}" class="w-[50px] h-[50px] rounded-full  m-auto "
                 alt="">
        </td>
    </tr>
@endforeach




            </tbody>
        </table>
    </div>
</section>
