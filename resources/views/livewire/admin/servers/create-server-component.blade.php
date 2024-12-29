<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="submit">
                    <div class="form-group">
                        <label for="">اسم السيرفر</label>
                        <input type="text" wire:model.lazy="name" class="form-control @error('name') is-invalid @enderror ">
                        @error('name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">الكود</label>
                        <select wire:model.lazy="code" class="form-control @error('code') is-invalid @enderror">
                            <option value="">إختر المكتبة</option>
                            @foreach($servers as $server)
                                <option value="{{$server}}">{{$server}}</option>
                            @endforeach
                        </select>
                        @error('code')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Api الربط</label>
                        <input type="text" read wire:model.lazy="api" class="form-control @error('api') is-invalid @enderror">
                        @error('api')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">إسم المستخدم</label>
                        <input type="text" read wire:model.lazy="username" class="form-control @error('username') is-invalid @enderror">
                        @error('username')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">كلمة المرور</label>
                        <input type="text" read wire:model.lazy="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="">صورة السيرفر</label>
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
                            @if($img!=null)
                                <img src="{{$img->temporaryUrl()}}" alt="" class="img-md">
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">الحالة</label>
                        <select  wire:model.lazy="is_active" class="form-control @error('is_active') is-invalid @enderror">
                            <option value="active">مفعل</option>
                            <option value="inactive">غير مفعل</option>
                        </select>
                        @error('is_active')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">اسم الشبكة</label>
                        <input type="text" wire:model.lazy="network" class="form-control @error('network') is-invalid @enderror ">
                        @error('network')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <button class="btn btn-sm btn-success">حفظ</button>


                </form>
            </div>
        </div>
    </div>
</div>
