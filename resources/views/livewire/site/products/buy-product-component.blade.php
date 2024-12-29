<div>

    @if($product->type==\App\Enums\ProductTypeEnum::NEED_ID)
        <div class="flex flex-col">
            <label for="exampleInputEmail1" class="text-gray-700 text-[12px]">
            @if($product->is_url)
                أدخل رابط الصفحة
                @else
                    ID الحساب
            @endif
            </label>
            <input type="text" class="rounded transition ease-in-out m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none @error('id')  is-invalid @enderror"
                   id="exampleInputEmail1"
                   aria-describedby="emailHelp" placeholder=" @if($product->is_url)
                أدخل رابط الصفحة
                @else
                ID الحساب
@endif " wire:model.lazy="id_user">
            @error('id_user')
            <small id="emailHelp" class="block mt-1 text-xs text-gray-600">{{$message}}</small>
            @enderror
        </div>
        <div class="flex flex-col">
            <label for="exampleInputPassword1"
                   class="text-gray-700 text-[12px] mt-2">اسم الحساب</label>
            <input type="text" wire:model.lazy="name" class="rounded transition ease-in-out m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none " id="exampleInputPassword1"
                   placeholder="أدخل اسم الحساب">
        </div>

    @elseif($product->type==\App\Enums\ProductTypeEnum::NEED_ACCOUNT)
        <div class="flex flex-col">
            <label for="exampleInputEmail1" class="text-[12px] mt-2 text-gray-700">البريد الإلكتروني</label>
            <input type="text" class="rounded transition ease-in-out m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none @error('id')  is-invalid @enderror"
                   id="exampleInputEmail1"
                   aria-describedby="emailHelp" placeholder="أدخل البريد الإلكتروني" wire:model.lazy="id_user">
            @error('id_user')
            <small id="emailHelp" class="block mt-1 text-xs text-gray-600">{{$message}}</small>
            @enderror
        </div>
        <div class="flex flex-col">
            <label for="exampleInputPassword1"
                   class="text-[12] mt-2 text-gray-700">كلمة المرور</label>
            <input type="text" wire:model="name" class="rounded transition ease-in-out m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleInputPassword1"
                   placeholder="أدخل  كلمة المرور">
        </div>
    @endif
    @if($product->is_free==0)
        <div class="flex flex-col">
            <label for="exampleInputPassword1"
                   class="mt-2 text-[12px] text-gray-700">ملاحظات</label>
            <input type="text" wire:model.lazy="info" class="rounded transition ease-in-out m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleInputPassword1"
                   placeholder="ملاحظات">
        </div>
        {{--
            <div class="flex justify-between w-1/2 m-auto">
                <button wire:click="increment" class="bg-[#4ee0de] rounded px-4 py-1 text-white"><i class="fa fa-plus"></i></button>
                <span>{{$count}}</span>

                <button wire:click="decrement" class="bg-[#4ee0de] rounded px-4 py-1 text-white"><i class="fa fa-minus"></i></button>
            </div>--}}
    @else
        <div class="form-group mb-6">
            <label for="" class="form-label inline-block mb-2 text-gray-700">الكمية المراد شراؤها</label>
            <input type="number" wire:model="amount" class="form-control block
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
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleInputPassword1"
                   placeholder="الكمية">
            @error('amount')
            <span class="text-red-400">{{$message}}</span>
            @enderror
        </div>
        <span class="block text-red-400 text-center">السعر : {{number_format($price,2)}}</span>
    @endif

    <button wire:loading.attr="disabled" type="button" wire:click.debounce.1000ms="submit" wire:loading.class="hidden" wire:target="submit" class="
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
        <span>شراء</span>

    </button>

    <div wire:loading wire:target="submit">

        جاري الطلب ...

    </div>


</div>
