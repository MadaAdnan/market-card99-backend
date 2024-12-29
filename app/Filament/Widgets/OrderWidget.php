<?php

namespace App\Filament\Widgets;

use App\Enums\BillStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Models\Bill;
use App\Models\Order;
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class OrderWidget extends BaseWidget
{

    public static function canView(): bool
    {
        return auth()->user()->hasRole('super_admin12');
    }

    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    protected function getCards(): array
    {
        $bill = Bill::orWhere('status', [BillStatusEnum::COMPLETE->value, BillStatusEnum::SUCCESS->value])->whereNull('api_id')->whereBetween('created_at', [now()->startOfMonth(), now()])->sum('price');
        $orders = Order::where('status', OrderStatusEnum::COMPLETE->value)->whereBetween('created_at', [now()->startOfMonth(), now()])->sum('price');
        $life = Bill::orWhere('status', [BillStatusEnum::COMPLETE->value, BillStatusEnum::SUCCESS->value])->whereNotNull('api_id')->where(function ($query) {
            return $query->whereNull('api')->orWhere('api', 'life-cash');
        })->whereBetween('created_at', [now()->startOfMonth(), now()])->sum('price');
        $eko = Bill::orWhere('status', [BillStatusEnum::COMPLETE->value, BillStatusEnum::SUCCESS->value])->where('api', 'eko')->whereBetween('created_at', [now()->startOfMonth(), now()])->sum('price');
        $speed = Bill::orWhere('status', [BillStatusEnum::COMPLETE->value, BillStatusEnum::SUCCESS->value])->where('api', 'speed-card')->whereBetween('created_at', [now()->startOfMonth(), now()])->sum('price');

        return [
            BaseWidget\Card::make(' مبيعات المنتجات',$bill)->extraAttributes(['style'=>'background-color:'.fake()->hexColor]),
            BaseWidget\Card::make('مبيعات الأرقام',$orders)->extraAttributes(['style'=>'background-color:'.fake()->hexColor]),
            BaseWidget\Card::make('مبيعات LifeCash',$life)->extraAttributes(['style'=>'background-color:'.fake()->hexColor]),
            BaseWidget\Card::make('مبيعات Speed',$speed)->extraAttributes(['style'=>'background-color:'.fake()->hexColor]),
            BaseWidget\Card::make(' مبيعات EKO',$eko)->extraAttributes(['style'=>'background-color:'.fake()->hexColor]),
            ];
    }
}
