<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control-sm mb-2"
                           placeholder="بحث">
                    <span></span>
                    {{-- <div class="btn-group">
                         <a href="{{route('dashboard.categories.create')}}" class="btn btn-sm btn-info">إضافة قسم</a>
                     </div>--}}
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الزبون</th>
                            <th>اسم المنتج</th>
                            <th>cost</th>
                            <th> السعر</th>
                            <th>ID/Phone</th>
                            <th>Phone</th>
                            <th>اسم المستخدم</th>
                            <th>كلمة المرور</th>
                            <th>الكمية المطلوبة</th>
                            <th>ملاحظات الزبون</th>
                            <th>الحالة
                                <select wire:model="type" class="form-contro-sm">
                                    <option value="">فلتر الحالة</option>
                                    <option
                                        value="{{\App\Enums\BillStatusEnum::PENDING->value}}">{{\App\Enums\BillStatusEnum::PENDING->status()}}</option>
                                    <option
                                        value="{{\App\Enums\BillStatusEnum::REQUEST_CANCEL->value}}">{{\App\Enums\BillStatusEnum::REQUEST_CANCEL->status()}}</option>
                                    <option
                                        value="{{\App\Enums\BillStatusEnum::COMPLETE->value}}">{{\App\Enums\BillStatusEnum::COMPLETE->status()}}</option>
                                    <option
                                        value="{{\App\Enums\BillStatusEnum::CANCEL->value}}">{{\App\Enums\BillStatusEnum::CANCEL->status()}}</option>
                                </select>
                            </th>
                            <th>تاريخ الطلب</th>
                            <th>رقم الطلب في Api</th>
                            <th> Api</th>

                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bills as $bill)
                            <livewire:dashboard.bills.single-bil-component wire:key="{{$bill->id}}" :bill="$bill"/>
                        @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="card-footer">
                {{$bills->links('dashboard.layouts.bootstrap')}}
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        window.addEventListener('cancelBill', (e) => {

            Swal.fire({
                title: 'سبب إلغاء الطلب',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'تأكيد إلغاء الطلب',
                cancelButtonText: 'إغلاق',
                showLoaderOnConfirm: false,

            }).then((result) => {
                if (result.isConfirmed == true) {
                    window.livewire.emit('cancelBillComplete', {
                        status: e.detail.status,
                        id: e.detail.id,
                        note: result.value
                    })
                }

            })

        });
        window.addEventListener('completeBill',(e)=>{
            Swal.fire({
                title: 'تحذير',
                text: "هل أنت متأكد من تغيير حالة الطلب",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'تأكيد',
                cancelButtonText: 'إغلاق',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('statusBillIsComplete', {status: e.detail.status, id: e.detail.id})
                }
            })
        })
    </script>
@endpush
