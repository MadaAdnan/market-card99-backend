<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="submit">

                    <div class="form-group">
                        <label for="">عدد الكوبونات</label>
                        <input type="number" step="1" min="1" wire:model.lazy="count" class="form-control @error('count') is-invalid @enderror">
                        @error('count')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">سعر الكوبون</label>
                        <input type="number" step="0.1"  wire:model.lazy="price" class="form-control @error('price') is-invalid @enderror">
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
