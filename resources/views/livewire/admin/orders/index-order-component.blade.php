<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control-sm mb-2" placeholder="بحث">
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>رقم الهاتف</th>
                            <th>الكود</th>
                            <th>الحالة</th>
                            <th>السعر</th>
                            <th>الدولة</th>
                            <th>التطبيق</th>
                            <th>السيرفر</th>
                            <th>إلغاء</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$order->user->name}}</td>
                                <td dir="ltr">{{$order->phone}}</td>
                                <td>{{$order->code}}</td>
                                <td>{{$order->status->status()}}</td>
                                <td>{{$order->price}}</td>
                                <td>{{$order->country?->name??$order->country_name}}</td>
                                <td>{{$order->program?->name??$order->program_name}}</td>
                                <td>{{$order->server?->name??'خارجي'}}</td>
                                <td>
                                    @if($order->created_at->lessThan(now()->subMinutes(5)) && $order->status->value==\App\Enums\OrderStatusEnum::WAITE->value)
                                    <button class="btn btn-sm btn-danger" wire:click="cancel({{$order->id}})">إلغاء</button>
                                        @endif
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
