<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Informasi';
    protected static ?string $navigationLabel = 'Berita & Artikel';
    protected static ?string $modelLabel = 'Artikel';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Artikel')->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul Artikel')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug URL')
                    ->required()
                    ->unique(ignorable: fn ($record) => $record)
                    ->prefix('/berita/'),

                Forms\Components\Select::make('article_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\TextInput::make('slug')->required(),
                        Forms\Components\ColorPicker::make('color'),
                    ])
                    ->preload(),

                Forms\Components\Select::make('user_id')
                    ->label('Penulis')
                    ->relationship('author', 'name')
                    ->default(auth()->id())
                    ->preload(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft'     => 'Draft',
                        'published' => 'Terbit',
                    ])
                    ->default('draft')
                    ->required(),

                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Tanggal Terbit')
                    ->default(now()),

                Forms\Components\Toggle::make('is_featured')
                    ->label('Artikel Unggulan')
                    ->default(false),
            ])->columns(2),

            Forms\Components\Section::make('Konten')->schema([
                Forms\Components\Textarea::make('excerpt')
                    ->label('Ringkasan (Excerpt)')
                    ->rows(3)
                    ->maxLength(300)
                    ->columnSpanFull(),

                Forms\Components\RichEditor::make('content')
                    ->label('Isi Artikel')
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('articles/attachments')
                    ->required()
                    ->columnSpanFull(),
            ]),

            Forms\Components\Section::make('Gambar & SEO')->schema([
                Forms\Components\FileUpload::make('featured_image')
                    ->label('Gambar Utama')
                    ->image()
                    ->directory('articles/images')
                    ->imagePreviewHeight(200),

                Forms\Components\FileUpload::make('og_image')
                    ->label('OG Image')
                    ->image()
                    ->directory('articles/og'),

                Forms\Components\TextInput::make('meta_title')
                    ->label('Meta Title')
                    ->maxLength(70),

                Forms\Components\Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(2)
                    ->maxLength(160),
            ])->columns(2)->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->height(50)
                    ->circular(false),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(35)
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn ($record) => $record->category?->color ?? 'primary'),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Penulis')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        default     => 'warning',
                    }),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Terbit')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['draft' => 'Draft', 'published' => 'Terbit']),

                Tables\Filters\SelectFilter::make('article_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
            ])
            ->defaultSort('published_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit'   => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
