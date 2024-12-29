<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">

                    <span></span>
                     <div class="btn-group">
                         <a href="{{route('dashboard.banks.create')}}" class="btn btn-sm btn-info">إضافة طريقة دفع</a>
                     </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>طريقة التحويل</th>

                            <th>الحالة</th>
                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($banks as $bank)
                            <tr>
                                <td>{{$bank->id}}</td>
                                <td>{{$bank->name}} </td>

                                <td>
                                    @if($bank->is_active)
                                        مفعل
                                    @else
                                        غير مفعل
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('dashboard.banks.edit',$bank)}}" class="btn btn-sm btn-primary">تعديل</a>
                                        <a href="{{route('dashboard.banks.show',$bank)}}" class="btn btn-sm btn-info">عرض</a>
                                        <button class="btn btn-sm btn-danger" wire:click="delete({{$bank->id}})">حذف</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="card-footer">
                {{$banks->links('dashboard.layouts.bootstrap')}}
            </div>
        </div>
    </div>
</div>

