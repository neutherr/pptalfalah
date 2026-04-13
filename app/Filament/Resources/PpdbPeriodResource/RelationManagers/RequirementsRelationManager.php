<?php

namespace App\Filament\Resources\PpdbPeriodResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RequirementsRelationManager extends RelationManager
{
    protected static string $relationship = 'requirements';
    protected static ?string $title = 'Syarat Pendaftaran';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Syarat')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            Forms\Components\Textarea::make('description')
                ->label('Keterangan')
                ->rows(3)
                ->columnSpanFull(),

            Forms\Components\TextInput::make('order')
                ->label('Urutan')
                ->numeric()
                ->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('order')->label('No.')->sortable(),
                Tables\Columns\TextColumn::make('title')->label('Syarat')->limit(50),
                Tables\Columns\TextColumn::make('description')->label('Keterangan')->limit(60),
            ])
            ->reorderable('order')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->headerActions([Tables\Actions\CreateAction::make()]);
    }
}
