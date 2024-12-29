<?php

namespace App\Filament\Resources;

use App\Enums\ActivateStatusBoolEnum;
use App\Enums\ProductTypeEnum;
use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-view-list';
    protected static ?string $label = 'الأكواد الجاهزة';
    protected static ?string $navigationLabel = 'الأكواد الجاهزة';
    protected static ?string $pluralLabel = 'الأكواد الجاهزة';
    protected static ?string $navigationGroup = 'الأقسام والمنتجات';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Forms\Components\Section::make('الأكواد الجاهزة')->schema([
                  Forms\Components\Select::make('product_id')->preload()->options(Product::where('type',ProductTypeEnum::DEFAULT->value)->pluck('name','id'))->required()->label('المنتج')->searchable(),
                  Forms\Components\TextInput::make('code')->required()->label('الكود'),
                  Forms\Components\Toggle::make('active')->default(true)->label('حالة الكود')
              ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->label('الكود')->searchable(),
                Tables\Columns\TextColumn::make('product.name')->label('المنتج')->sortable(),
                Tables\Columns\TextColumn::make('bill_id')->label('رقم الفاتورة ')->searchable(),
                Tables\Columns\TextColumn::make('active')->formatStateUsing(fn($record)=>ActivateStatusBoolEnum::tryFrom($record->active)?->status())->color(fn($record)=>ActivateStatusBoolEnum::tryFrom($record->active)?->color())->label('حالة الكود'),
                Tables\Columns\TextColumn::make('updated_at')->formatStateUsing(fn($record)=>$record->bill_id?$record->updated_at->diffForHumans():'')->label('تاريخ الإستخدام'),
            ])->defaultSort('created_at','desc')
            ->filters([
                Tables\Filters\SelectFilter::make('product_id')->options(Product::where('type',ProductTypeEnum::DEFAULT->value)->pluck('name','id'))->searchable()->label('المنتج'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
