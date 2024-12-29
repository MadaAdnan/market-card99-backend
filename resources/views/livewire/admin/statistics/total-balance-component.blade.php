<div class="row">

    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{$total?->total??0}}</h3>

                <p>مجموع الأرصدة في الموقع</p>
            </div>
            <div class="icon">
                <i class="ion ion-dollar"></i>
            </div>

        </div>
    </div>
        <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>مبيعات سيرفر Yutu</h3>

                <p>
                    <span>مجموع المبيعات : {{$orders?->total??0}}</span>
                    <span>مجموع الفواتير : {{$orders?->count??0}}</span>
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-dollar"></i>
            </div>

        </div>
</div>


</div>
