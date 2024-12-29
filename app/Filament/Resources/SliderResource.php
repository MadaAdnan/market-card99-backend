<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Filament\Resources\SliderResource\RelationManagers;
use App\Models\Slider;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-photograph';
    protected static ?string $label = 'الإعلانات';
    protected static ?string $navigationLabel = 'الإعلانات';
    protected static ?string $pluralLabel = 'الإعلانات';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الإعلانات')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('image')->conversion('webp')->imageCropAspectRatio('7:2')->image()->required()->label('صورة الإعلان'),
                    Forms\Components\TextInput::make('whats')->label('رقم الواتس'),
                    Forms\Components\TextInput::make('face')->url()->label('رابط صفحة فيسبوك'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('image')->conversion('webp')->label('صورة الإعلان'),

                Tables\Columns\TextColumn::make('whats')->url(fn($record)=>$record->whats!=null?'https://wa.me/'.ltrim(ltrim($record->whats,'+'),'00'):'#',true)->label('رقم الواتس'),

                Tables\Columns\TextColumn::make('face')->url(fn($record)=>$record->face,true)->label('رابط صفحة فيسبوك'),

            ])
            ->filters([
                //
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
