<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                جميع الأكواد المتاحة
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الكود</th>

                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->code}}</td>

                                <td>
                                    <div class="btn-group">

                                        <button class="btn btn-sm btn-danger" wire:click="delete({{$item->id}})">حذف</button>

                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="card-footer">
                {{$items->links('dashboard.layouts.bootstrap')}}
            </div>
        </div>
    </div>
</div>
