<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control-sm mb-2" placeholder="بحث">
                    <div class="btn-group">
                        <a href="{{route('dashboard.programs.create')}}" class="btn btn-sm btn-info">إضافة تطبيق</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم التطبيق</th>
                            <th>الصورة</th>
                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($programs as $program)
                            <tr>
                                <td>{{$program->id}}</td>
                                <td>{{$program->name}}</td>
                                <td><img src="{{$program->getImage()}}" alt="{{$program->name}}"  class="img-sm"></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('dashboard.programs.edit',$program)}}" class="btn btn-sm btn-primary">تعديل</a>
                                        <button class="btn btn-sm btn-danger" wire:click="delete({{$program->id}})">حذف</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="card-footer">
                {{$programs->links('dashboard.layouts.bootstrap')}}
            </div>
        </div>

    </div>

</div>
