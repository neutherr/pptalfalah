<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryItemResource\Pages;
use App\Models\GalleryItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryItemResource extends Resource
{
    protected static ?string $model = GalleryItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Konten Utama';
    protected static ?string $navigationLabel = 'Galeri';
    protected static ?string $modelLabel = 'Item Galeri';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detail Item')->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('caption')
                    ->label('Keterangan')
                    ->rows(2),

                Forms\Components\Select::make('gallery_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\TextInput::make('slug')->required(),
                    ])
                    ->preload(),

                Forms\Components\Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'image' => 'Gambar',
                        'video' => 'Video',
                    ])
                    ->default('image')
                    ->live(),

                Forms\Components\TextInput::make('video_url')
                    ->label('URL Video (YouTube/dll)')
                    ->url()
                    ->visible(fn (Forms\Get $get) => $get('type') === 'video'),

                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
            ])->columns(2),

            Forms\Components\Section::make('Gambar & Status')->schema([
                Forms\Components\FileUpload::make('image')
                    ->label('Gambar')
                    ->image()
                    ->directory('gallery')
                    ->imagePreviewHeight(250)
                    ->required(fn (Forms\Get $get) => $get('type') === 'image'),

                Forms\Components\Toggle::make('is_featured')
                    ->label('Tampilkan di Beranda')
                    ->default(false),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->height(60),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn ($state) => $state === 'video' ? 'danger' : 'success'),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gallery_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGalleryItems::route('/'),
            'create' => Pages\CreateGalleryItem::route('/create'),
            'edit'   => Pages\EditGalleryItem::route('/{record}/edit'),
        ];
    }
}
