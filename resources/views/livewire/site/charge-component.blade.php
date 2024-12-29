<div>
<div class="grid grid-cols-2 md:grid-cols-3 gap-2">
    <div class="col-span-1 cursor-pointer">
        <div
            class="rounded border-[1px] border-primary my-2 p-2 hover:bg-secondary hover:text-white @if($is_coupon) bg-secondary text-white @endif"
            wire:click="IsCoupon">
            <div class="flex justify-content-around items-center text-[12px]">

                <img class="w-[32px] h-[38px] rounded" src="{{asset('dist/img/charge.jpg')}}" alt="GETWAY">

                <span class="mx-2 text-[12px]">شحن عن طريق الكود</span>
            </div>

        </div>
    </div>


    @foreach($banks as $b)
        <div class="col-span-1 cursor-pointer">
            <div
                class="rounded border-[1px] border-primary my-2 p-2 hover:bg-secondary hover:text-white @if($b->id==$bank?->id) bg-secondary text-white @endif"
                wire:click="changeBank({{$b}})">
                <div class="flex justify-content-around items-center text-[12px]">

                    <img class="w-[32px] h-[38px] rounded" src="{{$b->getImage()}}" alt="GETWAY">

                    <span class="mx-2 text-[12px]">{{$b->name}}</span>
                </div>
            </div>
        </div>
    @endforeach

</div>


{{--<div class="col-span-1">
    <ul class="">
        <li class="rounded border-[1px] border-primary my-2 p-2 hover:bg-secondary hover:text-white @if($is_coupon) bg-secondary text-white @endif"
            wire:click="IsCoupon">
            <div class="flex justify-content-around items-center">

                <img class="w-[100px]" src="{{asset('dist/img/charge.jpg')}}" alt="GETWAY">

                <span class="mx-2">شحن عن طريق الكود</span>
            </div>

        </li>
        @foreach($banks as $b)
            <li class="rounded border-[1px] border-primary my-2 p-2 hover:bg-secondary hover:text-white @if($b->id==$bank?->id) bg-secondary text-white @endif"
                wire:click="changeBank({{$b}})">
                <div class="flex justify-content-around items-center">

                    <img class="w-[100px]" src="{{$b->getImage()}}" alt="GETWAY">

                    <span class="mx-2">{{$b->name}}</span>
                </div>
            </li>
        @endforeach
    </ul>
</div>--}}
    {{--fdsdfd--}}
@if($bank!=null)
    <div class="col-span-1 border border-2 rounded p-3 border-dashed mt-2">
      @if($bank->iban!=null)
            <div class="flex flex-col">
                <span>رقم الحساب :</span>

                <span class="font-bold mt-2 text-[#F9AC44] border border-1 px-2 py-1">{{$bank->iban}}</span>
            </div>
      @endif


        <div class="col-span-1 mt-2">
            <span class="font-bold p-1 mb-2">شرح طريقة التحويل :</span>
            <p class="text-justify p-2">{{$bank->info}}</p></div>
    </div>

        <div class="col-span-1 md:col-span-2 p-2 border-[1px] border-[#D6D6D6] mt-2 rounded @if($is_coupon) hidden  @endif">
            <h2 class="text-center font-bold text-[#F9AC44]"> معلومات الحوالة</h2>
            <div>
                <input type="hidden" wire:model="bank_id">
                @error('bank_id')
                <span class="text-[#FF0000]">يرجى تحديد طريقة الدفع من القائمة</span>
                @enderror
            </div>
            <div class="grid grid-cols-1">

                <div>

                 <div

                        x-data="{ isUploading: false, progress: 0 }"

                        x-on:livewire-upload-start="isUploading = true"

                        x-on:livewire-upload-finish="isUploading = false"

                        x-on:livewire-upload-error="isUploading = false"

                        x-on:livewire-upload-progress="progress = $event.detail.progress">
                        <!-- File Input -->
                       <div class="border border-[2px] border-[#D6D6D6] border-dashed w-1/2 m-auto mb-2">
                           <label for="Upload" class="text-center py-1 px-2">

                               <svg  xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#f9ac44" class="bi bi-card-image cursor-pointer d-inline-block m-auto" viewBox="0 0 16 16">
                                   <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                   <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5z"/>
                               </svg>

                               <input type="file" id="Upload" wire:model="img" class="hidden" style="background: transparent">
                               @error('img')
                               <span class="text-[#FF0000]">يرجى إرفاق صورة الإشعار</span>
                               @enderror
                           </label>
                       </div>
                        <!-- Progress Bar -->

                        <div x-show="isUploading">
                            <span class="w-1/2 m-auto block h-[10px] relative border border-[2px] rounded border-[#D6D6D6]">
                                <span class=" block absolute top-0 left-0 bg-red-700 rounded h-[10px]" x-bind:style="'width:'+progress+'%'" ></span>
                            </span>
{{--                            <progress max="100" wire:loading.class="bg-secondary" x-bind:data-value="progress" x-bind:value="progress"></progress>--}}

                        </div>

                    </div>

                </div>
                <div class="text-center flex align-items-center">
                    @if($img)
                        <img class="w-1/2 object-cover max-h-[260px] md:w-1/3 block m-auto rounded " src="{{$img->temporaryUrl()}}" alt="">
                    @endif
                </div>
            </div>
            <div class="my-1">
                <input type="number" wire:model="value" class="rounded w-full" placeholder="قيمة الحوالة">
                @error('value')
                <span class="text-[#FF0000]">يرجى إدخال قيمة الحوالة</span>
                @enderror
            </div>
            <div class="my-1">
                <textarea wire:model="info" class="rounded w-full" placeholder="ملاحظات"></textarea>
                @error('info')
                <span class="text-[#FF0000]">يرجى إضافة ملاحظاتك</span>
                @enderror
            </div>
            <button class="rounded bg-secondary text-white px-3 py-1" wire:click="submit">إرسال</button>

        </div>
    @else
        <div class="col-span-1 md:col-span-2 p-2 border-[1px] border-[#D6D6D6] rounded @if(!$is_coupon) hidden  @endif">
            <h2 class="text-center font-bold text-[#F9AC44]">أدخل الكوبون</h2>
            <div class="my-1">
                <input type="text" wire:model="code" class="rounded w-full" placeholder="أدخل الكوبون">

                @error('code')
                <span class="text-[#FF0000]">يرجى إدخال الكود</span>
                @enderror
            </div>
            <button class="rounded bg-secondary text-white px-3 py-1" wire:click="chargeCode">إرسال</button>

        </div>

    @endif
{{--rwerwe--}}
{{--rww--}}
{{--<div class="grid grid-cols-1">

@if(!$is_coupon)
    <div class="col-span-1  p-2 border-[1px] border-[#D6D6D6] rounded @if($is_coupon) hidden  @endif">
        @if($bank)
            <ul>
                <li><span>رقم الحساب :</span><span class="font-bold text-[#F9AC44]">{{$bank->iban}}</span></li>
                <li><span>شرح طريقة التحويل :</span>
                    <p class="">{{$bank->info}}</p></li>
            </ul>

        @endif
    </div>

    <div class="col-span-1 md:col-span-2 p-2 border-[1px] border-[#D6D6D6] rounded @if($is_coupon) hidden  @endif">
        <h2 class="text-center font-bold text-[#F9AC44]"> معلومات الحوالة</h2>
        <div>
            <input type="hidden" wire:model="bank_id">
            @error('bank_id')
            <span class="text-[#FF0000]">يرجى تحديد طريقة الدفع من القائمة</span>
            @enderror
        </div>
        <div class="grid grid-cols-2">

            <div>

                <div

                    x-data="{ isUploading: false, progress: 0 }"

                    x-on:livewire-upload-start="isUploading = true"

                    x-on:livewire-upload-finish="isUploading = false"

                    x-on:livewire-upload-error="isUploading = false"

                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <!-- File Input -->
                    <label for="Upload" class="rounded bg-secondary py-1 px-2 text-white">
                        صورة الإشعار
                        <input type="file" id="Upload" wire:model="img" class="hidden" style="background: transparent">
                        @error('img')
                        <span class="text-[#FF0000]">يرجى إرفاق صورة الإشعار</span>
                        @enderror
                    </label>
                    <!-- Progress Bar -->

                    <div x-show="isUploading">

                        <progress max="100" wire:loading.class="bg-secondary" x-bind:value="progress"></progress>

                    </div>

                </div>
            </div>
            <div class="text-center flex align-items-center">
                @if($img)
                    <img class="max-w-[100px] max-h-[100px] text-center" src="{{$img->temporaryUrl()}}" alt="">
                @endif
            </div>
        </div>
        <div class="my-1">
            <input type="number" wire:model="value" class="rounded w-full" placeholder="قيمة الحوالة">
            @error('value')
            <span class="text-[#FF0000]">يرجى إدخال قيمة الحوالة</span>
            @enderror
        </div>
        <div class="my-1">
            <textarea wire:model="info" class="rounded w-full" placeholder="ملاحظات"></textarea>
            @error('info')
            <span class="text-[#FF0000]">يرجى إضافة ملاحظاتك</span>
            @enderror
        </div>
        <button class="rounded bg-secondary text-white px-3 py-1" wire:click="submit">إرسال</button>

    </div>

@else

    <div class="col-span-1 md:col-span-2 p-2 border-[1px] border-[#D6D6D6] rounded @if(!$is_coupon) hidden  @endif">
        <h2 class="text-center font-bold text-[#F9AC44]">أدخل الكوبون</h2>
        <div class="my-1">
            <input type="text" wire:model="code" class="rounded w-full" placeholder="أدخل الكوبون">

            @error('code')
            <span class="text-[#FF0000]">يرجى إدخال الكود</span>
            @enderror
        </div>
        <button class="rounded bg-secondary text-white px-3 py-1" wire:click="chargeCode">إرسال</button>

    </div>

    @endif
    </div>--}}
</div>
