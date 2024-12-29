<div class="row">
  <div class="col-md-12">
      <div class="card">
          <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                  <input type="text" wire:model.debounce.500ms="search" class="form-control-sm mb-2" placeholder="بحث">
                  <div class="btn-group">
                      <a href="{{route('dashboard.countries.create')}}" class="btn btn-sm btn-info">إضافة دولة</a>
                  </div>
              </div>
              <div class="table-responsive">
                  <table class="table table-striped table-sm table-hover">
                      <thead>
                      <tr>
                          <th>#</th>
                          <th>اسم الدولة</th>
                          <th>الصورة</th>
                          <th>التحكم</th>
                      </tr>
                      </thead>
                      <tbody>
                      @foreach($countries as $country)
                          <tr>
                              <td>{{$loop->iteration}}</td>
                              <td>{{$country->name}}</td>
                              <td><img src="{{$country->getImage()}}" alt="{{$country->name}}"  class="img-sm"></td>
                              <td>
                                  <div class="btn-group">
                                      <a href="{{route('dashboard.countries.edit',$country)}}" class="btn btn-sm btn-primary">تعديل</a>
                                      <button class="btn btn-sm btn-danger" wire:click="delete({{$country->id}})">حذف</button>
                                  </div>
                              </td>
                          </tr>
                      @endforeach

                      </tbody>
                  </table>

              </div>

          </div>
          <div class="card-footer">
              {{$countries->links('dashboard.layouts.bootstrap')}}
          </div>
      </div>
  </div>
</div>
