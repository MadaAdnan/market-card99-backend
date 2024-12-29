<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control-sm mb-2" placeholder="بحث">
                    <div class="btn-group">
                        <a href="{{route('dashboard.users.create')}}" class="btn btn-sm btn-info">إضافة مستخدم</a>
                        <a href="{{route('dashboard.export.balance')}}" class="btn btn-sm btn-success">تصدير إلى Excel</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الهاتف</th>
                            <th>الرصيد</th>
                            <th>الفئة</th>
                            <th>الصلاحيات
                                <select wire:model="type" class="form-control-sm">
                                    <option value="">فلتر الصلاحيات</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->name}}">{{$role->display_name}}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>الأرباح</th>
                            <th>حالة الحساب</th>
                            <th>الحساب الاب</th>
                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->phone}}</td>
                                <td>{{$user->balance}}</td>
                                <td>{{$user->group->name}}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <kbd class="mx-1">{{$role->display_name}}</kbd>
                                    @endforeach
                                </td>
                                <td>{{$user->win}}</td>
                                <td>
                                    @if($user->active)
                                    مفعل
                                    @else
                                    محظور
                                    @endif
                                </td>
                                <td>{{$user->user?->name}}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('dashboard.balances.create',['user_id'=>$user])}}" class="btn btn-sm btn-success">إضافة رصيد</a>
                                        <a href="{{route('dashboard.users.edit',$user)}}" class="btn btn-sm btn-primary">تعديل</a>
                                        <a href="{{route('dashboard.user-bill.show',$user)}}" class="btn btn-sm btn-info">عرض</a>
                                        @hasrole('super_admin')
                                        <a href="{{route('dashboard.notifications.create',['user_id'=>$user->id])}}" class="btn btn-sm btn-warning">إشعار</a>

                                        <button class="btn btn-sm btn-danger" wire:click="delete({{$user->id}})">حذف</button>
                                        @endhasrole
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="card-footer">
                {{$users->links('dashboard.layouts.bootstrap')}}
            </div>
        </div>
    </div>
</div>
