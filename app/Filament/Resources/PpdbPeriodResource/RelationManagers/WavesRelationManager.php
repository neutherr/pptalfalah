<?php

namespace App\Filament\Resources\PpdbPeriodResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;


class WavesRelationManager extends RelationManager
{
    protected static string $relationship = 'waves';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Gelombang')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('registration_start')
                    ->label('Mulai Pendaftaran')
                    ->required(),
                Forms\Components\DatePicker::make('registration_end')
                    ->label('Tutup Pendaftaran')
                    ->required(),
                Forms\Components\DatePicker::make('test_date')
                    ->label('Tanggal Seleksi'),
                Forms\Components\DatePicker::make('announcement_date')
                    ->label('Tanggal Pengumuman'),
                Forms\Components\TextInput::make('order')
                    ->label('Urutan Tampil')
                    ->numeric()
                    ->default(1),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif/Dibuka')
                    ->default(true),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('order')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Gelombang')->searchable(),
                Tables\Columns\TextColumn::make('registration_start')
                    ->label('Pendaftaran')
                    ->formatStateUsing(fn ($record) => ($record->registration_start ? $record->registration_start->format('d M Y') : '') . ' - ' . ($record->registration_end ? $record->registration_end->format('d M Y') : '')),
                Tables\Columns\TextColumn::make('test_date')->label('Seleksi')->dateTime('d M Y'),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('order')->label('Urutan')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
