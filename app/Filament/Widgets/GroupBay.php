<?php

namespace App\Filament\Widgets;

use App\Enums\BillStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Models\Bill;
use App\Models\Group;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class GroupBay extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static string $chartId = 'buyChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'مشتريات الفئات';
    public ?string $filter = 'date';
    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    public static function canView(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('group')->options(Group::pluck('groups.name','groups.id'))->label('الفئة'),
        ];
    }

    protected function getOptions(): array
    {
        //$bill = Bill::orWhere('status', [BillStatusEnum::COMPLETE->value, BillStatusEnum::SUCCESS->value])->whereBetween('created_at', [Carbon::parse($this->filterFormData['date_start']), Carbon::parse($this->filterFormData['date_end'])])->sum('price');
$userIds=[];
$group=Group::find($this->filterFormData['group']);
if($group!=null){
    $userIds=$group->users->pluck('id')->toArray();
}
        $trend = Trend::query(Order::where('status', OrderStatusEnum::COMPLETE->value)
            ->when(count($userIds)>0, fn ($query)=>  $query->whereIn('user_id',$userIds))
            ->selectRaw('created_at,Sum(price - cost) as price')
    )
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('price');
        $trend2 = Trend::query(Bill::where('status', OrderStatusEnum::COMPLETE->value)
            ->when(count($userIds)>0, fn ($query)=>  $query->whereIn('user_id',$userIds))

        )
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('price');






        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'الارقام',
                    'data' => $trend->map(fn (TrendValue $value) => $value->aggregate),
                ], [
                    'name' => 'المشتريات',
                    'data' => $trend2->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'xaxis' => [
                'categories' =>$trend->map(fn (TrendValue $value) => $value->date),
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'colors' => ['#6366f1','#FF3388'],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shade' => 'dark',
                    'type' => 'vertical',
                    'shadeIntensity' => 0.5,
                    'gradientToColors' => ['#34d399'],
                    'inverseColors' => true,
                    'opacityFrom' => 1,
                    'opacityTo' => 1,
                    'stops' => [0, 100],
                ],
            ],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => false,
                ],
            ],
            'stroke' => [
                'curve' => 'smooth',
            ],
            'dataLabels' => [
                'enabled' => true,
            ],


        ];
    }
}
