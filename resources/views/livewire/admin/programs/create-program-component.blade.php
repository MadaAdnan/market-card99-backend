<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="submit">
                    <div class="form-group">
                        <label for="">اسم التطبيق</label>
                        <input type="text" wire:model.lazy="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group form-row">
                        <div class="col-md-6">
                            <label for="">صورة التطبيق</label>
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
                    <button class="btn btn-sm btn-success">حفظ</button>


                </form>
            </div>
        </div>
    </div>
</div>
