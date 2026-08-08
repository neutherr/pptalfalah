<?php

namespace App\Livewire\Ppdb;

use App\Models\PpdbPeriod;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class RegistrationWizard extends Component
{
    use WithFileUploads;

    public int $step = 1;

    public int $waveId;

    public string $fullName = '';

    public string $gender = '';

    public string $nik = '';

    public string $familyCardNumber = '';

    public string $nisn = '';

    public string $birthPlace = '';

    public string $birthDate = '';

    public string $address = '';

    public string $provinceCode = '';

    public string $districtCityCode = '';

    public string $subdistrictCode = '';

    public string $villageCode = '';

    public string $postalCode = '';

    public string $studentPhone = '';

    public $photo;

    public string $schoolName = '';

    public string $npsn = '';

    public string $fatherName = '';

    public string $fatherEducation = '';

    public string $fatherJob = '';

    public string $motherName = '';

    public string $motherEducation = '';

    public string $motherJob = '';

    public string $primaryContactRelation = '';

    public string $primaryContactPhone = '';

    public string $guardianName = '';

    public string $guardianRelationship = '';

    public string $guardianEducation = '';

    public string $guardianJob = '';

    public bool $accuracyAccepted = false;

    public bool $privacyAccepted = false;

    public string $website = '';

    public function mount(?PpdbWave $wave = null): void
    {
        if (! $wave?->exists) {
            $wave = PpdbPeriod::currentOpenWave();
        }

        abort_unless($wave, 404);
        $this->waveId = $wave->id;
    }

    #[Computed]
    public function provinces()
    {
        return DB::table('master_area_province')->orderBy('name')->get();
    }

    #[Computed]
    public function districts()
    {
        return DB::table('master_area_district_city')
            ->where('province_code', $this->provinceCode)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function subdistricts()
    {
        return DB::table('master_area_subdistrict')
            ->where('province_code', $this->provinceCode)
            ->where('district_city_code', $this->districtCityCode)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function villages()
    {
        return DB::table('master_area_village_subdistrict')
            ->where('province_code', $this->provinceCode)
            ->where('district_city_code', $this->districtCityCode)
            ->where('subdistrict_code', $this->subdistrictCode)
            ->orderBy('name')
            ->get();
    }

    public function updatedProvinceCode(): void
    {
        $this->reset('districtCityCode', 'subdistrictCode', 'villageCode');
    }

    public function updatedDistrictCityCode(): void
    {
        $this->reset('subdistrictCode', 'villageCode');
    }

    public function updatedSubdistrictCode(): void
    {
        $this->reset('villageCode');
    }

    public function nextStep(): void
    {
        $this->validate($this->rulesForStep($this->step), $this->messages(), $this->validationAttributes());

        if ($this->step === 1 && ! $this->validateAreaChain()) {
            return;
        }

        $this->step = min(4, $this->step + 1);
    }

    public function previousStep(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function goToStep(int $step): void
    {
        if ($step < $this->step) {
            $this->step = max(1, min(4, $step));
        }
    }

    public function submit()
    {
        if (filled($this->website)) {
            $this->addError('website', 'Pendaftaran tidak dapat diproses.');

            return null;
        }

        if (! $this->validateAllSteps()) {
            return null;
        }

        if (! RateLimiter::attempt('ppdb-registration:'.request()->ip(), 5, fn () => true, 3600)) {
            $this->addError('registration', 'Terlalu banyak percobaan pendaftaran. Silakan coba kembali satu jam lagi.');

            return null;
        }

        $wave = PpdbPeriod::currentOpenWave();

        if (! $wave || $wave->id !== $this->waveId) {
            $this->addError('registration', 'Gelombang pendaftaran sudah ditutup. Data belum disimpan.');

            return null;
        }

        $area = $this->areaNames();
        $photoPath = $this->photo->store('ppdb/photos', 'local');

        try {
            $registration = DB::transaction(function () use ($wave, $area, $photoPath) {
                $registration = PpdbRegistration::create([
                    'ppdb_period_id' => $wave->ppdb_period_id,
                    'ppdb_wave_id' => $wave->id,
                    'registration_number' => 'pending-'.Str::uuid(),
                    'public_token' => (string) Str::uuid(),
                    'status' => PpdbRegistration::STATUS_NEW,
                    'full_name' => trim($this->fullName),
                    'gender' => $this->gender,
                    'nik' => $this->nik,
                    'family_card_number' => $this->familyCardNumber ?: null,
                    'nisn' => $this->nisn ?: null,
                    'birth_place' => trim($this->birthPlace),
                    'birth_date' => $this->birthDate,
                    'address' => trim($this->address),
                    'province_code' => $this->provinceCode,
                    'province_name' => $area['province'],
                    'district_city_code' => $this->districtCityCode,
                    'district_city_name' => $area['district'],
                    'subdistrict_code' => $this->subdistrictCode,
                    'subdistrict_name' => $area['subdistrict'],
                    'village_code' => $this->villageCode,
                    'village_name' => $area['village'],
                    'postal_code' => $this->postalCode ?: null,
                    'student_phone' => $this->studentPhone ?: null,
                    'photo_path' => $photoPath,
                    'school_name' => trim($this->schoolName),
                    'npsn' => $this->npsn ?: null,
                    'father_name' => trim($this->fatherName),
                    'father_education' => $this->fatherEducation ?: null,
                    'father_job' => $this->fatherJob ?: null,
                    'mother_name' => trim($this->motherName),
                    'mother_education' => $this->motherEducation ?: null,
                    'mother_job' => $this->motherJob ?: null,
                    'primary_contact_relation' => $this->primaryContactRelation,
                    'primary_contact_phone' => $this->primaryContactPhone,
                    'guardian_name' => $this->primaryContactRelation === 'guardian' ? trim($this->guardianName) : null,
                    'guardian_relationship' => $this->primaryContactRelation === 'guardian' ? trim($this->guardianRelationship) : null,
                    'guardian_education' => $this->primaryContactRelation === 'guardian' ? ($this->guardianEducation ?: null) : null,
                    'guardian_job' => $this->primaryContactRelation === 'guardian' ? ($this->guardianJob ?: null) : null,
                    'accuracy_accepted_at' => now(),
                    'privacy_accepted_at' => now(),
                    'submitted_at' => now(),
                ]);

                $academicCode = preg_replace('/\D/', '', $wave->period->academic_year);
                $registration->update([
                    'registration_number' => 'PPDB-'.substr($academicCode, 2, 2).substr($academicCode, -2).'-'.str_pad((string) $registration->id, 6, '0', STR_PAD_LEFT),
                ]);

                return $registration;
            });
        } catch (QueryException $exception) {
            Storage::disk('local')->delete($photoPath);

            if (PpdbRegistration::query()->where('ppdb_period_id', $wave->ppdb_period_id)->where('nik_hash', hash('sha256', $this->nik))->exists()) {
                $this->step = 1;
                $this->addError('nik', 'NIK ini sudah terdaftar pada periode yang sama.');

                return null;
            }

            throw $exception;
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($photoPath);

            throw $exception;
        }

        return $this->redirectRoute('ppdb.proof', ['token' => $registration->public_token]);
    }

    private function validateAllSteps(): bool
    {
        $this->resetErrorBag();

        foreach (range(1, 4) as $step) {
            $validator = validator($this->formData(), $this->rulesForStep($step), $this->messages())
                ->setAttributeNames($this->validationAttributes());

            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $field => $messages) {
                    foreach ($messages as $message) {
                        $this->addError($field, $message);
                    }
                }
            }
        }

        if (! $this->validateAreaChain()) {
            $this->step = 1;

            return false;
        }

        if ($this->getErrorBag()->isNotEmpty()) {
            $this->step = $this->firstInvalidStep();

            return false;
        }

        return true;
    }

    private function validateAreaChain(): bool
    {
        $checks = [
            'provinceCode' => DB::table('master_area_province')->where('code', $this->provinceCode)->exists(),
            'districtCityCode' => DB::table('master_area_district_city')->where(['province_code' => $this->provinceCode, 'code' => $this->districtCityCode])->exists(),
            'subdistrictCode' => DB::table('master_area_subdistrict')->where(['province_code' => $this->provinceCode, 'district_city_code' => $this->districtCityCode, 'code' => $this->subdistrictCode])->exists(),
            'villageCode' => DB::table('master_area_village_subdistrict')->where(['province_code' => $this->provinceCode, 'district_city_code' => $this->districtCityCode, 'subdistrict_code' => $this->subdistrictCode, 'code' => $this->villageCode])->exists(),
        ];

        foreach ($checks as $field => $valid) {
            if (! $valid) {
                $this->addError($field, 'Wilayah yang dipilih tidak valid.');

                return false;
            }
        }

        return true;
    }

    private function areaNames(): array
    {
        return [
            'province' => DB::table('master_area_province')->where('code', $this->provinceCode)->value('name'),
            'district' => DB::table('master_area_district_city')->where(['province_code' => $this->provinceCode, 'code' => $this->districtCityCode])->value('name'),
            'subdistrict' => DB::table('master_area_subdistrict')->where(['province_code' => $this->provinceCode, 'district_city_code' => $this->districtCityCode, 'code' => $this->subdistrictCode])->value('name'),
            'village' => DB::table('master_area_village_subdistrict')->where(['province_code' => $this->provinceCode, 'district_city_code' => $this->districtCityCode, 'subdistrict_code' => $this->subdistrictCode, 'code' => $this->villageCode])->value('name'),
        ];
    }

    private function firstInvalidStep(): int
    {
        foreach (range(1, 4) as $step) {
            if (collect(array_keys($this->rulesForStep($step)))->contains(fn ($field) => $this->getErrorBag()->has($field))) {
                return $step;
            }
        }

        return 1;
    }

    private function formData(): array
    {
        return collect(array_keys(array_merge(...array_map(fn ($step) => $this->rulesForStep($step), range(1, 4)))))
            ->mapWithKeys(fn ($field) => [$field => $this->{$field}])
            ->all();
    }

    private function rulesForStep(int $step): array
    {
        return match ($step) {
            1 => [
                'fullName' => ['required', 'string', 'max:150'],
                'gender' => ['required', Rule::in(['male', 'female'])],
                'nik' => ['required', 'digits:16'],
                'familyCardNumber' => ['nullable', 'digits:16'],
                'nisn' => ['nullable', 'digits:10'],
                'birthPlace' => ['required', 'string', 'max:100'],
                'birthDate' => ['required', 'date', 'before:today'],
                'address' => ['required', 'string', 'max:1000'],
                'provinceCode' => ['required', 'string', 'size:2'],
                'districtCityCode' => ['required', 'string', 'size:2'],
                'subdistrictCode' => ['required', 'string', 'size:3'],
                'villageCode' => ['required', 'string', 'size:3'],
                'postalCode' => ['nullable', 'digits:5'],
                'studentPhone' => ['nullable', 'regex:/^[0-9+\-\s]{9,20}$/'],
                'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ],
            2 => ['schoolName' => ['required', 'string', 'max:150'], 'npsn' => ['nullable', 'digits:8']],
            3 => [
                'fatherName' => ['required', 'string', 'max:150'],
                'fatherEducation' => ['nullable', 'string', 'max:100'],
                'fatherJob' => ['nullable', 'string', 'max:100'],
                'motherName' => ['required', 'string', 'max:150'],
                'motherEducation' => ['nullable', 'string', 'max:100'],
                'motherJob' => ['nullable', 'string', 'max:100'],
                'primaryContactRelation' => ['required', Rule::in(['father', 'mother', 'guardian'])],
                'primaryContactPhone' => ['required', 'regex:/^[0-9+\-\s]{9,20}$/'],
                'guardianName' => ['required_if:primaryContactRelation,guardian', 'nullable', 'string', 'max:150'],
                'guardianRelationship' => ['required_if:primaryContactRelation,guardian', 'nullable', 'string', 'max:100'],
                'guardianEducation' => ['nullable', 'string', 'max:100'],
                'guardianJob' => ['nullable', 'string', 'max:100'],
            ],
            4 => ['accuracyAccepted' => ['accepted'], 'privacyAccepted' => ['accepted']],
            default => [],
        };
    }

    private function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'required_if' => ':attribute wajib diisi.',
            'digits' => ':attribute harus :digits digit.',
            'accepted' => ':attribute wajib disetujui.',
            'photo.max' => 'Foto maksimal 2 MB.',
            'photo.mimes' => 'Foto harus berformat JPG, PNG, atau WebP.',
            'photo.image' => 'File foto tidak valid.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'fullName' => 'Nama lengkap',
            'gender' => 'Jenis kelamin',
            'nik' => 'NIK',
            'familyCardNumber' => 'Nomor KK',
            'nisn' => 'NISN',
            'birthPlace' => 'Tempat lahir',
            'birthDate' => 'Tanggal lahir',
            'address' => 'Alamat lengkap',
            'provinceCode' => 'Provinsi',
            'districtCityCode' => 'Kabupaten/Kota',
            'subdistrictCode' => 'Kecamatan',
            'villageCode' => 'Kelurahan/Desa',
            'postalCode' => 'Kode pos',
            'studentPhone' => 'Nomor HP calon santri',
            'photo' => 'Foto formal',
            'schoolName' => 'Nama sekolah asal',
            'npsn' => 'NPSN',
            'fatherName' => 'Nama ayah',
            'fatherEducation' => 'Pendidikan ayah',
            'fatherJob' => 'Pekerjaan ayah',
            'motherName' => 'Nama ibu',
            'motherEducation' => 'Pendidikan ibu',
            'motherJob' => 'Pekerjaan ibu',
            'primaryContactRelation' => 'Kontak utama',
            'primaryContactPhone' => 'Nomor WhatsApp',
            'guardianName' => 'Nama wali',
            'guardianRelationship' => 'Hubungan wali',
            'guardianEducation' => 'Pendidikan wali',
            'guardianJob' => 'Pekerjaan wali',
            'accuracyAccepted' => 'Pernyataan kebenaran data',
            'privacyAccepted' => 'Persetujuan kebijakan privasi',
        ];
    }

    public function render()
    {
        return view('livewire.ppdb.registration-wizard', [
            'wave' => PpdbWave::with('period')->findOrFail($this->waveId),
            'areaNames' => $this->validAreaSelection() ? $this->areaNames() : [],
        ]);
    }

    private function validAreaSelection(): bool
    {
        return filled($this->provinceCode)
            && filled($this->districtCityCode)
            && filled($this->subdistrictCode)
            && filled($this->villageCode)
            && DB::table('master_area_village_subdistrict')->where([
                'province_code' => $this->provinceCode,
                'district_city_code' => $this->districtCityCode,
                'subdistrict_code' => $this->subdistrictCode,
                'code' => $this->villageCode,
            ])->exists();
    }
}
