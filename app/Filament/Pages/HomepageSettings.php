<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use App\Support\HomepageContent;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
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
            Section::make('Misi sekolah')->description('Tersedia delapan poin. Judul boleh kosong; poin yang seluruh isinya kosong tidak ditampilkan.')
                ->schema($missions),
        ])->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('loadClientRevision')->label('Muat revisi klien')->color('gray')
                ->action(function (): void {
                    $this->form->fill(HomepageContent::clientRevision());
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
            foreach (array_keys(HomepageContent::clientRevision()) as $key) {
                $group = str_starts_with($key, 'mission_') || in_array($key, ['vision_headline', 'school_motto'], true)
                    ? 'vision' : 'general';
                SiteSetting::set($key, $data[$key] ?? '', $group);
            }
        });

        Notification::make()->title('Konten beranda berhasil disimpan')->success()->send();
    }
}
