<section>

    <div class="overflow-auto  m-4 mb-0">
        <table class="w-full text-center">
            <div class="mb-2 flex w-full h-[40px]   ">
                <input placeholder="ابحث  .... "
                       class="w-[100%]  md:w-[40%]  lg:w-[30%]  rounded-t-xl   outline-none p-2   " type="text"
                       wire:model.debounce.500ms="search" id="">
            </div>
            <thead>
            <tr>
                <th scope="col" class="text-sm  bg-secondary text-white   px-6 py-4">
                    رقم الطلب
                </th>
                <th scope="col" class="text-sm  bg-secondary text-white   px-6 py-4">
                    الرقم
                </th>
                <th scope="col" class="text-sm  bg-secondary text-white   px-6 py-4">
                    الكود
                </th>
                <th scope="col" class="text-sm  bg-secondary text-white   px-6 py-4">
                    السعر
                </th>
                <th scope="col" class="text-sm  bg-secondary text-white   px-6 py-4">
                    التطبيق
                </th>
                <th scope="col" class="text-sm  bg-secondary text-white   px-6 py-4">
                    الدوله
                </th>
                <th scope="col" class="text-smbg-main  bg-secondary text-white   px-6 py-4">
                    الحاله
                </th>
                <th scope="col" class="text-sm  bg-secondary text-white   px-6 py-4">
                    التاريخ
                </th>

            </tr>
            </thead>
            <tbody>
          @foreach($orders as $order)
             <livewire:site.single-order-component wire:key="{{$order->id}}" :order="$order"/>
          @endforeach





            </tbody>
        </table>
    </div>
    <div class="flex w-full justify-center items-center gap-2 m-0 ">
        {{$orders->links()}}
    </div>
</section>
