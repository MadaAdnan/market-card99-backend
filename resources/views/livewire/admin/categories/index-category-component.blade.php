@push('css')
    <style>
        .switch input[type=checkbox] {
            height: 0;
            width: 0;
            visibility: hidden;
        }

        .switch label {
            cursor: pointer;
            width: 56px;
            height: 28px;
            background: lightgray;
            display: block;
            border-radius: 7px;
            position: relative;
        }

        .switch label:before {
            content: attr(data-off);
            position: absolute;
            top: 1.4px;
            right: 0;
            font-size: 8.4px;
            padding: 7px 7px;
            color: white;
        }

        .switch input:checked + label:before {
            content: attr(data-on);
            position: absolute;
            left: 0;
            font-size: 8.4px;
            padding-left: 7px;
            color: white;
        }

        .switch label:after {
            content: "";
            position: absolute;
            top: 1.4px;
            left: 1.4px;
            width: 25.2px;
            height: 25.2px;
            background: #fff;
            border-radius: 5.6px;
        }

        .switch input:checked + label {
            background: #007bff;
        }

        .switch input:checked + label:after {
            transform: translateX(28px);
        }


    </style>
@endpush



<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                      <input type="text" wire:model.debounce.500ms="search" class="form-control-sm mb-2" placeholder="بحث">
                    <span></span>
                    <div class="btn-group">
                        <a href="{{route('dashboard.categories.create')}}" class="btn btn-sm btn-info">إضافة قسم</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>

                            <th>اسم القسم</th>
                            <th>نوع القسم</th>
                            <th>صورة القسم</th>
                            <th>الحالة</th>

                            <th> القسم الرئيسي</th>
                            <th>التوفر</th>
                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$category->name}}</td>
                                <td>{{$category->type->status()}}</td>
                                <td><img src="{{$category->getImage()}}" alt="" class="img-md rounded"></td>
                                <td>
                                    @if($category->active)
                                        مفعل
                                    @else
                                        محظور
                                    @endif
                                </td>
                                <td>{{$category->category?->name}}</td>
                                <td>
                                    <div class="switch">
                                        <input class="switch" id="switch.{{$category->id}}" name="switch" type="checkbox" @if($category->is_available) checked="checked" @endif wire:change="toggle({{$category->id}})" /><label data-off="OFF" data-on="ON" for="switch.{{$category->id}}"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('dashboard.categories.edit',$category)}}" class="btn btn-sm btn-primary">تعديل</a>
                                        <a href="{{route('dashboard.categories.show',$category)}}" class="btn btn-sm btn-info">ترتيب المنتجات</a>
                                        <button class="btn btn-sm btn-danger" wire:click="delete({{$category->id}})">حذف</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="card-footer">
                {{$categories->links('dashboard.layouts.bootstrap')}}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <h3>ترتيب الأقسام</h3>
        <livewire:admin.categories.sortable-category-component/>
    </div>
</div>
