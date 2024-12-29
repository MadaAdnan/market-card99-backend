<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <p>إجمالي المبيعات في الفترة ما بين </p>
                    <div class="d-flex justify-content-center ">
                        <p class="mx-2">
                            <label for="">من تاريخ</label>
                            <input type="date" wire:model="from" class="form-control">
                        </p>
                        <p class="mx-2">
                            <label for="">إلى تاريخ</label>
                            <input type="date" wire:model="to" class="form-control">
                        </p>
                    </div>

                </div>
                <div class="col-md-12">
                    <div class="card card-body">
                        <h3 class="text-center">إجمالي المبيعات خلال الفترة المحددة</h3>
                        <div class="bold text-danger text-center bold size-3 d-flex justify-content-between">
                            <span class="border border-[1px] p-2">  منتجات:  $ {{number_format($orders,2)}}</span>
                            <span class="border border-[1px] p-2">  أونلاين:  $ {{number_format($online,2)}}</span>
                            <span class=" d-flex flex-column">  <span>Api:</span>
                            <span class="my-1 border border-[1px] p-2 ">Life-Cach : $ {{number_format($orders_api,2)}}</span>
                            <span class="my-1 border border-[1px] p-2">Speed-Card : $ {{number_format($orders_speed,2)}}</span>
                            <span class="my-1 border border-[1px] p-2">Eko : $ {{number_format($orders_eko,2)}}</span>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="gorm-group">
                            <label for="">من تاريخ</label>
                            <input type="date" wire:model="balance_from" class="form-control"></div>
                        <div class="gorm-group">
                            <label for="">إلى تاريخ</label>
                            <input type="date" wire:model="balance_date" class="form-control"></div>
                    </div>
                </div>
                <div class=" card-body">
                    <table class="table">
                        <thead>
                        <th>البريد</th>
                        <th>الشحن</th>
                        <th>السحب</th>
                        <th>منذ</th>
                        </thead>
                        <tbody>
                        @foreach($balances as $balance)
                            <tr>
                                <td>{{$balance->user->name}}</td>
                                <td>{{$balance->credit}}</td>
                                <td>{{$balance->debit}}</td>
                                <td>{{$balance->created_at->diffForHumans()}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    المجموع : {{$balances->sum('credit')- $balances->sum('debit')}}
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12" wire:ignore>
            <div class="card">
                <div class="card-header no-border">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">الأرباح اليومية بين تاريخ <span class="text-success">{{now()->startOfMonth()->format('Y-m-d')}}</span> -
                            <span class="text-danger">{{now()->endOfMonth()->format('Y-m-d')}}</span></h3>
                        <a href="javascript:void(0);">إحصائيات أرباح المبيعات من المنتجات</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            {{--                        <span class="text-bold text-lg">820</span>--}}
                            {{--                        <span>بازدید کننده در طول زمان</span>--}}
                        </p>
                        <p class="mr-auto d-flex flex-column text-right">
                            {{--                    <span class="text-success">--}}
                            {{--                      <i class="fa fa-arrow-up"></i> 12.5%--}}
                            {{--                    </span>--}}
                            {{--                        <span class="text-muted">از هفته گذشته</span>--}}
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <canvas id="visitors-chart" height="200"></canvas>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                        {{-- <span class="ml-2">
                           <i class="fa fa-square text-primary"></i> این هفته
                         </span>

                           <span>
                           <i class="fa fa-square text-gray"></i> هفته گذشته
                         </span>--}}
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-12" wire:ignore>
            <div class="card">
                <div class="card-header no-border">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">الأرباح الشهرية بين تاريخ <span class="text-success">{{$from}}</span> -
                            <span class="text-danger">{{$to}}</span></h3>
                        <a href="javascript:void(0);">إحصائيات أرباح المبيعات من المنتجات</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            {{--                        <span class="text-bold text-lg">820</span>--}}
                            {{--                        <span>بازدید کننده در طول زمان</span>--}}
                        </p>
                        <p class="mr-auto d-flex flex-column text-right">
                            {{--                    <span class="text-success">--}}
                            {{--                      <i class="fa fa-arrow-up"></i> 12.5%--}}
                            {{--                    </span>--}}
                            {{--                        <span class="text-muted">از هفته گذشته</span>--}}
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <canvas id="visitors-chart2" height="200"></canvas>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                        {{-- <span class="ml-2">
                           <i class="fa fa-square text-primary"></i> این هفته
                         </span>

                           <span>
                           <i class="fa fa-square text-gray"></i> هفته گذشته
                         </span>--}}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@push('js')
    <script>
        let allOfDays=@json($paysOfDay);


        let days=[];
        let priceOfDay=[];
        allOfDays.forEach(function(el) {
            days.push(el.day);
            priceOfDay.push(el.total);

        });
        var ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
        }
        var mode      = 'index'
        var intersect = true
        var $visitorsChart = $('#visitors-chart')
        var visitorsChart  = new Chart($visitorsChart, {
            data   : {
                labels  :days,
                datasets: [{
                    type                : 'line',
                    data                : priceOfDay,
                    backgroundColor     : 'blue',
                    borderColor         : '#007bff',
                    pointBorderColor    : '#007bff',
                    pointBackgroundColor: '#007bff',
                    fill                : false
                    // pointHoverBackgroundColor: '#007bff',
                    // pointHoverBorderColor    : '#007bff'
                }]
            },
            options: {
                maintainAspectRatio: false,
                tooltips           : {
                    mode     : mode,
                    intersect: intersect
                },
                hover              : {
                    mode     : mode,
                    intersect: intersect
                },
                legend             : {
                    display: false
                },
                scales             : {
                    yAxes: [{
                        // display: false,
                        gridLines: {
                            display      : true,
                            lineWidth    : '4px',
                            color        : 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks    : $.extend({
                            beginAtZero : true,
                            suggestedMax: 200
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display  : true,
                        gridLines: {
                            display: false
                        },
                        ticks    : ticksStyle
                    }]
                }
            }
        })
    </script>

    <script>
        let allOfMonthes=@json($paysOfMonth);


        let Month=[];
        let priceOfMonth=[];
        allOfMonthes.forEach(function(el) {
            Month.push(el.month);
            priceOfMonth.push(el.total);

        });
        var ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
        }
        var mode      = 'index'
        var intersect = true
        var $visitorsChart = $('#visitors-chart2')
        var visitorsChart  = new Chart($visitorsChart, {
            data   : {
                labels  :Month,
                datasets: [{
                    type                : 'line',
                    data                : priceOfMonth,
                    backgroundColor     : 'blue',
                    borderColor         : '#007bff',
                    pointBorderColor    : '#007bff',
                    pointBackgroundColor: '#007bff',
                    fill                : false
                    // pointHoverBackgroundColor: '#007bff',
                    // pointHoverBorderColor    : '#007bff'
                }]
            },
            options: {
                maintainAspectRatio: false,
                tooltips           : {
                    mode     : mode,
                    intersect: intersect
                },
                hover              : {
                    mode     : mode,
                    intersect: intersect
                },
                legend             : {
                    display: false
                },
                scales             : {
                    yAxes: [{
                        // display: false,
                        gridLines: {
                            display      : true,
                            lineWidth    : '4px',
                            color        : 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks    : $.extend({
                            beginAtZero : true,
                            suggestedMax: 200
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display  : true,
                        gridLines: {
                            display: false
                        },
                        ticks    : ticksStyle
                    }]
                }
            }
        })
    </script>
@endpush
