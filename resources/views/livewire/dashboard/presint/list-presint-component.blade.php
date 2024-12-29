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
                    <div class="btn-group">
                        <a href="{{route('dashboard.asks.create')}}" class="btn btn-sm btn-info">إضافة سؤال</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>المستخدم</th>
                            <th>الحالة</th>
                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($presints as $presint)
                            <tr>
                                <td>{{$loop->iteration}}</td>

                                <td>{{$presint->user->name}}</td>
                                <td>
                                    <div class="switch">
                                        <input class="switch" id="switch.{{$presint->id}}" name="switch" type="checkbox" @if($presint->status) checked="checked" @endif wire:change="toggle({{$presint->id}})" /><label data-off="OFF" data-on="ON" for="switch.{{$presint->id}}"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('dashboard.presints.show',$presint)}}" class="btn btn-sm btn-primary">عرض</a>

                                        <button class="btn btn-sm btn-danger" wire:click="delete({{$presint->id}})">حذف</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="card-footer">
                {{$presints->links('dashboard.layouts.bootstrap')}}
            </div>
        </div>
    </div>

</div>
