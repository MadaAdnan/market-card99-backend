<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control-sm mb-2" placeholder="بحث">
                    <div class="btn-group">
                        <a href="{{route('dashboard.asks.create')}}" class="btn btn-sm btn-info">إضافة سؤال</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>السؤال</th>
                            <th>الحالة</th>
                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($asks as $ask)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$ask->ask}}</td>
                                <td>{{$ask->is_active=='active'?'مفعل':'غير مفعل'}}</td>

                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('dashboard.asks.edit',$ask)}}" class="btn btn-sm btn-primary">تعديل</a>

                                         <button class="btn btn-sm btn-danger" wire:click="delete({{$ask->id}})">حذف</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="card-footer">
                {{$asks->links('dashboard.layouts.bootstrap')}}
            </div>
        </div>
    </div>

</div>
