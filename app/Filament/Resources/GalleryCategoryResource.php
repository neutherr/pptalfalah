<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryCategoryResource\Pages;
use App\Models\GalleryCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GalleryCategoryResource extends Resource
{
    protected static ?string $model = GalleryCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Konten Utama';
    protected static ?string $navigationLabel = 'Kategori Galeri';
    protected static ?string $modelLabel = 'Kategori Galeri';
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

            Forms\Components\Textarea::make('description')
                ->label('Deskripsi')
                ->rows(2)
                ->columnSpanFull(),

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
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->label('Slug'),
                Tables\Columns\TextColumn::make('items_count')->label('Jumlah Item')->counts('items'),
                Tables\Columns\TextColumn::make('order')->label('Urutan')->sortable(),
            ])
            ->reorderable('order')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGalleryCategories::route('/'),
            'create' => Pages\CreateGalleryCategory::route('/create'),
            'edit'   => Pages\EditGalleryCategory::route('/{record}/edit'),
        ];
    }
}
