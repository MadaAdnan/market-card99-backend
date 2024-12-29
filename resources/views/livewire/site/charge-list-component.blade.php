    <div class="flex flex-col w-[100%]">
    <div class="overflow-x-auto w-full sm:-mx-6 lg:-mx-8">
        <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
            <div class="overflow-hidden w-full">
                        <table class="w-full text-center">
                            <thead>
                            <tr>
                                <th class="text-sm  bg-secondary text-white   px-6 py-4">#</th>
                                <th class="text-sm  bg-secondary text-white   px-6 py-4">طريقة التحويل</th>
                                <th class="text-sm  bg-secondary text-white   px-6 py-4">القيمة</th>
                                <th class="text-sm  bg-secondary text-white   px-6 py-4">الحالة</th>
                                <th class="text-sm  bg-secondary text-white   px-6 py-4">التاريخ</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">{{$order->id}}</td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">{{$order->bank->name}}</td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">{{$order->value}}</td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">{{\App\Enums\BillStatusEnum::tryFrom($order->status)?->status()}}</td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">{{$order->created_at->diffForHumans()}}</td>

                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                {{$orders->links()}}
            </div>
        </div>
    </div>
</div>
