<?php

namespace App\Filament\Resources\ServerResource\RelationManagers;

use App\Models\Program;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProgramsRelationManager extends RelationManager
{
    protected static string $relationship = 'programs';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('التطبيقات')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('image')->conversion('webp')->label('الصورة'),
                    Forms\Components\TextInput::make('name')->label('اسم التطبيق')->required(),
//                    Forms\Components\Select::make('server_id')->label('السيرفر')->relationship('server','name')->preload()->required(),
                    Forms\Components\Select::make('country_id')->label('الدولة')->relationship('country','name',fn($livewire,$query)=>$query->where('server_id',$livewire->ownerRecord->id))->preload()->required(),
                    Forms\Components\TextInput::make('code')->label('كود التطبيق')->required(),
                    Forms\Components\TextInput::make('price')->label('سعر التطبيق')->required()->numeric(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sortable')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('image')->conversion('webp')->label('صورة'),
               Tables\Columns\TextColumn::make('name')->label('التطبيق'),
                Tables\Columns\TextColumn::make('code')->label('الرمز'),
                Tables\Columns\TextColumn::make('price')->label('السعر'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->mutateFormDataUsing(function($livewire,$data){
                    $data['server_id']=$livewire->ownerRecord->id;
                    return $data;
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
