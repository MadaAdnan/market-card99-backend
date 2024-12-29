<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="submit">
                    <div class="form-group">
                        <label for="">اسم الفئة</label>
                        <input type="text" wire:model.lazy="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for=""> نسبة الربح</label>
                        <input type="number" step="0.001" wire:model.lazy="price" class="form-control @error('price') is-invalid @enderror">
                        @error('price')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>


                    <button class="btn btn-sm btn-success">حفظ</button>


                </form>
            </div>
        </div>
    </div>
</div>
