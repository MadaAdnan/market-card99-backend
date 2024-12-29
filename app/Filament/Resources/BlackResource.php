<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlackResource\Pages;
use App\Filament\Resources\BlackResource\RelationManagers;
use App\Models\Black;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlackResource extends Resource
{
    protected static ?string $model = Black::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $label = 'القائمة السوداء';
    protected static ?string $navigationLabel = 'القائمة السوداء';
    protected static ?string $pluralLabel = 'القائمة السوداء';
    protected static ?string $navigationGroup = 'أساسي';
    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('data')->label('الحساب ID , Username,Url')->required(),
                Forms\Components\Textarea::make('info')->label('سبب الحظر')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('data')->label('الحساب ')->searchable(),
                Tables\Columns\TextColumn::make('info')->label('سبب الحظر'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListBlacks::route('/'),
            'create' => Pages\CreateBlack::route('/create'),
            'edit' => Pages\EditBlack::route('/{record}/edit'),
        ];
    }
}
