<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $label = 'الأقسام';
    protected static ?string $navigationLabel = 'الأقسام';
    protected static ?string $pluralLabel = 'الأقسام';
    protected static ?string $navigationGroup = 'الأقسام والمنتجات';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الأقسام')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('image')->conversion('webp')->required()->image()->imageCropAspectRatio('1:1')->label('صورة القسم'),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('slider')->collection('slider')->conversion('webp')->required()->image()->imageCropAspectRatio('7:2')->label('صورة سلايدر')->multiple()->hidden(fn($get) => $get('category_id') != null),
                    Forms\Components\TextInput::make('name')->required()->label('اسم القسم'),
                    Forms\Components\Radio::make('type')->required()->inline()
                        ->options([
                            'default' => 'قسم عادي',
                            'online' => 'أون لاين',
//                            'sim90'=>'sim90'
                        ])->default('default')
                        ->label('نوع القسم'),
                    Forms\Components\Select::make('category_id')->options(Category::whereNull('category_id')->pluck('name', 'id'))->label('يتبع للقسم')->searchable()->reactive(),
                    Forms\Components\Textarea::make('info')->label('وصف القسم'),
                    Forms\Components\Toggle::make('active')->label('حالة القسم'),
                    Forms\Components\Toggle::make('is_available')->label('متوفر'),
                    Forms\Components\Toggle::make('can_check')->label('قابل للفحص')->reactive()->visible(fn($get) => $get('category_id') != null),
                    Forms\Components\Select::make('game')->options([
                        'pubg' => 'بوبجي',
                        'pubglite' => 'بوبجي لايت',
                        'freefire' => 'فري فاير',
                        'likee' => 'لايكي',
                        'bigo' => 'بيجو',
                        'lightchat' => 'لايت شات',
                        'oohla' => 'أوهلا',
                        'yalla' => 'يلا',
                        'ligo' => 'ليغو',
                        'jawaker' => 'جواكر',
                        'talktalk' => 'TalkTalk',
                        'yoyo' => 'YoYo',
                        'azal' => 'Azal',
                        'livu' => 'Livu',
                        'tumile' => 'Tumile',
                        'fancylive' => 'Fancy Live',
                        'mixu'=>'Mixu',
                        'hiya'=>'Hiya',
                        'haki'=>'Haki',


                    ])->label('نوع الفحص')->required(fn($get) => $get('can_check'))->visible(fn($get) => $get('category_id') != null)

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->extraImgAttributes(fn($record) => ['alt' => $record->name])->collection('image')->conversion('webp')->circular()->label('الصورة'),
                Tables\Columns\TextColumn::make('name')->label('اسم القسم')->searchable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->enum([
                        'default' => 'قسم عادي',
                        'online' => 'أون لاين',
//                        'sim90'=>'sim90'
                    ])
                    ->label('نوع القسم'),
                Tables\Columns\TextColumn::make('category.name')->label('القسم الرئيسي'),
                Tables\Columns\ToggleColumn::make('active')->label('حالة القسم'),
                Tables\Columns\ToggleColumn::make('is_available')->label('حالة التوفر'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')->options(Category::whereNull('category_id')->pluck('name', 'id'))->label('إختر القسم')->searchable(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
