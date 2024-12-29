<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="submit">
                    @hasrole('super_admin')
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" wire:model="type_proccess" id="inlineRadio1" value="push">
                            <label class="form-check-label" for="inlineRadio1">شحن</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" wire:model="type_proccess" id="inlineRadio2" value="pull">
                            <label class="form-check-label" for="inlineRadio2">سحب</label>
                        </div>

                    </div>
                    @endhasrole
                  <div class="form-group">
                         <label for="">القيمة</label>
                         <input type="text" wire:model.lazy="amount" class="form-control @error('amount') is-invalid @enderror">
                         @error('amount')
                         <span class="text-danger">{{$message}}</span>
                         @enderror
                     </div>

                    <div class="form-group">
                        <label for="">ملاحظات</label>
                        <input type="text" wire:model.lazy="info" class="form-control @error('info') is-invalid @enderror">
                        @error('info')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>


                    <button class="btn btn-sm btn-success">حفظ</button>


                </form>
            </div>
        </div>
    </div>
</div>
