<?php

namespace App\Http\Livewire\Admin\Statistics;

use App\Enums\BillStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Models\Balance;
use App\Models\Bill;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OrderComponent extends Component
{
    public $from;
    public $to;
public $balance_from;
    public $balance_date;
    public function mount(){
        $this->from=now()->startOfYear()->format('Y-m-d');
        $this->to=now()->endOfYear()->format('Y-m-d');
        $this->balance_from=now()->format('Y-m-d');
        $this->balance_date=now()->format('Y-m-d');
       /* $paysOfDay=DB::table('orderable')->where('status','complete')
            ->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()])
            ->selectRaw( 'sum(price) as price, Day(created_at) as day')->groupByRaw('Day(created_at)')
            ->get();
        $paysOfMonth=DB::table('orderable')->where('status','complete')
            ->whereBetween('created_at',[now()->startOfYear(),now()->endOfYear()])
            ->selectRaw( 'sum(price) as price, Month(created_at) as month')->groupByRaw('Month(created_at)')
            ->get();*/
    }
    public function render()
    {
        return view('livewire.admin.statistics.order-component',[
            'orders'=>DB::table('bills')->whereNull('api_id')->where('status',BillStatusEnum::COMPLETE->value)->whereBetween('created_at',[$this->from,$this->to])->selectRaw('Sum(price) as total')->first()?->total,
            'orders_api'=>DB::table('bills')->whereNotNull('api_id')->where(function($query){
               return $query->whereNull('api')->orWhere('api','life-cash');
            })->where('status',BillStatusEnum::COMPLETE->value)->whereBetween('created_at',[$this->from,$this->to])->selectRaw('Sum(price) as total')->first()?->total,
            'orders_speed'=>DB::table('bills')->whereNotNull('api_id')->where('api','speed-card')->where('status',BillStatusEnum::COMPLETE->value)->whereBetween('created_at',[$this->from,$this->to])->selectRaw('Sum(price) as total')->first()?->total,
            'orders_eko'=>DB::table('bills')->whereNotNull('api_id')->where('api','eko')->where('status',BillStatusEnum::COMPLETE->value)->whereBetween('created_at',[$this->from,$this->to])->selectRaw('Sum(price) as total')->first()?->total,
            'online'=>DB::table('orders')->where('status',OrderStatusEnum::COMPLETE->value)->whereBetween('created_at',[$this->from,$this->to])->selectRaw('Sum(price) as total')->first()?->total,
            'paysOfMonth'=>DB::table('bills')->where('status',BillStatusEnum::COMPLETE->value)->whereBetween('created_at',[now()->startOfYear(),now()->endOfYear()])
                ->selectRaw('(SUM(price) - SUM(cost)) as total , Month(created_at) as month')->groupByRaw('Month(created_at)')->get(),
            'paysOfDay'=>DB::table('bills')->where('status',BillStatusEnum::COMPLETE->value)->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()])
                ->selectRaw('(SUM(price) - SUM(cost)) as total , Day(created_at) as day')->groupByRaw('Day(created_at)')->get(),
            'balances'=>Balance::whereBetween('created_at',[Carbon::parse($this->balance_from)->startOfDay(),Carbon::parse($this->balance_date)->endOfDay()])->where('balances.info','like','%عن طريق المدير%')->with('user')->latest()->get(),
        ]);
    }
}
