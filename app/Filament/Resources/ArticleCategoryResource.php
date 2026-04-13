<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleCategoryResource\Pages;
use App\Models\ArticleCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ArticleCategoryResource extends Resource
{
    protected static ?string $model = ArticleCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Informasi';
    protected static ?string $navigationLabel = 'Kategori Berita';
    protected static ?string $modelLabel = 'Kategori Berita';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Kategori')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

            Forms\Components\TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->unique(ignorable: fn ($record) => $record),

            Forms\Components\ColorPicker::make('color')
                ->label('Warna Badge')
                ->default('#306a43'),

            Forms\Components\TextInput::make('order')
                ->label('Urutan')
                ->numeric()
                ->default(0),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('slug')->label('Slug'),
                Tables\Columns\ColorColumn::make('color')->label('Warna'),
                Tables\Columns\TextColumn::make('articles_count')->label('Jumlah Artikel')->counts('articles'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListArticleCategories::route('/'),
            'create' => Pages\CreateArticleCategory::route('/create'),
            'edit'   => Pages\EditArticleCategory::route('/{record}/edit'),
        ];
    }
}
