<div class="flex flex-col" >
    <h4 class="text-red-400 text-center">سعر المنتج : {{number_format($product->getPrice(),2)}} $</h4>
    <h3  class=" text-gray-700 text-center">أدخل العدد</h3>
    <div class="items-center justify-center flex flex-column mb-6">

        <div class="flex flex-row mt-4">
            <button class="rounded py-1 text-[22px] px-4 bg-[#ebebeb]" wire:click="increase">+</button>
            <div class="rounded p-2 m-2 bg-gray-50">{{$count}}</div>
            <button class="rounded py-1 text-[22px] px-4 bg-[#ebebeb]" wire:click="decrease">-</button>
        </div>

    </div>
    @if($product->items()->where('items.active',1)->count())
        <button class="rounded bg-primary  text-white py-2" wire:click="submit">شراء</button>
    @else
        <span class="text-danger">غير متوفر في المخزون</span>
    @endif
</div>
