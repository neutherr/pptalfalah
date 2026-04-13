<?php

namespace App\Filament\Resources\PpdbPeriodResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FeesRelationManager extends RelationManager
{
    protected static string $relationship = 'fees';
    protected static ?string $title = 'Rincian Biaya';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Biaya')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('amount')
                ->label('Jumlah (Rp)')
                ->numeric()
                ->required()
                ->prefix('Rp'),

            Forms\Components\Textarea::make('notes')
                ->label('Catatan')
                ->rows(2)
                ->columnSpanFull(),

            Forms\Components\TextInput::make('order')
                ->label('Urutan')
                ->numeric()
                ->default(0),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('order')->label('No.')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Biaya'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('notes')->label('Catatan')->limit(40),
            ])
            ->reorderable('order')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->headerActions([Tables\Actions\CreateAction::make()]);
    }
}
