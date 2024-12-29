<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatusEnum;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Helpers\Viotp;
use App\InterFaces\ServerInterface;
use App\Models\Order;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saadj55\FilamentCopyable\Tables\Columns\CopyableTextColumn;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-search';
    protected static ?string $label = 'طلبات أونلاين';
    protected static ?string $navigationLabel = 'طلبات أونلاين';
    protected static ?string $pluralLabel = 'طلبات أونلاين';
    protected static ?string $navigationGroup = 'أونلاين';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->disableClick()->label('المستخدم'),
                Tables\Columns\TextColumn::make('phone')->disableClick()->label('رقم الهاتف')->searchable(),

                Tables\Columns\TextColumn::make('code')->disableClick()->label('الكود')->copyable()->searchable(),
                Tables\Columns\TextColumn::make('status')->disableClick()->formatStateUsing(fn($record) => OrderStatusEnum::tryFrom($record->status->value)?->status())->color(fn($record) => OrderStatusEnum::tryFrom($record->status->value)?->color())->label('الكود'),
                Tables\Columns\TextColumn::make('server.name')->disableClick()->label('السيرفر'),
                Tables\Columns\TextColumn::make('country.name')->disableClick()->label('الدولة'),
                Tables\Columns\TextColumn::make('program.name')->disableClick()->label('التطبيق'),
                Tables\Columns\TextColumn::make('price')->disableClick()->formatStateUsing(fn($record) => number_format($record->price, 2))->label('السعر'),
                Tables\Columns\TextColumn::make('created_at')->disableClick()->since()->label('منذ'),
            ])->defaultSort('created_at','desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    OrderStatusEnum::WAITE->value=>OrderStatusEnum::WAITE->status(),
                    OrderStatusEnum::COMPLETE->value=>OrderStatusEnum::COMPLETE->status(),
                ])
            ])
            ->actions([
//                Tables\TablesActions\EditAction::make()->button(),
//                Tables\Actions\DeleteAction::make()->button(),
            Tables\Actions\Action::make('cancel_order')->action(function($record){
                /**
                 * @var $server ServerInterface
                 */

                $server=new $record->server->code();
                $server->cancelOrder($record);
            })->visible(fn($record)=>$record->created_at->lessThan(now()->subDays(3)))->requiresConfirmation()->label('إلغاء الطلب')
            ])
            ->bulkActions([
//                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
//            'create' => Pages\CreateOrder::route('/create'),
//            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
