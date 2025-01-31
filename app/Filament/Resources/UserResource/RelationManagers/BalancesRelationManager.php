<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BalancesRelationManager extends RelationManager
{
    protected static string $relationship = 'balances';
    protected static ?string $title = 'الرصيد';
    protected static ?string $recordTitleAttribute = 'credit';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#'),
                Tables\Columns\TextColumn::make('credit')->label('شحن'),
                Tables\Columns\TextColumn::make('debit')->label('سحب'),
                Tables\Columns\TextColumn::make('total')->label('الرصيد'),
                Tables\Columns\TextColumn::make('info')->label('البيان'),
                Tables\Columns\TextColumn::make('bill.uuid')->label('رقم الطلب'),
                Tables\Columns\TextColumn::make('created_at')->date('Y-m-d')->label('التاريخ'),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                //  Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                //  Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //  Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
