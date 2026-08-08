<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PpdbRegistration extends Model
{
    public const STATUS_NEW = 'new';

    public const STATUS_REVIEWED = 'reviewed';

    protected $fillable = [
        'ppdb_period_id', 'ppdb_wave_id', 'registration_number', 'public_token', 'status',
        'full_name', 'gender', 'nik', 'family_card_number', 'nisn', 'birth_place', 'birth_date', 'address',
        'province_code', 'province_name', 'district_city_code', 'district_city_name',
        'subdistrict_code', 'subdistrict_name', 'village_code', 'village_name',
        'postal_code', 'student_phone', 'photo_path', 'school_name', 'npsn',
        'father_name', 'father_education', 'father_job', 'mother_name', 'mother_education',
        'mother_job', 'primary_contact_relation', 'primary_contact_phone', 'guardian_name',
        'guardian_relationship', 'guardian_education', 'guardian_job', 'accuracy_accepted_at',
        'privacy_accepted_at', 'submitted_at', 'reviewed_at', 'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'nik' => 'encrypted',
            'family_card_number' => 'encrypted',
            'nisn' => 'encrypted',
            'birth_date' => 'date',
            'accuracy_accepted_at' => 'datetime',
            'privacy_accepted_at' => 'datetime',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $registration) {
            if ($registration->isDirty('nik')) {
                $nik = preg_replace('/\D/', '', $registration->nik);
                $registration->nik = $nik;
                $registration->nik_hash = hash('sha256', $nik);
                $registration->nik_last4 = substr($nik, -4);
            }

            if ($registration->isDirty('nisn') && filled($registration->nisn)) {
                $registration->nisn = preg_replace('/\D/', '', $registration->nisn);
            }

            if ($registration->isDirty('family_card_number') && filled($registration->family_card_number)) {
                $registration->family_card_number = preg_replace('/\D/', '', $registration->family_card_number);
            }
        });

        static::deleting(function (self $registration) {
            if ($registration->photo_path) {
                Storage::disk('local')->delete($registration->photo_path);
            }
        });
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(PpdbPeriod::class, 'ppdb_period_id');
    }

    public function wave(): BelongsTo
    {
        return $this->belongsTo(PpdbWave::class, 'ppdb_wave_id');
    }

    public function getMaskedNikAttribute(): string
    {
        return '************'.$this->nik_last4;
    }
}
