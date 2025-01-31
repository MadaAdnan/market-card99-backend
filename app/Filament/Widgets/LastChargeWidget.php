<?php

namespace App\Filament\Widgets;

use App\Models\Balance;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Widgets\TableWidget;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;

class LastChargeWidget extends TableWidget
{
    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];
    protected static ?int $sort=9;
    protected static ?string $heading='عمليات الشحن';
    public static function canView(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }
    /*protected int | string | array $columnSpan=2;
    protected static string $view = 'filament.widgets.last-charge-widget';*/
    protected function getTableQuery(): Builder
    {
        return Balance::query()->/*whereBetween('created_at',[Carbon::parse($this->start)->startOfDay(),Carbon::parse($this->end)->endOfDay()])->*/
        where('balances.info','like','%عن طريق المدير%')->with('user')->latest();
    }
protected function getTableFilters(): array
{
    return [
        Filter::make('created_at')
            ->form([
                DatePicker::make('created_from')->label('من'),
               DatePicker::make('created_until')->label('إلى'),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['created_from'],
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                    )
                    ->when(
                        $data['created_until'],
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                    );
            })->default(true)
    ];
}
protected function getTableRecordsPerPageSelectOptions(): array
{
    return [
        10,25
    ];
}

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('user.email')->label('البريد')->searchable(),
            TextColumn::make('user.name')->label('المستخدم'),
            TextColumn::make('credit')->label('الشحن'),
            TextColumn::make('debit')->label('السحب'),
            TextColumn::make('info')->label('البيان'),
            TextColumn::make('created_at')->date('Y-m-d H:i')->label('التاريخ'),
        ];
    }
}
