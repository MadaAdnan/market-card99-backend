<?php

namespace App\Filament\Resources;

use App\Enums\ActivateStatusBoolEnum;
use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-qrcode';
    protected static ?string $label = 'الكوبونات';
    protected static ?string $navigationLabel = 'الكوبونات';
    protected static ?string $pluralLabel = 'الكوبونات';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?int $navigationSort = 1;
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
                Tables\Columns\TextColumn::make('code')->formatStateUsing(fn($record)=>$record->status==ActivateStatusBoolEnum::INACTIVE||$record->status==0?$record->code:'')->label('رقم الكوبون')->searchable(),
                Tables\Columns\TextColumn::make('status')->formatStateUsing(fn($record)=>ActivateStatusBoolEnum::tryFrom($record->status)?->coupon())->color(fn($record)=>ActivateStatusBoolEnum::tryFrom($record->status)?->color())->label('حالة الكوبون'),
                Tables\Columns\TextColumn::make('user.name')->label('إستخدمه')->searchable(),
                Tables\Columns\TextColumn::make('price')->label('السعر')->sortable(),

            ])
            ->filters([
                TernaryFilter::make('user_id')
                    ->placeholder('حالة الكوبون')
                    ->trueLabel('غير مستخدم')
                    ->falseLabel('مستخدم')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNull('user_id'),
                        false: fn (Builder $query) => $query->whereNotNull('user_id'),
                        blank: fn (Builder $query) => $query,
                    )
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
