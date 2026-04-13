<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSectionResource\Pages;
use App\Models\HeroSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HeroSectionResource extends Resource
{
    protected static ?string $model = HeroSection::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Konten Utama';
    protected static ?string $navigationLabel = 'Hero Section';
    protected static ?string $modelLabel = 'Hero Slide';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Slide')->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Label Internal (Hanya penamaan penanda)')
                    ->required()
                    ->maxLength(255),
            ])->columns(1),

            Forms\Components\Section::make('Gambar & Urutan')->schema([
                Forms\Components\FileUpload::make('image')
                    ->label('Gambar Background')
                    ->image()
                    ->directory('hero-sections')
                    ->imagePreviewHeight(200)
                    ->required(),

                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),

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
                    ->label('Label/Catatan')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable(),
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
            'index'  => Pages\ListHeroSections::route('/'),
            'create' => Pages\CreateHeroSection::route('/create'),
            'edit'   => Pages\EditHeroSection::route('/{record}/edit'),
        ];
    }
}
