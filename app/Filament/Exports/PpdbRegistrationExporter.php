<?php

namespace App\Filament\Exports;

use App\Models\PpdbRegistration;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PpdbRegistrationExporter extends Exporter
{
    protected static ?string $model = PpdbRegistration::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('registration_number')->label('Nomor Pendaftaran'),
            ExportColumn::make('full_name')->label('Nama Lengkap'),
            ExportColumn::make('gender')->label('Jenis Kelamin')->formatStateUsing(fn (string $state) => $state === 'male' ? 'Laki-laki' : 'Perempuan'),
            ExportColumn::make('nik')->label('NIK'),
            ExportColumn::make('nik_last4')->label('4 Digit Terakhir NIK'),
            ExportColumn::make('family_card_number')->label('Nomor KK'),
            ExportColumn::make('nisn')->label('NISN'),
            ExportColumn::make('birth_place')->label('Tempat Lahir'),
            ExportColumn::make('birth_date')->label('Tanggal Lahir'),
            ExportColumn::make('address')->label('Alamat'),
            ExportColumn::make('village_name')->label('Desa/Kelurahan'),
            ExportColumn::make('subdistrict_name')->label('Kecamatan'),
            ExportColumn::make('district_city_name')->label('Kabupaten/Kota'),
            ExportColumn::make('province_name')->label('Provinsi'),
            ExportColumn::make('postal_code')->label('Kode Pos'),
            ExportColumn::make('student_phone')->label('HP Calon Santri'),
            ExportColumn::make('school_name')->label('Sekolah Asal'),
            ExportColumn::make('npsn')->label('NPSN'),
            ExportColumn::make('father_name')->label('Nama Ayah'),
            ExportColumn::make('father_education')->label('Pendidikan Ayah'),
            ExportColumn::make('father_job')->label('Pekerjaan Ayah'),
            ExportColumn::make('mother_name')->label('Nama Ibu'),
            ExportColumn::make('mother_education')->label('Pendidikan Ibu'),
            ExportColumn::make('mother_job')->label('Pekerjaan Ibu'),
            ExportColumn::make('primary_contact_relation')->label('Kontak Utama'),
            ExportColumn::make('primary_contact_phone')->label('Nomor WhatsApp'),
            ExportColumn::make('guardian_name')->label('Nama Wali'),
            ExportColumn::make('guardian_relationship')->label('Hubungan Wali'),
            ExportColumn::make('period.academic_year')->label('Tahun Ajaran'),
            ExportColumn::make('wave.name')->label('Gelombang'),
            ExportColumn::make('status')->label('Status')->formatStateUsing(fn (string $state) => $state === PpdbRegistration::STATUS_REVIEWED ? 'Sudah Ditinjau' : 'Baru'),
            ExportColumn::make('submitted_at')->label('Tanggal Daftar'),
        ];
    }

    public function getJobConnection(): ?string
    {
        return 'sync';
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return "Ekspor selesai. {$export->successful_rows} data pendaftar berhasil diekspor.";
    }
}
