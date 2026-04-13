<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DownloadFileResource\Pages;
use App\Models\DownloadFile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DownloadFileResource extends Resource
{
    protected static ?string $model = DownloadFile::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'File Unduhan';
    protected static ?string $modelLabel = 'File Unduhan';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama File')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('category')
                ->label('Kategori')
                ->options([
                    'brosur'   => 'Brosur',
                    'formulir' => 'Formulir',
                    'lainnya'  => 'Lainnya',
                ])
                ->default('brosur')
                ->required(),

            Forms\Components\TextInput::make('description')
                ->label('Deskripsi Singkat')
                ->maxLength(255)
                ->columnSpanFull(),

            Forms\Components\FileUpload::make('file_path')
                ->label('Upload File (PDF/Dokumen)')
                ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                ->directory('downloads')
                ->downloadable()
                ->required()
                ->columnSpanFull(),

            Forms\Components\Toggle::make('is_active')
                ->label('Aktif / Tampilkan')
                ->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'brosur'   => 'success',
                        'formulir' => 'warning',
                        default    => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('download_count')
                    ->label('Diunduh')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDownloadFiles::route('/'),
            'create' => Pages\CreateDownloadFile::route('/create'),
            'edit'   => Pages\EditDownloadFile::route('/{record}/edit'),
        ];
    }
}
