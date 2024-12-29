<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="submit">

                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="">لوغو الموقع</label>
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

                                <img src="{{$setting->getImage()}}" alt="" class="img-md">

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">اسم الموقع</label>
                        <input type="text" wire:model.lazy="name"
                               class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">بريد الموقع</label>
                        <input type="email" wire:model.lazy="email"
                               class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">واتسآب الموقع</label>
                        <input type="text" wire:model.lazy="phone"
                               class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">api Sim 90</label>
                        <input type="text" wire:model.lazy="api_sim90"
                               class="form-control @error('api_sim90') is-invalid @enderror">
                        @error('api_sim90')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">سعر الدولار بالتركي</label>
                        <input type="number" step="0.1" min="0.1" wire:model.lazy="usd_price"
                               class="form-control @error('usd_price') is-invalid @enderror">
                        @error('usd_price')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">الأخبار</label>
                        <textarea wire:model.lazy="news"
                                  class="form-control @error('news') is-invalid @enderror"></textarea>
                        @error('news')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="">نسبة الربح من Sim90</label>
                        <input type="number" wire:model="win_sim90_ratio" step="0.01" max="1" min="0"
                               class="form-control">
                        @error('win_sim90_ratio')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">نسبة الربح من Sim90</label>
                        <select wire:model="is_active_sim90"  class="form-control">
                            <option value="active">مفعل</option>
                            <option value="inactive">غير مفعل</option>
                        </select>
                        @error('is_active_sim90')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">نسبة حسم الأرقام لل Api</label>
                        <input type="text" wire:model.lazy="discount_online"
                               class="form-control @error('discount_online') is-invalid @enderror">
                        @error('discount_online')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">نسبة حسم الوكلاء من Online</label>
                        <input type="text" wire:model.lazy="discount_delegate_online"
                               class="form-control @error('discount_delegate_online') is-invalid @enderror">
                        @error('discount_delegate_online')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">نسبة ربح الوكلاء المخفيين</label>
                        <input type="number" step="0.001" wire:model.lazy="fixed_ratio"
                               class="form-control @error('fixed_ratio') is-invalid @enderror">
                        @error('fixed_ratio')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">تعليمات طلب الوكالة</label>
                        <textarea wire:model.lazy="info_present"
                                  class="form-control @error('info_present') is-invalid @enderror"></textarea>
                        @error('info_present')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">رقم موظف تفعيل الواتس</label>
                        <input type="text" wire:model.lazy="whats_activate"
                               class="form-control @error('whats_activate') is-invalid @enderror">
                        @error('whats_activate')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Api SpeedCard</label>
                        <input type="text" wire:model.lazy="apis.speed_card"
                               class="form-control @error('apis.speed_card') is-invalid @enderror">
                        @error('apis.speed_card')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Api EKO</label>
                        <input type="text" wire:model.lazy="apis.eko"
                               class="form-control @error('apis.eko') is-invalid @enderror">
                        @error('apis.eko')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <button class="btn btn-sm btn-success">حفظ</button>


                </form>
            </div>
        </div>
    </div>
</div>
