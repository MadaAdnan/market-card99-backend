@push('css')
    <style>
        .lds-dual-ring {
            display: inline-block;
            width: 60px;
            height: 60px;
        }
        .lds-dual-ring:after {
            content: " ";
            display: block;
            width: 44px;
            height: 44px;
            margin: 8px;
            border-radius: 50%;
            border: 6px solid #f50a0a;
            border-color: #00934f transparent #fff transparent;
            animation: lds-dual-ring 1.2s linear infinite;
        }
        @keyframes lds-dual-ring {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }


    </style>
@endpush
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="submit">
                    <div class="form-group">
                        <label for="">صورة</label>
                        <input type="file" wire:model="img" class="form-control @error('img') is-invalid @enderror">
                        <div wire:loading.class="d-inline-block" class="lds-dual-ring d-none" wire:target="img"></div>
                        @error('img')
                        <span  class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">اسم طريقة الدفع</label>
                        <input type="text" wire:model.lazy="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">رقم المحفظة / رقم الحساب </label>
                        <input type="text" wire:model.lazy="iban" class="form-control @error('iban') is-invalid @enderror">
                        @error('iban')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">شرح </label>
                        <textarea type="text" wire:model.lazy="info" class="form-control @error('info') is-invalid @enderror"></textarea>
                        @error('info')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">الحالة</label>
                        <select wire:model="is_active" class="form-control">
                            <option value="1">مفعل</option>
                            <option value="0">غير مفعل</option>
                        </select>
                    </div>

                    <button class="btn btn-sm btn-success">حفظ</button>


                </form>
            </div>
        </div>
    </div>
</div>
