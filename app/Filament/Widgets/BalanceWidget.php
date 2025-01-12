<?php

namespace App\Filament\Widgets;

use App\Enums\BillStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Helpers\Yutu;
use App\Models\Bill;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class BalanceWidget extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }
    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    protected function getCards(): array
    {
        $balancesUser=\DB::table('balances')->selectRaw('Sum(credit-debit) as total')->first()?->total;
        $start=now()->startOfYear();
        $end=now();
        $yutu=Order::whereHas('server',fn($q)=>$q->where('code',Yutu::class))->where('status',OrderStatusEnum::COMPLETE->value)->whereBetween('created_at',[$start,$end])->selectRaw('SUM(price) as total')->first()?->total;
        $card= [
            Card::make('أرصدة الزبائن',$balancesUser)->extraAttributes(['style'=>'background-color:red;color:white']),
            Card::make('مبيعات سيرفر YUTU',$yutu)->extraAttributes(['style'=>'background-color:purple;color:white']),
        ];

        $products=[];
        /*foreach ( $bills as $item) {
            $products[]=Card::make($item->product->name,$item->count);
        }*/
        return array_merge($card,$products);
    }
}
