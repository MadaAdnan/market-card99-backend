<div class="col-md-12">
    <div>
        <div class="form-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio"wire:model="type" id="inlineRadio1"
                       value="hand">
                <label class="form-check-label" for="inlineRadio1">رفع يدوي</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" wire:model="type" id="inlineRadio2"
                       value="ex">
                <label class="form-check-label" for="inlineRadio2">رفع من إكسل</label>
            </div>
        </div>
    </div>
    @if($type=='ex')
        <div class="">
<div class="form-group">
    <label for="FileUplaod">حدد ملف إكسل</label>
    <input type="file" name="" id="" class="form-control-file" wire:model="file">
</div>
            <button class="btn btn-sm btn-success" wire:click="import">حفظ</button>
        </div>


    @else

        <div class="">
            <div class="form-group form-row">

                <div class="col-md-6">
                    <label for="">الكود</label>
                    <input type="text" class="form-control" wire:model="code1">
                </div>
                <div class="col-md-6">
                    <label for="">الكود</label>
                    <input type="text" class="form-control" wire:model="code2">
                </div>
                <div class="col-md-6">
                    <label for="">الكود</label>
                    <input type="text" class="form-control" wire:model="code3">
                </div>
                <div class="col-md-6">
                    <label for="">الكود</label>
                    <input type="text" class="form-control" wire:model="code4">
                </div>
                <div class="col-md-6">
                    <label for="">الكود</label>
                    <input type="text" class="form-control" wire:model="code5">
                </div>
                <div class="col-md-6">
                    <label for="">الكود</label>
                    <input type="text" class="form-control" wire:model="code6">

                </div>
                <div class="col-md-6">
                    <label for="">الكود</label>
                    <input type="text" class="form-control" wire:model="code7">
                </div>
                <div class="col-md-6">
                    <label for="">الكود</label>
                    <input type="text" class="form-control" wire:model="code8">
                </div>
                <div class="col-md-6">
                    <label for="">الكود</label>
                    <input type="text" class="form-control" wire:model="code9">
                </div>
                <div class="col-md-6">
                    <label for="">الكود</label>
                    <input type="text" class="form-control" wire:model="code10">
                </div>
            </div>




            <button class="btn btn-sm btn-info" wire:click="submit">حفظ</button>
        </div>
    @endif

</div>
