<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="submit">
                    <div class="form-group">
                        <label for=""> القسم الرئيسي</label>
                        <select wire:model.lazy="main_category" class="form-control @error('main_category') is-invalid @enderror">
                            <option value="">قسم رئيسي</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach


                        </select>
                        @error('main_category')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">اسم القسم</label>
                        <input type="text" wire:model.lazy="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="">صورة القسم</label>
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
                        <label for="">وصف القسم</label>
                        <textarea wire:model.lazy="info" class="form-control @error('info') is-invalid @enderror"></textarea>
                        @error('info')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">نوع القسم</label>
                        <select wire:model.lazy="type" class="form-control @error('type') is-invalid @enderror">
                            <option value="{{\App\Enums\CategoryTypeEnum::SIM90->value}}">{{\App\Enums\CategoryTypeEnum::SIM90->status()}}</option>
                            <option value="{{\App\Enums\CategoryTypeEnum::ONLINE->value}}">{{\App\Enums\CategoryTypeEnum::ONLINE->status()}}</option>
                            <option value="{{\App\Enums\CategoryTypeEnum::DEFAULT->value}}">{{\App\Enums\CategoryTypeEnum::DEFAULT->status()}}</option>
                        </select>
                        @error('type')
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


                    <button class="btn btn-sm btn-success">حفظ</button>


                </form>
            </div>
        </div>
    </div>
</div>
