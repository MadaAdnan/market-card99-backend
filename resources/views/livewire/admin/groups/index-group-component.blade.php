<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
{{--                    <input type="text" wire:model.debounce.500ms="search" class="form-control-sm mb-2" placeholder="بحث">--}}
                    <span></span>
                    <div class="btn-group">
                        <a href="{{route('dashboard.groups.create')}}" class="btn btn-sm btn-info">إضافة مجموعة</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم المجموعة</th>
                            <th>عدد المستخدمين</th>
                            <th> نسبة الربح</th>
                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($groups as $group)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$group->name}}</td>
                                <td>{{$group->users->count()}}</td>
                                <td>{{$group->price*100}} %</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('dashboard.groups.edit',$group)}}" class="btn btn-sm btn-primary">تعديل</a>
                                        <button class="btn btn-sm btn-danger" wire:click="delete({{$group->id}})">حذف</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="card-footer">
                {{$groups->links('dashboard.layouts.bootstrap')}}
            </div>
        </div>
    </div>
</div>
