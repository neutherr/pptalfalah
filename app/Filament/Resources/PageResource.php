<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Konten Utama';
    protected static ?string $navigationLabel = 'Halaman Statis';
    protected static ?string $modelLabel = 'Halaman';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Halaman')->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul Halaman')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug URL')
                    ->required()
                    ->unique(ignorable: fn ($record) => $record)
                    ->prefix('/'),

                Forms\Components\Select::make('template')
                    ->label('Template')
                    ->options([
                        'default'   => 'Default',
                        'profil'    => 'Profil',
                        'program'   => 'Program',
                        'fasilitas' => 'Fasilitas',
                        'faq'       => 'FAQ',
                    ])
                    ->default('default'),

                Forms\Components\Toggle::make('is_published')
                    ->label('Terbitkan')
                    ->default(true),
            ])->columns(2),

            Forms\Components\Section::make('Konten')->schema([
                Forms\Components\RichEditor::make('content')
                    ->label('Konten Halaman')
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('pages/attachments')
                    ->columnSpanFull(),
            ]),

            Forms\Components\Section::make('SEO & Meta')->schema([
                Forms\Components\TextInput::make('meta_title')
                    ->label('Meta Title')
                    ->maxLength(70),

                Forms\Components\Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(2)
                    ->maxLength(160),

                Forms\Components\FileUpload::make('og_image')
                    ->label('OG Image (Sosmed)')
                    ->image()
                    ->directory('pages/og'),
            ])->columns(2)->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->prefix('/')
                    ->searchable(),

                Tables\Columns\TextColumn::make('template')
                    ->label('Template')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Terbit')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit'   => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
