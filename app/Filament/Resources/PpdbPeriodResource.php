<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PpdbPeriodResource\Pages;
use App\Filament\Resources\PpdbPeriodResource\RelationManagers;
use App\Models\PpdbPeriod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PpdbPeriodResource extends Resource
{
    protected static ?string $model = PpdbPeriod::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'PPDB';
    protected static ?string $navigationLabel = 'Periode PPDB';
    protected static ?string $modelLabel = 'Periode PPDB';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('academic_year')
                ->label('Tahun Ajaran')
                ->required()
                ->placeholder('2026/2027')
                ->maxLength(20),

            Forms\Components\Toggle::make('is_active')
                ->label('Periode Aktif')
                ->default(false)
                ->helperText('Hanya satu periode yang dapat aktif sekaligus.'),

            Forms\Components\Textarea::make('description')
                ->label('Deskripsi / Catatan Periode')
                ->rows(3)
                ->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('academic_year')
                    ->label('Tahun Ajaran')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('waves_count')
                    ->label('Jumlah Gelombang')
                    ->counts('waves'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ])]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\WavesRelationManager::class,
            RelationManagers\RequirementsRelationManager::class,
            RelationManagers\FeesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPpdbPeriods::route('/'),
            'create' => Pages\CreatePpdbPeriod::route('/create'),
            'edit'   => Pages\EditPpdbPeriod::route('/{record}/edit'),
        ];
    }
}
