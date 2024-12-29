<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Bill;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BillsRelationManager extends RelationManager
{
    protected static string $relationship = 'bills';
    protected static ?string $title = 'المشتريات';


    protected static ?string $recordTitleAttribute = 'id';

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
                Tables\Columns\TextColumn::make('id')->label('#')->searchable(isIndividual: true)->sortable(),
                Tables\Columns\TextColumn::make('product.name')->label('اسم المنتج')->words(2)->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('price')->label('السعر'),
                Tables\Columns\TextColumn::make('customer_id')->label('Phone/ID')->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('data_id')->label('Phone')->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('customer_name')->searchable(isIndividual: true)->formatStateUsing(fn($record) => $record->customer_name ?? $record->customer_username)->label('رقم الهاتف'),
                Tables\Columns\TextColumn::make('customer_password')->label('كلمة المرور')->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('amount')->label('الكمية'),
                Tables\Columns\TextColumn::make('customer_note')->label('ملاحظات الزبون'),
                Tables\Columns\BadgeColumn::make('status')->formatStateUsing(fn($record) => $record->status->status())->color(fn($record) => $record->status->color())->label('الحالة'),

                Tables\Columns\TextColumn::make('created_at')->since()->label('تاريخ الطلب')->sortable(),

            ])->defaultSort('created_at', 'desc')
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
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
