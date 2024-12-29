<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control-sm mb-2" placeholder="بحث">
                    <div class="btn-group">
                        <a href="{{route('dashboard.sliders.create')}}" class="btn btn-sm btn-info">إضافة إعلان</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الصورة</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sliders as $slider)
                            <tr>
                                <td>{{$loop->iteration}}</td>

                                <td><img src="{{$slider->getImage()}}" alt="Not Found"  class="img-md"></td>

                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('dashboard.sliders.edit',$slider)}}" class="btn btn-sm btn-primary">تعديل</a>


                                        <button class="btn btn-sm btn-danger" wire:click="delete({{$slider->id}})">حذف</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="card-footer">
                {{$sliders->links('dashboard.layouts.bootstrap')}}
            </div>
        </div>
    </div>
</div>
