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

class AffiliatesRelationManager extends RelationManager
{
    protected static string $relationship = 'affiliates';
protected static ?string $title='الإحالات';
    protected static ?string $recordTitleAttribute = 'name';
    public static function canViewForRecord(Model $ownerRecord): bool
    {
        return auth()->user()->hasRole('super_admin');
    }
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
                Tables\Columns\TextColumn::make('name')->searchable()->label('الاسم'),
                Tables\Columns\TextColumn::make('username')->searchable()->label('اسم المستخدم'),
                Tables\Columns\TextColumn::make('email')->searchable()->label('البريد الإلكتروني'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('delete')->label('إلغاء من الإحالة')->action(fn($record)=>$record->update(['affiliate_id'=>null]))->button()->color('danger')->requiresConfirmation()
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
