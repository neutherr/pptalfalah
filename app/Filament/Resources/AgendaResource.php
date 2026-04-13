<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgendaResource\Pages;
use App\Models\Agenda;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AgendaResource extends Resource
{
    protected static ?string $model = Agenda::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Informasi';
    protected static ?string $navigationLabel = 'Agenda';
    protected static ?string $modelLabel = 'Agenda';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detail Agenda')->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul Agenda')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug URL')
                    ->required()
                    ->unique(ignorable: fn ($record) => $record),

                Forms\Components\TextInput::make('location')
                    ->label('Lokasi'),

                Forms\Components\DateTimePicker::make('start_datetime')
                    ->label('Mulai')
                    ->required(),

                Forms\Components\DateTimePicker::make('end_datetime')
                    ->label('Selesai'),

                Forms\Components\TextInput::make('organizer')
                    ->label('Penyelenggara'),

                Forms\Components\FileUpload::make('image')
                    ->label('Gambar / Poster')
                    ->image()
                    ->directory('agendas'),

                Forms\Components\Toggle::make('is_published')
                    ->label('Terbitkan')
                    ->default(true),
            ])->columns(2),

            Forms\Components\Section::make('Deskripsi')->schema([
                Forms\Components\RichEditor::make('description')
                    ->label('Deskripsi Agenda')
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
                    ->limit(35),

                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi'),

                Tables\Columns\TextColumn::make('start_datetime')
                    ->label('Mulai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Terbit')
                    ->boolean(),
            ])
            ->defaultSort('start_datetime', 'desc')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAgendas::route('/'),
            'create' => Pages\CreateAgenda::route('/create'),
            'edit'   => Pages\EditAgenda::route('/{record}/edit'),
        ];
    }
}
