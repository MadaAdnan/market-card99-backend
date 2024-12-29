<?php

namespace App\Filament\Widgets;

use App\Enums\BillStatusEnum;
use App\Models\Bill;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TopProduct extends ApexChartWidget
{
    public static function canView(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }
    /**
     * Chart Id
     *
     * @var string
     */
    protected static string $chartId = 'topProduct';
    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];
    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'المنتجات الأعلى مبيعاً خلال آخر 10 أيام';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        /*$bills= Bill::with('product')
            ->whereBetween('created_at',[now()->subDays(300),now()])
            ->join('products','products.id','=','bills.product_id')
            ->where(fn($q)=>$q->where('status',BillStatusEnum::COMPLETE->value)->orWhere('status',BillStatusEnum::COMPLETE->value))
            ->selectRaw('product_id,count(id) as count,products.name as name')
            ->groupBy('products.name')
            ->orderByRaw('COUNT(*) DESC')
            ->take(6)

            ->get();*/
        $bills = Bill::join('products','product_id','=','products.id')
            ->selectRaw('product_id, COUNT(product_id) as count, products.name as name')
            ->whereBetween('bills.created_at', [now()->subDays(10), now()])
            ->where('status', BillStatusEnum::COMPLETE->value)
            ->groupBy('product_id', 'name')
            ->orderByRaw('COUNT(*) DESC')
            ->take(6)
            ->get();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'TopProduct',
                    'data' =>$bills->pluck('count')->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $bills->pluck('name')->toArray(),
                'labels' => [
                    'style' => [
                        'colors' => '#FF0000',
                        'fontWeight' => 600,
                        'fontSize'=>18
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                        'fontSize'=>12,

                    ],
                ],
            ],
            'colors' => [fake()->hexColor.'55',],
          /*  'annotations' => [
                'yaxis' => [
                    [
                        'y' => 15,
                        'borderColor' => '#fcd34d',
                        'borderWidth' => '5',
                        'label' => [
                            'offsetX' => -5,
                            'offsetY' => -13,
                            'borderColor' => '#f59e0b',
                            'style' => [
                                'color' => '#fffbeb',
                                'background' => '#f59e0b',
                            ],
                            'text' => 'Label Example',
                        ],
                    ],
                ],
            ],*/
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
