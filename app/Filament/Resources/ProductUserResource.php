<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductUserResource\Pages;
use App\Filament\Resources\ProductUserResource\RelationManagers;
use App\Models\Product;
use App\Models\ProductUser;
use App\Models\User;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductUserResource extends Resource
{
    protected static ?string $model = ProductUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $label = 'الأسعار المخصصة';
    protected static ?string $navigationLabel = 'الأسعار المخصصة';
    protected static ?string $pluralLabel = 'الأسعار المخصصة';
    protected static ?string $navigationGroup = 'الأقسام والمنتجات';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الاسعار المخصصة')->schema([
                    Forms\Components\Select::make('product_id')->required()->options(Product::orderBy('category_id', 'desc')->orderBy('cost', 'desc')->pluck('name', 'id'))->searchable()->label('المنتج'),
                    Forms\Components\Select::make('user_id')->required()->options(User::OrderBy('name')->selectRaw("CONCAT(username,' | ', email) as fullname,id")->pluck('fullname', 'id'))->searchable()->label('الزبون'),
                    Forms\Components\TextInput::make('price')->label('السعر')->required()->numeric()

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.username')->label('الزبون')->searchable(),
                Tables\Columns\TextColumn::make('product.name')->label('المنتج')->searchable(),
                Tables\Columns\TextColumn::make('price')->label('السعر'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_id')->options(Product::orderBy('category_id', 'desc')->orderBy('cost', 'desc')->pluck('name', 'id'))->searchable()->label('المنتج'),
                Tables\Filters\SelectFilter::make('user_id')->options(User::orderBy('username', 'asc')->pluck('username', 'id'))->searchable()->label('المنتج'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button(),
                Tables\Actions\DeleteAction::make()->button(),
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
            'index' => Pages\ListProductUsers::route('/'),
            'create' => Pages\CreateProductUser::route('/create'),
            'edit' => Pages\EditProductUser::route('/{record}/edit'),
        ];
    }
}
