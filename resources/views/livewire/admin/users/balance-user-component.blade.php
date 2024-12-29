<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h4>الحركة المالية</h4></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>مقبوض</th>
                            <th>مدفوع</th>
                            <th>الرصيد</th>
                            <th>التاريخ</th>
                            <th>الملاحظات</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($balances as $balance)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$balance->credit}}</td>
                                <td>{{$balance->debit}}</td>
                                <td>{{$balance->total}}</td>
                                <td>{{$balance->created_at->format('Y-m-d')}}</td>
                                <td>{{$balance->info}}</td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="card-footer">
                {{$balances->links('dashboard.layouts.bootstrap')}}
            </div>
        </div>
    </div>
</div>
