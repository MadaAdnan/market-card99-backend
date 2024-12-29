<?php

namespace App\Filament\Widgets;

use App\Enums\BillStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Models\Bill;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BuyChart extends ApexChartWidget
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
    protected static ?string $heading = 'BuyChart';
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
            DatePicker::make('date_start')
                ->default(now()->startOfMonth()),
            DatePicker::make('date_end')
                ->default(now()),
            Select::make('type')->options([
                'product' => ' مبيعات المنتجات',
                'number' => 'مبيعات الأرقام',
                'life-cash' => 'مبيعات LifeCash',
                'eko' => 'مبيعات EKO',
                'speed' => 'مبيعات Speed',
                'drd3' => 'مبيعات DRD & CashSmm',
                'as7ab' => 'مبيعات أصحاب',
                'mazaya' => 'مبيعات مزايا',
                'cache-back'=>'كاش باك'
            ])->multiple()->label('فلتر المبيعات'),
        ];
    }

    protected function getOptions(): array
    {
        $bill = Bill::orWhere('status', [BillStatusEnum::COMPLETE->value, BillStatusEnum::SUCCESS->value])->whereNull('api_id')->whereBetween('created_at', [Carbon::parse($this->filterFormData['date_start']), Carbon::parse($this->filterFormData['date_end'])])->sum('price');
        $orders = Order::where('status', OrderStatusEnum::COMPLETE->value)->whereBetween('created_at', [Carbon::parse($this->filterFormData['date_start']), Carbon::parse($this->filterFormData['date_end'])])->sum('price');
        $life = Bill::orWhere('status', [BillStatusEnum::COMPLETE->value, BillStatusEnum::SUCCESS->value])->whereNotNull('api_id')->where(function ($query) {
            return $query->whereNull('api')->orWhere('api', 'life-cash');
        })->whereBetween('created_at', [Carbon::parse($this->filterFormData['date_start']), Carbon::parse($this->filterFormData['date_end'])])->sum('price');
        $eko = Bill::orWhere('status', [BillStatusEnum::COMPLETE->value, BillStatusEnum::SUCCESS->value])->where('api', 'eko')->whereBetween('created_at', [Carbon::parse($this->filterFormData['date_start']), Carbon::parse($this->filterFormData['date_end'])])->sum('price');
        $speed = Bill::orWhere('status', [BillStatusEnum::COMPLETE->value, BillStatusEnum::SUCCESS->value])->where('api', 'speed-card')->whereBetween('created_at', [Carbon::parse($this->filterFormData['date_start']), Carbon::parse($this->filterFormData['date_end'])])->sum('price');
        $drd3 = Bill::orWhere('status', [BillStatusEnum::COMPLETE->value, BillStatusEnum::SUCCESS->value])->where(fn($query) => $query->where('api', 'drd3')->orWhere('api', 'cash-mm'))->whereBetween('created_at', [Carbon::parse($this->filterFormData['date_start']), Carbon::parse($this->filterFormData['date_end'])])->sum('price');
        $as7ab = Bill::orWhere('status', [BillStatusEnum::COMPLETE->value, BillStatusEnum::SUCCESS->value])->where(fn($query) => $query->where('api', 'as7ab'))->whereBetween('created_at', [Carbon::parse($this->filterFormData['date_start']), Carbon::parse($this->filterFormData['date_end'])])->sum('price');
        $mazaya = Bill::orWhere('status', [BillStatusEnum::COMPLETE->value, BillStatusEnum::SUCCESS->value])->where(fn($query) => $query->where('api', 'mazaya'))->whereBetween('created_at', [Carbon::parse($this->filterFormData['date_start']), Carbon::parse($this->filterFormData['date_end'])])->sum('price');
        $cachBack = Bill::orWhere('status', [BillStatusEnum::COMPLETE->value, BillStatusEnum::SUCCESS->value])->where(fn($query) => $query->where('api', 'cache-back'))->whereBetween('created_at', [Carbon::parse($this->filterFormData['date_start']), Carbon::parse($this->filterFormData['date_end'])])->sum('price');
        $billLabel = [
            'product' => ' مبيعات المنتجات',
            'number' => 'مبيعات الأرقام',
            'life-cash' => 'مبيعات LifeCash',
            'eko' => 'مبيعات EKO',
            'speed' => 'مبيعات Speed',
            'drd3' => 'مبيعات DRD & CashSmm',
            'as7ab' => 'مبيعات أصحاب',
            'mazaya' => 'مبيعات مزايا',
            'cache-back'=>'كاش باك'
        ];
        $billsData = [
            'product' => $bill,
            'number' => $orders,
            'life-cash' => $life,
            'eko' => $eko,
            'speed' => $speed,
            'drd3' => $drd3,
            'as7ab' => $as7ab,
            'mazaya' => $mazaya,
            'cachback' => $cachBack,
        ];

        $data = ['data' => [], 'labels' => []];

        if (isset($this->filterFormData['type']) && count($this->filterFormData['type'])>0 ) {
            foreach ($this->filterFormData['type'] as $type) {
                $data['data'][] = $billsData[$type];
                $data['labels'][] = $billLabel[$type];
            }
        } else{
            foreach ($billLabel as $key => $label) {
                if(isset($billsData[$key])){
                    $data['data'][] = $billsData[$key];
                    $data['labels'][] = $label;
                }

            }
        }


        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'BuyChart',
                    'data' =>$data['data'] ,
                ],
            ],
            'xaxis' => [
                'categories' =>$data['labels'],
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
            'colors' => ['#6366f1'],
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
