<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupResource\Pages;
use App\Filament\Resources\GroupResource\RelationManagers;
use App\Models\Group;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $label = 'الفئات';
    protected static ?string $navigationLabel = 'الفئات';
    protected static ?string $pluralLabel = 'الفئات';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الفئات')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('image')->label('الصورة')->image()->imageCropAspectRatio('2:1')->conversion('webp'),
                    Forms\Components\TextInput::make('name')->label('اسم الفئة')->required(),
                    Forms\Components\TextInput::make('price')->label('نسبة الربح من المنتج')->required()->numeric()->step(0.001),
                    Forms\Components\TextInput::make('ratio_delegate')->label('نسبة ربح الوكيل من الفئة')->required()->numeric()->step(0.001),
                    Forms\Components\TextInput::make('min_value')->label('أقل قيمة للاشتراك')->required()->numeric()->step(0.1),
                Forms\Components\Toggle::make('is_active')->label('عرض')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->circular()->collection('image')->conversion('webp'),
                Tables\Columns\TextColumn::make('name')->label('اسم الفئة'),
                Tables\Columns\TextColumn::make('users_count')->label('عدد المستخدمين'),
                Tables\Columns\TextColumn::make('price')->formatStateUsing(fn($record)=>($record->price*100).'%')->label('نسبة الربح'),
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
            ])->defaultSort('sort');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\UsersRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
        ];
    }
}
