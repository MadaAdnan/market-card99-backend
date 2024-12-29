<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card card-body">
            <div class="form-group form-row">
                @foreach($programs as $program)
                    <div class="col-4">
                        <label for="">التطبيق</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="programs_ids" value="{{$program->id}}" id="defaultCheck{{$program->id}}">
                            <label class="form-check-label" for="defaultCheck{{$program->id}}">
                                {{$program->name}} &nbsp;<img src="{{$program->getImage()}}" class="img-sm" alt="">
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <label >السعر</label>
                        <input type="text" wire:model.lazy="programs_price.{{$program->id}}" class="form-control">
                    </div>
                    <div class="col-4">
                        <label for="">كود التطبيق</label>
                        <input type="text" wire:model.lazy="programs_code.{{$program->id}}" class="form-control">
                    </div>
                @endforeach


            </div>
            <div class="form-group">
                <button class="btn btn-sm btn-success" wire:click="submit">حفظ</button>
            </div>
        </div>

    </div>
</div>
