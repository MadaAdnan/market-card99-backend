<div>
    <button wire:loading.class="d-none" wire:target="getData" wire:click="getData('life-cash')" class="btn-sm btn-info">LifeCashجلب البيانات</button>
    <button wire:loading.class="d-none" wire:target="getData" wire:click="getData('eko')" class="btn-sm btn-info">EKoجلب البيانات</button>
    <button wire:loading.class="d-none" wire:target="getData" wire:click="getData('speed-card')" class="btn-sm btn-info">SpeedCard البيانات</button>
    <span wire:loading.class="d-block" wire:target="getData" class="d-none"> جاري جلب البيانات ...</span>
    <div class="table-responsive">
        <input type="text" class="form-control-sm" wire:model="search">
        <table class="table table-hover table-striped">
            <thead>
           <tr>
               <th>التطبيق</th>
               <th>ID</th>
               <th>السعر</th>
           </tr>
            </thead>
            <tbody>
            @forelse($data as $app)
                <tr>
                    <td>{{$app['name']}}</td>
                    <td>{{$app['id']}}</td>
                    <td>{{number_format($app['price'],2)}}</td>
                </tr>
            @empty
<tr>
    <td colspan="3">لا يوجد بيانات</td>
</tr>
            @endforelse

            </tbody>
        </table>
    </div>
</div>
