<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="submit">
                    <div class="form-group">
                        <label for="">الاسم</label>
                        <input type="text" wire:model.lazy="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">البريد الإلكتروني</label>
                        <input type="text" wire:model.lazy="email" class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">اسم المستخدم</label>
                        <input type="text" wire:model.lazy="username" class="form-control @error('username') is-invalid @enderror">
                        @error('username')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">كلمة المرور</label>
                        <input type="text" wire:model.lazy="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">رقم الهاتف</label>
                        <input type="text" wire:model.lazy="phone" class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">العنوان</label>
                        <input type="text" wire:model.lazy="address" class="form-control @error('address') is-invalid @enderror">
                        @error('address')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    @if(!auth()->user()->hasRole('super_admin'))
                        <div class="form-group">
                            <label for="">نسبة الربح</label>
                            <input type="number" wire:model.lazy="ratio" step="0.01" class="form-control @error('ratio') is-invalid @enderror">
                            @error('ratio')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">نسبة الربح من Online</label>
                            <input type="number" wire:model.lazy="ratio_online" step="0.01" class="form-control @error('ratio_online') is-invalid @enderror">
                            @error('ratio_online')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    @endif
                    @hasrole('super_admin')
                    <div class="form-group">
                        <label for="">تفعيل / حظر</label>
                        <select wire:model.lazy="active" class="form-control @error('active') is-invalid @enderror">
                                <option value="1">مفعل</option>
                                <option value="0">غير مفعل</option>
                        </select>
                        @error('active')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">يتبع للوكيل</label>
                        <select wire:model.lazy="user_id" class="form-control @error('user_id') is-invalid @enderror">
                            <option value="">إختر وكيل</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach

                        </select>
                        @error('user_id')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">الفئة</label>
                        <select wire:model.lazy="group_id" class="form-control @error('group_id') is-invalid @enderror">
                            <option value="">حدد فئة الزبون</option>
                            @foreach($groups as $group)
                                <option value="{{$group->id}}">{{$group->name}}</option>
                            @endforeach

                        </select>
                        @error('group_id')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">الطلاحيات</label>
                        @foreach($roles as $role)
                            <div class="form-check  form-check-inline">
                                <input class="form-check-input" wire:model="roles_id" type="checkbox" value="{{$role->id}}" id="defaultCheck{{$role->id}}">
                                <label class="form-check-label" for="defaultCheck{{$role->id}}">
                                    {{$role->display_name}}
                                </label>
                            </div>

                        @endforeach



                    </div>
                    <div class="form-group">
                        <label for="">نوع الوكيل</label>
                        <select wire:model.lazy="is_show" class="form-control @error('is_show') is-invalid @enderror">
                            <option value="1">ظاهر</option>
                            <option value="0"> مخفي</option>
                        </select>
                        @error('is_show')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    @endhasrole
                    <button class="btn btn-sm btn-success">حفظ</button>


                </form>
            </div>
        </div>
    </div>
</div>
