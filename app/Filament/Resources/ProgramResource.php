<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramResource\Pages;
use App\Models\Program;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Konten Utama';
    protected static ?string $navigationLabel = 'Program Unggulan';
    protected static ?string $modelLabel = 'Program';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detail Program')->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Nama Program')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->maxLength(100),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug URL')
                    ->required()
                    ->unique(ignorable: fn ($record) => $record)
                    ->prefix('/program/')
                    ->maxLength(100),

                Forms\Components\TextInput::make('subtitle')
                    ->label('Sub Judul (Opsional)')
                    ->maxLength(100),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi Singkat')
                    ->required()
                    ->rows(3),

                Forms\Components\TextInput::make('icon')
                    ->label('Nama Ikon Material Symbols')
                    ->required()
                    ->default('school')
                    ->helperText('Contoh: menu_book, domain, potted_plant — lihat fonts.google.com/icons'),

                Forms\Components\TextInput::make('icon_bg_color')
                    ->label('Warna Background Ikon (Tailwind class)')
                    ->default('bg-primary-container')
                    ->helperText('Contoh: bg-primary-container, bg-secondary-container, bg-primary'),
            ])->columns(2),

            Forms\Components\Section::make('Gambar & Poin Unggulan')->schema([
                Forms\Components\FileUpload::make('image')
                    ->label('Foto/Gambar Program')
                    ->image()
                    ->directory('programs')
                    ->imagePreviewHeight(200),

                Forms\Components\Repeater::make('bullet_points')
                    ->label('Poin Unggulan (Bullet)')
                    ->schema([
                        Forms\Components\TextInput::make('point')
                            ->label('Poin')
                            ->required()
                            ->maxLength(150),
                    ])
                    ->defaultItems(2)
                    ->maxItems(5)
                    ->reorderable()
                    ->columnSpanFull(),
            ])->columns(1),

            Forms\Components\Section::make('Konten Bacaan (Artikel Detail)')->schema([
                Forms\Components\RichEditor::make('content')
                    ->label('Artikel Program (Tampil di halaman detail saat kartu diklik)')
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('programs/attachments')
                    ->columnSpanFull(),
            ]),

            Forms\Components\Section::make('Pengaturan')->schema([
                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif / Tampilkan')
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
                    ->label('Nama Program')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('icon')
                    ->label('Ikon'),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPrograms::route('/'),
            'create' => Pages\CreateProgram::route('/create'),
            'edit'   => Pages\EditProgram::route('/{record}/edit'),
        ];
    }
}
