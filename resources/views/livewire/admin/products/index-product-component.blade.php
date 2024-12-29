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
                        <a href="{{route('dashboard.products.create')}}" class="btn btn-sm btn-info">إضافة منتج</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم المنتج</th>
                            <th>
                                <select wire:model="category_id" class="form-control-sm">
                                    <option value="">فلتر الأقسام</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>نوع المنتج</th>
                            <th>عملة  المنتج</th>
                            <th>صورة المنتج</th>
                            <th> سعر المنتج الكلفة</th>
                            <th> الحالة</th>
                            <th> الأكواد المتوفرة</th>
                            <th> التوفر </th>
                            <th> api </th>
                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$product->name}}</td>
                                <td>{{$product->category->name}}</td>
                                <td>{{$product->type->status()}}</td>
                                <td>{{$product->currency->status()}}</td>
                                <td><img src="{{$product->getImage()}}" alt="" class="img-md rounded"></td>
                                <td>{{$product->cost}}</td>
                                <td>
                                    @if($product->active)
                                        مفعل
                                    @else
                                        محظور
                                    @endif
                                </td>
                                <td>{{$product->items->count()}}</td>
                                <td>

                                    <div class="switch">
                                        <input class="switch" id="switch.{{$product->id}}" name="switch" type="checkbox" @if($product->is_available) checked="checked" @endif wire:change="toggle({{$product->id}})" /><label data-off="OFF" data-on="ON" for="switch.{{$product->id}}"></label>
                                    </div>

                                </td>
                                <td>{{$product->api}}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('dashboard.products.edit',$product)}}" class="btn btn-sm btn-primary">تعديل</a>
                                        <button class="btn btn-sm btn-danger" wire:click="delete({{$product->id}})">حذف</button>
                                        @if($product->type==\App\Enums\ProductTypeEnum::DEFAULT)
                                            <a href="{{route('dashboard.items.create',['product_id'=>$product->id])}}" class="btn btn-sm btn-primary">رفع أكواد</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="card-footer">
                {{$products->links('dashboard.layouts.bootstrap')}}
            </div>
        </div>
    </div>
</div>

