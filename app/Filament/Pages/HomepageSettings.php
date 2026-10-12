<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use App\Support\HomepageContent;
use App\Support\HomepageMedia;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class HomepageSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Konten Utama';

    protected static ?string $title = 'Konten Beranda';

    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.pages.homepage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(HomepageContent::fromSettings(SiteSetting::pluck('value', 'key')->all()));
    }

    public function form(Form $form): Form
    {
        $missions = [];
        for ($i = 1; $i <= HomepageContent::MISSION_COUNT; $i++) {
            $missions[] = Section::make("Misi {$i}")->schema([
                TextInput::make("mission_{$i}_title")->label('Judul (opsional)')->maxLength(255),
                Textarea::make("mission_{$i}_desc")->label('Isi misi')->rows(3)->maxLength(3000),
            ]);
        }

        return $form->schema([
            Section::make('Bagian 1 — Judul utama')->description('Teks besar di atas foto beranda. Terpisah dari visi sekolah.')->schema([
                Textarea::make('hero_title')->label('Judul hero')->required()->rows(4)->maxLength(1000)
                    ->helperText('Gunakan Enter untuk mengatur pergantian baris. Tidak perlu kode HTML.'),
            ]),
            Section::make('Bagian 2 — Teks pendukung')->schema([
                Textarea::make('site_tagline')->label('Baris pertama / tagline')->required()->rows(2)->maxLength(1000),
                Textarea::make('hero_description')->label('Baris kedua / deskripsi sekolah')->rows(2)->maxLength(1000),
            ]),
            Section::make('Visi dan Moto')->schema([
                Textarea::make('vision_headline')->label('Visi sekolah')->required()->rows(5)->maxLength(3000),
                Textarea::make('school_motto')->label('Moto sekolah')->rows(2)->maxLength(1000)
                    ->helperText('Ditampilkan di bawah visi. Kosongkan untuk menyembunyikan.'),
            ]),
            Section::make('Media Who We Are')->description('Gambar atau video di samping pengenalan sekolah.')->schema([
                Select::make('about_media_type')->label('Tampilkan media')
                    ->options(['image' => 'Gambar', 'video' => 'Video'])->required()->in(['image', 'video'])->live(),
                FileUpload::make('about_image')->label('Gambar / sampul video (opsional)')
                    ->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(2048)->disk('public')->visibility('public')->directory('homepage')
                    ->helperText('JPG, PNG, atau WebP, maksimal 2 MB. Kosongkan untuk memakai foto tanaman bawaan. Untuk video MP4/WebM, gambar ini menjadi sampul.'),
                TextInput::make('about_video_url')->label('Tautan video')->maxLength(2048)
                    ->visible(fn (Get $get): bool => $get('about_media_type') === 'video')
                    ->required(fn (Get $get): bool => $get('about_media_type') === 'video')
                    ->helperText('Tautan YouTube atau URL HTTPS langsung ke file .mp4 / .webm. Video diputar saat pengunjung menekan tombol putar.')
                    ->rules([fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                        if (HomepageMedia::video((string) $value) === null) {
                            $fail('Gunakan tautan YouTube yang valid atau URL HTTPS file MP4/WebM.');
                        }
                    }]),
            ]),
            Section::make('Misi sekolah')->description('Tersedia delapan poin. Judul boleh kosong; poin yang seluruh isinya kosong tidak ditampilkan.')
                ->schema($missions),
        ])->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('loadClientRevision')->label('Muat revisi klien')->color('gray')
                ->action(function (): void {
                    // Update text fields only, preserving pending uploads and selected media.
                    foreach (HomepageContent::clientRevision() as $key => $value) {
                        $this->data[$key] = $value;
                    }
                    Notification::make()->title('Revisi dimuat ke formulir')
                        ->body('Periksa isinya, lalu klik Simpan perubahan untuk menerapkan ke website.')
                        ->info()->send();
                }),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        DB::transaction(function () use ($data): void {
            foreach (array_keys(HomepageContent::clientRevision() + HomepageContent::MEDIA_DEFAULTS) as $key) {
                // Hidden fields retain their stored value when switching media modes.
                if (! array_key_exists($key, $data)) {
                    continue;
                }
                $group = str_starts_with($key, 'mission_') || in_array($key, ['vision_headline', 'school_motto'], true)
                    ? 'vision' : 'general';
                SiteSetting::set($key, $data[$key] ?? '', $group);
            }
        });

        Notification::make()->title('Konten beranda berhasil disimpan')->success()->send();
    }
}
