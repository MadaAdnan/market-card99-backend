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
                            <th>رقم الهاتف</th>
                            <th>الكود</th>
                            <th>الحالة</th>
                            <th>السعر</th>
                            <th>الدولة</th>
                            <th>التطبيق</th>
                            <th>السيرفر</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{$loop->iteration}}</td>

                                <td dir="ltr">{{$order->phone}}</td>
                                <td>{{$order->code}}</td>
                                <td>{{$order->status->status()}}</td>
                                <td>{{$order->price}}</td>
                                <td>{{$order->country->name}}</td>
                                <td>{{$order->program->name}}</td>
                                <td>{{$order->server->name}}</td>
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
