<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control-sm mb-2" placeholder="بحث">
                    <div class="btn-group">
                        <a href="{{route('dashboard.coupons.create')}}" class="btn btn-sm btn-info">إضافة كوبونات</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>رقم الكوبون</th>
                            <th>حالة الكوبون</th>
                            <th>استخدمه</th>
                            <th>السعر</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($coupons as $coupon)
                            <tr>
                                <td>{{$coupon->id}}</td>
                                <td>{{$coupon->code}}</td>
                                <td>
                                    @if($coupon->status)
                                        <span class="text-success">غير مستخدم</span>
                                    @else
                                        <span class="text-danger"> مستخدم</span>
                                    @endif
                                </td>
                                <td>{{$coupon->user?->name}}</td>
                                <td>{{$coupon->price}}</td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="card-footer">
                {{$coupons->links('dashboard.layouts.bootstrap')}}
            </div>
        </div>
    </div>
</div>
