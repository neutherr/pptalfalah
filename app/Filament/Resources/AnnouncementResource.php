<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = 'Informasi';
    protected static ?string $navigationLabel = 'Pengumuman';
    protected static ?string $modelLabel = 'Pengumuman';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detail Pengumuman')->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul Pengumuman')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug URL')
                    ->required()
                    ->unique(ignorable: fn ($record) => $record),

                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Tanggal Terbit')
                    ->default(now()),

                Forms\Components\Toggle::make('is_pinned')
                    ->label('Sematkan (Pin)')
                    ->default(false),

                Forms\Components\Toggle::make('is_published')
                    ->label('Terbitkan')
                    ->default(true),

                Forms\Components\FileUpload::make('attachment')
                    ->label('Lampiran (PDF/Dok)')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->directory('announcements/attachments'),
            ])->columns(2),

            Forms\Components\Section::make('Isi Pengumuman')->schema([
                Forms\Components\RichEditor::make('content')
                    ->label('Konten')
                    ->required()
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\IconColumn::make('is_pinned')
                    ->label('Pin')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Terbit')
                    ->boolean(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Tanggal Terbit')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit'   => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
