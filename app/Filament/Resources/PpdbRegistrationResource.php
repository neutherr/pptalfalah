<?php

namespace App\Filament\Resources;

use App\Filament\Exports\PpdbRegistrationExporter;
use App\Filament\Resources\PpdbRegistrationResource\Pages;
use App\Models\PpdbRegistration;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class PpdbRegistrationResource extends Resource
{
    protected static ?string $model = PpdbRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'PPDB';

    protected static ?string $navigationLabel = 'Pendaftar PPDB';

    protected static ?string $modelLabel = 'Pendaftar PPDB';

    protected static ?string $pluralModelLabel = 'Pendaftar PPDB';

    public static function getNavigationBadge(): ?string
    {
        $count = PpdbRegistration::query()
            ->where('status', PpdbRegistration::STATUS_NEW)
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function contactWhatsappUrl(PpdbRegistration $registration): string
    {
        $phone = preg_replace('/\D/', '', $registration->primary_contact_phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62'.$phone;
        }

        $message = "Assalamu'alaikum Bapak/Ibu.\n\nPendaftaran online ananda {$registration->full_name} dengan nomor {$registration->registration_number} telah ditinjau oleh panitia PPDB PPT Al-Falah.\n\nSilakan datang ke pondok untuk mengikuti tes akademik dan membawa fotokopi Akta Kelahiran dan KK, pas foto 3×4 sebanyak empat lembar, serta fotokopi rapor terakhir.\n\nGratis biaya pendidikan selama satu tahun pertama. Informasi waktu kedatangan dapat ditanyakan dengan membalas pesan ini.";

        return 'https://wa.me/'.$phone.'?text='.urlencode($message);
    }

    public static function correctionFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('full_name')
                ->label('Nama Lengkap')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('school_name')
                ->label('Sekolah Asal')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('primary_contact_phone')
                ->label('Nomor WhatsApp')
                ->tel()
                ->required()
                ->maxLength(20)
                ->rule('regex:/^(?:\\+62|62|0)8[0-9\\s-]{7,16}$/'),
            Forms\Components\Textarea::make('admin_notes')
                ->label('Catatan Admin')
                ->placeholder('Contoh: Janji datang 12 Agustus 2026.')
                ->rows(4)
                ->maxLength(2000)
                ->columnSpanFull(),
        ];
    }

    public static function correctionFormData(PpdbRegistration $registration): array
    {
        return [
            'full_name' => $registration->full_name,
            'school_name' => $registration->school_name,
            'primary_contact_phone' => $registration->primary_contact_phone,
            'admin_notes' => $registration->admin_notes,
        ];
    }

    public static function correctData(PpdbRegistration $registration, array $data): void
    {
        $registration->update([
            'full_name' => trim($data['full_name']),
            'school_name' => trim($data['school_name']),
            'primary_contact_phone' => trim($data['primary_contact_phone']),
            'admin_notes' => filled($data['admin_notes'] ?? null) ? trim($data['admin_notes']) : null,
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Pendaftaran')->schema([
                Infolists\Components\TextEntry::make('registration_number')->label('Nomor Pendaftaran')->copyable(),
                Infolists\Components\TextEntry::make('status')->label('Status')->badge()->formatStateUsing(fn (string $state) => self::statusLabel($state))->color(fn (string $state) => $state === PpdbRegistration::STATUS_REVIEWED ? 'success' : 'warning'),
                Infolists\Components\TextEntry::make('period.academic_year')->label('Tahun Ajaran'),
                Infolists\Components\TextEntry::make('wave.name')->label('Gelombang'),
                Infolists\Components\TextEntry::make('submitted_at')->label('Tanggal Daftar')->dateTime('d M Y, H:i'),
                Infolists\Components\TextEntry::make('admin_notes')->label('Catatan Admin')->placeholder('Belum ada catatan')->columnSpanFull(),
            ])->columns(3),
            Infolists\Components\Section::make('Calon Santri')->schema([
                Infolists\Components\TextEntry::make('full_name')->label('Nama Lengkap'),
                Infolists\Components\TextEntry::make('gender')->label('Jenis Kelamin')->formatStateUsing(fn (string $state) => $state === 'male' ? 'Laki-laki' : 'Perempuan'),
                Infolists\Components\TextEntry::make('nik')->label('NIK')->copyable(),
                Infolists\Components\TextEntry::make('family_card_number')->label('Nomor KK')->copyable()->placeholder('-'),
                Infolists\Components\TextEntry::make('nisn')->label('NISN')->placeholder('-'),
                Infolists\Components\TextEntry::make('birth_place')->label('Tempat Lahir'),
                Infolists\Components\TextEntry::make('birth_date')->label('Tanggal Lahir')->date('d M Y'),
                Infolists\Components\TextEntry::make('address')->label('Alamat')->columnSpanFull(),
                Infolists\Components\TextEntry::make('village_name')->label('Desa/Kelurahan'),
                Infolists\Components\TextEntry::make('subdistrict_name')->label('Kecamatan'),
                Infolists\Components\TextEntry::make('district_city_name')->label('Kabupaten/Kota'),
                Infolists\Components\TextEntry::make('province_name')->label('Provinsi'),
                Infolists\Components\TextEntry::make('postal_code')->label('Kode Pos')->placeholder('-'),
                Infolists\Components\TextEntry::make('student_phone')->label('HP Calon Santri')->placeholder('-'),
            ])->columns(3),
            Infolists\Components\Section::make('Sekolah dan Orang Tua')->schema([
                Infolists\Components\TextEntry::make('school_name')->label('Sekolah Asal'),
                Infolists\Components\TextEntry::make('npsn')->label('NPSN')->placeholder('-'),
                Infolists\Components\TextEntry::make('father_name')->label('Nama Ayah'),
                Infolists\Components\TextEntry::make('father_education')->label('Pendidikan Ayah')->placeholder('-'),
                Infolists\Components\TextEntry::make('father_job')->label('Pekerjaan Ayah')->placeholder('-'),
                Infolists\Components\TextEntry::make('mother_name')->label('Nama Ibu'),
                Infolists\Components\TextEntry::make('mother_education')->label('Pendidikan Ibu')->placeholder('-'),
                Infolists\Components\TextEntry::make('mother_job')->label('Pekerjaan Ibu')->placeholder('-'),
                Infolists\Components\TextEntry::make('primary_contact_relation')->label('Kontak Utama')->formatStateUsing(fn (string $state) => ['father' => 'Ayah', 'mother' => 'Ibu', 'guardian' => 'Wali'][$state] ?? $state),
                Infolists\Components\TextEntry::make('primary_contact_phone')->label('Nomor WhatsApp')->copyable(),
                Infolists\Components\TextEntry::make('guardian_name')->label('Nama Wali')->placeholder('-'),
                Infolists\Components\TextEntry::make('guardian_relationship')->label('Hubungan Wali')->placeholder('-'),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_number')->label('Nomor')->searchable()->sortable()->copyable(),
                Tables\Columns\TextColumn::make('full_name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nik_last4')->label('NIK')->formatStateUsing(fn (string $state) => '•••• •••• •••• '.$state),
                Tables\Columns\TextColumn::make('village_name')->label('Wilayah')->description(fn (PpdbRegistration $record) => $record->subdistrict_name.', '.$record->district_city_name)->searchable(),
                Tables\Columns\TextColumn::make('school_name')->label('Sekolah')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('primary_contact_phone')->label('Kontak Utama')->description(fn (PpdbRegistration $record) => ['father' => 'Ayah', 'mother' => 'Ibu', 'guardian' => 'Wali'][$record->primary_contact_relation] ?? '-')->copyable(),
                Tables\Columns\TextColumn::make('wave.name')->label('Gelombang')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn (string $state) => self::statusLabel($state))->color(fn (string $state) => $state === PpdbRegistration::STATUS_REVIEWED ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('submitted_at')->label('Tanggal Daftar')->dateTime('d M Y, H:i')->sortable(),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('ppdb_period_id')->label('Periode')->relationship('period', 'academic_year'),
                Tables\Filters\SelectFilter::make('ppdb_wave_id')->label('Gelombang')->relationship('wave', 'name'),
                Tables\Filters\SelectFilter::make('status')->options([
                    PpdbRegistration::STATUS_NEW => 'Baru',
                    PpdbRegistration::STATUS_REVIEWED => 'Sudah Ditinjau',
                ]),
                Tables\Filters\TernaryFilter::make('retention')
                    ->label('Layak Dihapus')
                    ->placeholder('Semua data')
                    ->trueLabel('Sudah lewat 1 tahun')
                    ->falseLabel('Belum lewat 1 tahun')
                    ->queries(
                        true: fn (Builder $query) => self::eligibleForDeletion($query),
                        false: fn (Builder $query) => $query->whereHas('period.waves', fn (Builder $wave) => $wave->whereDate('registration_end', '>', today()->subYear())),
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('correctData')
                    ->label('Koreksi Data')
                    ->icon('heroicon-o-pencil-square')
                    ->fillForm(fn (PpdbRegistration $record) => self::correctionFormData($record))
                    ->form(self::correctionFormSchema())
                    ->action(fn (PpdbRegistration $record, array $data) => self::correctData($record, $data)),
                Tables\Actions\Action::make('markReviewed')
                    ->label('Tandai Ditinjau')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (PpdbRegistration $record) => $record->status === PpdbRegistration::STATUS_NEW)
                    ->action(fn (PpdbRegistration $record) => $record->update(['status' => PpdbRegistration::STATUS_REVIEWED, 'reviewed_at' => now()])),
                Tables\Actions\Action::make('contactWhatsapp')
                    ->label('Hubungi via WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn (PpdbRegistration $record) => self::contactWhatsappUrl($record))
                    ->openUrlInNewTab()
                    ->visible(fn (PpdbRegistration $record) => $record->status === PpdbRegistration::STATUS_REVIEWED),
                Tables\Actions\Action::make('downloadPhoto')
                    ->label('Unduh Foto')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn (PpdbRegistration $record) => Storage::disk('local')->download($record->photo_path, $record->registration_number.'-foto.'.pathinfo($record->photo_path, PATHINFO_EXTENSION))),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->label('Ekspor Hasil Filter')
                    ->exporter(PpdbRegistrationExporter::class)
                    ->formats([ExportFormat::Csv, ExportFormat::Xlsx]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make()
                        ->label('Ekspor Pilihan')
                        ->exporter(PpdbRegistrationExporter::class)
                        ->formats([ExportFormat::Csv, ExportFormat::Xlsx]),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function eligibleForDeletion(Builder $query): Builder
    {
        return $query
            ->whereHas('period.waves')
            ->whereDoesntHave('period.waves', fn (Builder $wave) => $wave->whereDate('registration_end', '>', today()->subYear()));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPpdbRegistrations::route('/'),
            'view' => Pages\ViewPpdbRegistration::route('/{record}'),
        ];
    }

    private static function statusLabel(string $status): string
    {
        return $status === PpdbRegistration::STATUS_REVIEWED ? 'Sudah Ditinjau' : 'Baru';
    }
}
