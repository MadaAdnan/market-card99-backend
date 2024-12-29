<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="submit">
                    <div class="form-group">
                        <label for="">السؤال</label>
                        <input type="text" wire:model.lazy="title" class="form-control @error('title') is-invalid @enderror ">
                        @error('title')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
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

                    <button class="btn btn-sm btn-success">حفظ</button>


                </form>
            </div>
        </div>
    </div>
</div>
