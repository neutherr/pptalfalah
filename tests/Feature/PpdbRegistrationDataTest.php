<?php

namespace Tests\Feature;

use App\Models\PpdbPeriod;
use App\Models\PpdbRegistration;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PpdbRegistrationDataTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_area_reference_data_is_imported_with_the_expected_hierarchy(): void
    {
        $this->assertSame(38, DB::table('master_area_province')->count());
        $this->assertSame(84210, DB::table('master_area_village_subdistrict')->count());
        $this->assertSame('JAWA BARAT', DB::table('master_area_province')->where('code', '32')->value('name'));
        $this->assertSame('KAB. BOGOR', DB::table('master_area_district_city')->where([
            'province_code' => '32',
            'code' => '01',
        ])->value('name'));
    }

    public function test_open_registration_wave_returns_the_first_ordered_wave(): void
    {
        Carbon::setTestNow('2026-08-08 09:00:00');

        $period = PpdbPeriod::create([
            'academic_year' => '2026/2027',
            'is_active' => true,
        ]);
        $period->waves()->createMany([
            [
                'name' => 'Gelombang 2',
                'registration_start' => '2026-08-01',
                'registration_end' => '2026-08-31',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Gelombang 1',
                'registration_start' => '2026-08-01',
                'registration_end' => '2026-08-31',
                'is_active' => true,
                'order' => 1,
            ],
        ]);

        $this->assertSame('Gelombang 1', $period->openWave()->name);
    }

    public function test_registration_encrypts_identifiers_and_removes_private_photo_when_deleted(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('ppdb/photos/foto.jpg', 'photo');

        $registration = PpdbRegistration::create($this->registrationData());
        $raw = DB::table('ppdb_registrations')->find($registration->id);

        $this->assertSame('3275011501010001', $registration->nik);
        $this->assertSame('0061234567', $registration->nisn);
        $this->assertNotSame('3275011501010001', $raw->nik);
        $this->assertNotSame('0061234567', $raw->nisn);
        $this->assertSame(hash('sha256', '3275011501010001'), $raw->nik_hash);
        $this->assertSame('0001', $raw->nik_last4);

        $registration->delete();

        Storage::disk('local')->assertMissing('ppdb/photos/foto.jpg');
    }

    public function test_same_nik_cannot_register_twice_in_one_period(): void
    {
        PpdbRegistration::create($this->registrationData());

        $this->expectException(QueryException::class);

        PpdbRegistration::create(array_merge($this->registrationData(), [
            'registration_number' => 'PPDB-2627-000002',
            'public_token' => '22222222-2222-4222-8222-222222222222',
        ]));
    }

    public function test_proof_page_explains_review_status_and_prioritizes_manual_whatsapp_notice(): void
    {
        $registration = PpdbRegistration::create($this->registrationData());

        $this->get(route('ppdb.proof', $registration->public_token))
            ->assertOk()
            ->assertSee('Data pendaftaran berhasil diterima')
            ->assertSee('menunggu peninjauan panitia')
            ->assertSee('Beri Tahu Panitia via WhatsApp')
            ->assertSee('Mohon+ditinjau', false)
            ->assertDontSee('Kirim Nomor ke WhatsApp')
            ->assertDontSee('Cetak Bukti');
    }

    private function registrationData(): array
    {
        $period = PpdbPeriod::first() ?? PpdbPeriod::create([
            'academic_year' => '2026/2027',
            'is_active' => true,
        ]);
        $wave = $period->waves()->first() ?? $period->waves()->create([
            'name' => 'Gelombang 1',
            'registration_start' => '2026-08-01',
            'registration_end' => '2026-08-31',
            'is_active' => true,
            'order' => 1,
        ]);

        return [
            'ppdb_period_id' => $period->id,
            'ppdb_wave_id' => $wave->id,
            'registration_number' => 'PPDB-2627-000001',
            'public_token' => '11111111-1111-4111-8111-111111111111',
            'status' => PpdbRegistration::STATUS_NEW,
            'full_name' => 'Muhammad Fadhil Ramadhan',
            'gender' => 'male',
            'nik' => '3275011501010001',
            'nisn' => '0061234567',
            'birth_place' => 'Bandung',
            'birth_date' => '2012-05-14',
            'address' => 'Jl. Melati No. 12',
            'province_code' => '32',
            'province_name' => 'JAWA BARAT',
            'district_city_code' => '01',
            'district_city_name' => 'KAB. BOGOR',
            'subdistrict_code' => '010',
            'subdistrict_name' => 'NANGGUNG',
            'village_code' => '001',
            'village_name' => 'MALASARI',
            'photo_path' => 'ppdb/photos/foto.jpg',
            'school_name' => 'SDN Sukamaju 01',
            'father_name' => 'Ahmad Hidayat',
            'mother_name' => 'Siti Nur Aisyah',
            'primary_contact_relation' => 'father',
            'primary_contact_phone' => '081234567890',
            'accuracy_accepted_at' => now(),
            'privacy_accepted_at' => now(),
            'submitted_at' => now(),
        ];
    }
}
