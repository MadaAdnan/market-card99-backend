
@push('css')
    <style>
        .switch input[type=checkbox] {
            height: 0;
            width: 0;
            visibility: hidden;
        }

        .switch label {
            cursor: pointer;
            width: 56px;
            height: 28px;
            background: lightgray;
            display: block;
            border-radius: 7px;
            position: relative;
        }

        .switch label:before {
            content: attr(data-off);
            position: absolute;
            top: 1.4px;
            right: 0;
            font-size: 8.4px;
            padding: 7px 7px;
            color: white;
        }

        .switch input:checked + label:before {
            content: attr(data-on);
            position: absolute;
            left: 0;
            font-size: 8.4px;
            padding-left: 7px;
            color: white;
        }

        .switch label:after {
            content: "";
            position: absolute;
            top: 1.4px;
            left: 1.4px;
            width: 25.2px;
            height: 25.2px;
            background: #fff;
            border-radius: 5.6px;
        }

        .switch input:checked + label {
            background: #007bff;
        }

        .switch input:checked + label:after {
            transform: translateX(28px);
        }


    </style>
@endpush
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="submit">
                    <div class="form-group">
                        <label for="">القسم</label>
                        <select wire:model="category_id" class="form-control">
                            <option value="">إختر القسم</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">اسم المنتج</label>
                        <input type="text" wire:model.lazy="name"
                               class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="">صورة المنتج</label>
                            <div

                                x-data="{ isUploading: false, progress: 0 }"

                                x-on:livewire-upload-start="isUploading = true"

                                x-on:livewire-upload-finish="isUploading = false"

                                x-on:livewire-upload-error="isUploading = false"

                                x-on:livewire-upload-progress="progress = $event.detail.progress">
                                <!-- File Input -->
                                <input type="file" wire:model="img" class="form-control-file">
                                <!-- Progress Bar -->

                                <div x-show="isUploading">

                                    <progress max="100" x-bind:value="progress"></progress>

                                </div>

                            </div>
                            @error('img')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                                <img src="{{$product->getImage()}}" alt="" class="img-md">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">وصف المنتج</label>
                        <textarea wire:model.lazy="info"
                                  class="form-control @error('info') is-invalid @enderror"></textarea>
                        @error('info')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">نوع العملة</label>
                        <select wire:model.lazy="currency" class="form-control @error('currency') is-invalid @enderror">
                            <option
                                value="{{\App\Enums\CurrencyEnum::USD->value}}">{{\App\Enums\CurrencyEnum::USD->status()}}</option>
                            <option
                                value="{{\App\Enums\CurrencyEnum::TR->value}}">{{\App\Enums\CurrencyEnum::TR->status()}}</option>

                        </select>
                        @error('currency')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">حر / محدد</label>
                        <select wire:model.lazy="is_free" class="form-control @error('is_free') is-invalid @enderror">
                            <option value="1">حر</option>
                            <option value="0">محدد</option>

                        </select>
                        @error('is_free')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    @if($is_free==1)
                        <div class="form-group">
                            <label for="">الكمية  </label>
                            <input type="number" step="0.01" wire:model="amount" class="form-control @error('amount') is-invalid @enderror">
                            @error('amount')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">أقل كمية للطلب  </label>
                            <input type="number" step="0.01" wire:model="min_amount" class="form-control @error('min_amount') is-invalid @enderror">
                            @error('min_amount')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">أقصى كمية للطلب  </label>
                            <input type="number" step="0.01" wire:model="max_amount" class="form-control @error('max_amount') is-invalid @enderror">
                            @error('max_amount')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="">نوع المنتج</label>
                        <select wire:model.lazy="type" class="form-control @error('type') is-invalid @enderror">
                            <option
                                value="{{\App\Enums\ProductTypeEnum::DEFAULT->value}}">{{\App\Enums\ProductTypeEnum::DEFAULT->status()}}</option>
                            <option
                                value="{{\App\Enums\ProductTypeEnum::NEED_ACCOUNT->value}}">{{\App\Enums\ProductTypeEnum::NEED_ACCOUNT->status()}}</option>
                            <option
                                value="{{\App\Enums\ProductTypeEnum::NEED_ID->value}}">{{\App\Enums\ProductTypeEnum::NEED_ID->status()}}</option>
                        </select>
                        @error('type')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for=""> سعر التكلفة </label>
                        <input type="number" step="0.01" wire:model="cost"
                               class="form-control @error('cost') is-invalid @enderror">
                        @error('cost')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">تفعيل / حظر</label>
                        <select wire:model.lazy="active" class="form-control @error('active') is-invalid @enderror">
                            <option value="1">مفعل</option>
                            <option value="0">غير مفعل</option>
                        </select>
                        @error('active')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">حالة الطلب من Api</label>
                        <select wire:model.lazy="is_active_api" class="form-control @error('is_active_api') is-invalid @enderror">
                            <option value="1">مفعل</option>
                            <option value="0">غير مفعل</option>

                        </select>
                        @error('is_free')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    @if($is_active_api)
                        <div class="form-group">
                            <label for="">كمية الطلب من api</label>
                            <input type="number" step="0.01" wire:model="count" class="form-control @error('count') is-invalid @enderror">
                            @error('count')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">حدد الموقع</label>
                            <select wire:model="api"  class="form-control">
                                <option value="">حدد الموقع المستخدم</option>
                                <option value="life-cash">لايف كاش</option>
                                <option value="speed-card">speed card</option>
                                <option value="eko">EKO</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">كود الطلب من api</label>
                            <input type="text"  wire:model="code" class="form-control @error('code') is-invalid @enderror">
                            @error('code')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">كود الطلب من SPEEDCARD</label>
                            <input type="text"  wire:model="codes_api.speed_card" class="form-control @error('code') is-invalid @enderror">
                            @error('code')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">كود الطلب من EKO</label>
                            <input type="text"  wire:model="codes_api.eko" class="form-control @error('code') is-invalid @enderror">
                            @error('code')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    @endif
                    <label for="">حالة العرض</label>
                     <div class="switch">
                        <input class="switch" id="switch" name="switch" type="checkbox"   wire:model="is_discount" /><label data-off="OFF" data-on="ON" for="switch"></label>
                    </div>

                    <label for="">البيع بسعر التكلفة</label>
                    <div class="switch">
                        <input class="switch" id="switch1" name="switch1" type="checkbox"   wire:model="is_offer" /><label data-off="OFF" data-on="ON" for="switch1"></label>
                    </div>
                    <button class="btn btn-sm btn-success">حفظ</button>


                </form>
            </div>
        </div>
    </div>
</div>
