<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PointsRelationManager extends RelationManager
{
    protected static string $relationship = 'points';

    protected static ?string $recordTitleAttribute = 'credit';
    protected static ?string $title='النقاط';

    public static function canViewForRecord(Model $ownerRecord): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('credit')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('credit')->label('شحن'),
                Tables\Columns\TextColumn::make('debit')->label('سحب'),
//                Tables\Columns\TextColumn::make('total')->label('الرصيد'),
                Tables\Columns\TextColumn::make('info')->label('البيان'),
                Tables\Columns\TextColumn::make('created_at')->date('Y-m-d')->label('التاريخ'),
            ])->defaultSort('created_at','desc')
            ->filters([
                //
            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
