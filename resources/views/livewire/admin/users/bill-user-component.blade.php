<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control-sm mb-2" placeholder="بحث">
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
                            <th>اسم المنتج</th>
                            <th> السعر</th>
                            <th>ID/Phone</th>
                            <th>اسم المستخدم</th>
                            <th>كلمة المرور</th>
                            <th>ملاحظات الزبون</th>
                            <th>الحالة

                            </th>
                            <th>تاريخ الطلب</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bills as $bill)
                            <tr>
                                <td>{{$loop->iteration}}</td>

                                <td>{{$bill->product->name}}</td>
                                <td>{{$bill->price}}</td>
                                <td>{{$bill->customer_id}}</td>
                                <td>{{$bill->customer_name??$bill->customer_username}}</td>

                                <td>{{$bill->customer_password}}</td>
                                <td>{{$bill->customer_note}}</td>
                                <td
                                    class="text-white @if($bill->status==\App\Enums\BillStatusEnum::CANCEL) bg-black   @elseif($bill->status==\App\Enums\BillStatusEnum::COMPLETE) bg-success  @elseif($bill->status==\App\Enums\BillStatusEnum::PENDING) bg-info     @else  bg-danger   @endif"
                                >{{$bill->status->status()}}</td>
                                <td>{{$bill->created_at->format('Y-m-d h:i a')}}</td>
                            </tr>
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
        window.addEventListener('changeStatusBill',(e)=>{
            if(e.detail.status=='cancel'){
                Swal.fire({
                    title: 'سبب إلغاء الطلب',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'تأكيد إلغاء الطلب',
                    cancelButtonText:'إغلاق',
                    showLoaderOnConfirm: false,

                }).then((result) => {
                    if(result.isConfirmed==true){
                        window.livewire.emit('changeStatus',{status:e.detail.status,id:e.detail.id,note:result.value})
                    }

                })
            }else{
                Swal.fire({
                    title: 'تحذير',
                    text: "هل أنت متأكد من تغيير حالة الطلب",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'تأكيد',
                    cancelButtonText:'إغلاق',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit('changeStatus',{status:e.detail.status,id:e.detail.id})
                    }
                })

            }

        });

    </script>
@endpush
