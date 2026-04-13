<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityResource\Pages;
use App\Filament\Resources\FacilityResource\RelationManagers;
use App\Models\Facility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Fasilitas';
    protected static ?string $modelLabel = 'Fasilitas';
    protected static ?string $pluralModelLabel = 'Fasilitas';
    protected static ?string $navigationGroup = 'Tampilan Web';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Fasilitas')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Singkat')
                            ->rows(3),
                    ])->columns(1),

                Forms\Components\Section::make('Galeri Multi-Media')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->label('Koleksi Foto')
                            ->image()
                            ->multiple()
                            ->directory('facilities')
                            ->helperText('Anda bisa memilih lebih dari satu foto sekaligus.')
                            ->columnSpanFull(),
                        
                        Forms\Components\Repeater::make('videos')
                            ->label('Tautan Video Youtube')
                            ->schema([
                                Forms\Components\TextInput::make('url')
                                    ->label('URL Youtube')
                                    ->url()
                                    ->placeholder('https://www.youtube.com/watch?v=...')
                                    ->required(),
                            ])
                            ->addActionLabel('Tambah Video')
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Pengaturan Tayang')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif Tampil')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Fasilitas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('order')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListFacilities::route('/'),
            'create' => Pages\CreateFacility::route('/create'),
            'edit' => Pages\EditFacility::route('/{record}/edit'),
        ];
    }
}
