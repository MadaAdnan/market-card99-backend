<section class=" ">
    <div class="w-full grid grid-cols-1  md:grid-cols-7 gap-3">
        <div class="col-span-2 border-[#D6D6D6] rounded shadow p-3">
            <h1 class="border-b border-success my-4">شراء الكوبونات</h1>
            <div class="grid grid-cols-1 gap-4 mb-5">
                @foreach($coupons as $coupon)
                    <div class="shadow rounded flex justify-between p-2 ">
                        <span> <span>سعر الكوبون : </span> <span>{{$coupon->price}} $</span></span>
                        <button class="rounded-full bg-secondary px-3 py-1 text-white"
                                wire:click="buy({{$coupon->id}})">شراء
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-span-5">
            <table class="table-auto w-full">
                <thead>
                <tr>
                    <th class="text-center border py-2">#</th>
                    <th class="text-center border py-2">الكود</th>
                    <th class="text-center border py-2">القيمة</th>
                    <th class="text-center border py-2">الحالة</th>
                </tr>
                </thead>
                <tbody>

                @foreach($couponsList as $item)
                    <tr>
                        <td class="text-center border py-2">{{$item->id}}</td>
                        <td class="text-center border py-2">{{$item->code}}</td>
                        <td class="text-center border py-2">{{$item->price}} $</td>
                        <td class="text-center border py-2 @if($item->status) text-success  @endif">@if($item->status) غير
                            مستخدم  @else  مستخدم  @endif </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="w-full">
        {{$couponsList->links()}}
    </div>
</section>
