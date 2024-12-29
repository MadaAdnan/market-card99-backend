<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card card-body">
            <div class="form-group form-row">
                @foreach($countries as $country)
                    <div class="col-6">
                        <label for="">الدولة</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="country_ids" value="{{$country->id}}" id="defaultCheck{{$country->id}}">
                            <label class="form-check-label" for="defaultCheck{{$country->id}}">
                                {{$country->name}} &nbsp;<img src="{{$country->getImage()}}" class="img-sm" alt="">
                            </label>
                        </div>
                    </div>

                    <div class="col-6">
                        <label for="">كود الدولة</label>
                        <input type="text" wire:model.lazy="country_code.{{$country->id}}" class="form-control">
                    </div>
                @endforeach


            </div>
            <div class="form-group">
                <button class="btn btn-sm btn-success" wire:click="submit">حفظ</button>
            </div>
        </div>

    </div>
</div>
