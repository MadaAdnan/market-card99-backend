<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
               <div class="d-flex justify-content-between align-items-center">
                   <input type="text" wire:model.debounce.500ms="search" class="form-control-sm mb-2" placeholder="بحث">
                 <div class="btn-group">
                     <a href="{{route('dashboard.servers.create')}}" class="btn btn-sm btn-info">إضافة سيرفر</a>
                 </div>
               </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم السيرفر</th>
                            <th>الصورة</th>
                            <th>المكتبة</th>
                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($servers as $server)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$server->name}}</td>
                                <td><img src="{{$server->getImage()}}" alt="{{$server->name}}"  class="img-sm"></td>
                                <td>{{$server->code}}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('dashboard.servers.edit',$server)}}" class="btn btn-sm btn-primary">تعديل</a>

                                        <a href="{{route('dashboard.relations_program.edit',$server)}}" class="btn btn-sm btn-secondary">ربط التطبيقات بالسيرفر</a>
                                        <a href="{{route('dashboard.relations_country.edit',$server)}}" class="btn btn-sm btn-default">ربط الدول بالسيرفر</a>
                                        <button class="btn btn-sm btn-danger" wire:click="delete({{$server->id}})">حذف</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="card-footer">
                {{$servers->links('dashboard.layouts.bootstrap')}}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <livewire:dashboard.servers.sortable-server/>
    </div>
</div>
