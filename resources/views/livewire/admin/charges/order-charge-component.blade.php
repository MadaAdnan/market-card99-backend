<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">

                    <span></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>صورة الحوالة</th>
                            <th>طريقة التحويل</th>
                            <th>المستخدم</th>
                            <th>الكمية</th>
                            <th>الحالة</th>
                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{$order->id}}</td>
                                <td><a href="{{$order->getImage()}}" target="_blank">
                                        <img src="{{$order->getImage()}}" class="img-md" alt="">
                                    </a> </td>
                                <td>{{$order->bank->name}} </td>
                                <td>{{$order->user->name}} </td>
                                <td>{{$order->value}} </td>
                                <td>{{\App\Enums\BillStatusEnum::tryFrom($order->status)?->status()}} </td>
                                <td>
                                    <div class="btn-group">
                                        @if($order->status==\App\Enums\BillStatusEnum::PENDING->value || $order->status=='')
                                            <button class="btn btn-sm btn-danger" wire:click="cancel({{$order->id}})">
                                                إلغاء
                                            </button>
                                            <button class="btn btn-sm btn-success"
                                                    wire:click="complete({{$order->id}})">تم الشحن
                                            </button>
                                        @endif

                                        {{--                                        <button class="btn btn-sm btn-danger" wire:click="delete({{$bank->id}})">حذف</button>--}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="card-footer">
                {{$orders->links('dashboard.layouts.bootstrap')}}
            </div>
        </div>
    </div>
</div>

