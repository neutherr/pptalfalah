<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Pengaturan Situs';
    protected static ?string $modelLabel = 'Pengaturan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('group')
                ->label('Grup')
                ->options([
                    'general'  => 'Umum',
                    'contact'  => 'Kontak',
                    'social'   => 'Media Sosial',
                    'seo'      => 'SEO',
                    'vision'   => 'Visi & Misi',
                    'ppdb'     => 'PPDB',
                ])
                ->required()
                ->default('general'),

            Forms\Components\TextInput::make('key')
                ->label('Kunci (Key)')
                ->required()
                ->unique(ignorable: fn ($record) => $record)
                ->maxLength(100),

            Forms\Components\Textarea::make('value')
                ->label('Nilai (Value)')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->label('Grup')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('key')
                    ->label('Kunci')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai')
                    ->limit(60)
                    ->searchable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'general' => 'Umum',
                        'contact' => 'Kontak',
                        'social'  => 'Media Sosial',
                        'seo'     => 'SEO',
                        'vision'  => 'Visi & Misi',
                        'ppdb'    => 'PPDB',
                    ]),
            ])
            ->defaultSort('group')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit'   => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
