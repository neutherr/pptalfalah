<?php

namespace Tests\Feature;

use App\Livewire\Ppdb\RegistrationWizard;
use App\Models\PpdbPeriod;
use App\Models\PpdbRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class PpdbRegistrationWizardTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_registration_page_shows_closed_state_without_an_open_wave(): void
    {
        $this->get('/ppdb/daftar')
            ->assertOk()
            ->assertSee('Pendaftaran sedang ditutup')
            ->assertDontSeeLivewire(RegistrationWizard::class);
    }

    public function test_registration_page_renders_the_wizard_during_an_open_wave(): void
    {
        $this->createOpenWave();

        $this->get('/ppdb/daftar')
            ->assertOk()
            ->assertSee('Formulir Pendaftaran PPDB')
            ->assertSee('Gratis biaya pendidikan selama 1 tahun pertama')
            ->assertSeeLivewire(RegistrationWizard::class)
            ->assertDontSee('cdn.jsdelivr.net/npm/alpinejs', false);
    }

    public function test_each_step_validates_before_advancing(): void
    {
        $this->createOpenWave();

        Livewire::test(RegistrationWizard::class)
            ->call('nextStep')
            ->assertSet('step', 1)
            ->assertHasErrors(['fullName', 'nik', 'photo'])
            ->assertSee('Nama lengkap wajib diisi.')
            ->assertSee('Provinsi wajib diisi.')
            ->set('fullName', 'Muhammad Fadhil Ramadhan')
            ->set('gender', 'male')
            ->set('nik', '3275011501010001')
            ->set('birthPlace', 'Bandung')
            ->set('birthDate', '2012-05-14')
            ->set('address', 'Jl. Melati No. 12')
            ->set('provinceCode', '32')
            ->set('districtCityCode', '01')
            ->set('subdistrictCode', '010')
            ->set('villageCode', '001')
            ->set('photo', UploadedFile::fake()->image('formal.jpg', 400, 600)->size(500))
            ->call('nextStep')
            ->assertHasNoErrors()
            ->assertSet('step', 2)
            ->call('nextStep')
            ->assertSet('step', 2)
            ->assertHasErrors(['schoolName']);
    }

    public function test_changing_a_parent_area_resets_all_child_selections(): void
    {
        $this->createOpenWave();

        Livewire::test(RegistrationWizard::class)
            ->set('provinceCode', '32')
            ->set('districtCityCode', '01')
            ->set('subdistrictCode', '010')
            ->set('villageCode', '001')
            ->set('provinceCode', '31')
            ->assertSet('districtCityCode', '')
            ->assertSet('subdistrictCode', '')
            ->assertSet('villageCode', '');
    }

    public function test_valid_submission_stores_private_photo_and_redirects_to_safe_proof(): void
    {
        Storage::fake('local');
        $wave = $this->createOpenWave();

        $component = $this->validWizard()
            ->set('accuracyAccepted', true)
            ->set('privacyAccepted', true)
            ->call('submit')
            ->assertHasNoErrors();

        $registration = PpdbRegistration::sole();

        $component->assertRedirect(route('ppdb.proof', $registration->public_token));
        $this->assertSame($wave->id, $registration->ppdb_wave_id);
        $this->assertSame($wave->ppdb_period_id, $registration->ppdb_period_id);
        $this->assertStringStartsWith('PPDB-2627-', $registration->registration_number);
        $this->assertSame('3275011501010001', $registration->nik);
        $this->assertNotSame('3275011501010001', DB::table('ppdb_registrations')->value('nik'));
        $this->assertNotNull($registration->accuracy_accepted_at);
        $this->assertNotNull($registration->privacy_accepted_at);
        Storage::disk('local')->assertExists($registration->photo_path);

        $this->get(route('ppdb.proof', $registration->public_token))
            ->assertOk()
            ->assertSee($registration->registration_number)
            ->assertSee($registration->full_name)
            ->assertDontSee('3275011501010001')
            ->assertDontSee('Jl. Melati No. 12')
            ->assertDontSee('081234567890')
            ->assertDontSee($registration->photo_path);
    }

    public function test_submit_rejects_a_false_area_chain_and_returns_to_the_first_step(): void
    {
        $this->createOpenWave();

        $this->validWizard()
            ->set('step', 4)
            ->set('villageCode', '999')
            ->set('accuracyAccepted', true)
            ->set('privacyAccepted', true)
            ->call('submit')
            ->assertSet('step', 1)
            ->assertHasErrors(['villageCode']);

        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_submit_rechecks_that_the_wave_is_still_open(): void
    {
        $wave = $this->createOpenWave();
        $component = $this->validWizard()
            ->set('step', 4)
            ->set('accuracyAccepted', true)
            ->set('privacyAccepted', true);

        $wave->update(['registration_end' => today()->subDay()]);

        $component->call('submit')
            ->assertHasErrors(['registration'])
            ->assertNoRedirect();

        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_guardian_fields_are_required_only_when_guardian_is_the_primary_contact(): void
    {
        $this->createOpenWave();

        $this->validWizard()
            ->set('step', 3)
            ->set('primaryContactRelation', 'guardian')
            ->call('nextStep')
            ->assertSet('step', 3)
            ->assertHasErrors(['guardianName', 'guardianRelationship'])
            ->set('guardianName', 'Budi Santoso')
            ->set('guardianRelationship', 'Paman')
            ->call('nextStep')
            ->assertHasNoErrors()
            ->assertSet('step', 4);
    }

    public function test_confirmation_shows_all_collected_data_but_only_masks_the_nik(): void
    {
        $this->createOpenWave();

        $this->validWizard()
            ->set('step', 4)
            ->assertSee('0061234567')
            ->assertSee('Jl. Melati No. 12')
            ->assertSee('40286')
            ->assertSee('20212345')
            ->assertSee('Karyawan Swasta')
            ->assertSee('Ibu Rumah Tangga')
            ->assertSee('•••• •••• •••• 0001')
            ->assertDontSee('3275011501010001');
    }

    public function test_optional_family_card_number_is_encrypted_and_masked_on_confirmation(): void
    {
        Storage::fake('local');
        $this->createOpenWave();

        $this->validWizard()
            ->set('familyCardNumber', '3275010102030004')
            ->set('step', 4)
            ->assertSee('•••• •••• •••• 0004')
            ->assertDontSee('3275010102030004')
            ->set('accuracyAccepted', true)
            ->set('privacyAccepted', true)
            ->call('submit')
            ->assertHasNoErrors();

        $registration = PpdbRegistration::sole();

        $this->assertSame('3275010102030004', $registration->family_card_number);
        $this->assertNotSame('3275010102030004', DB::table('ppdb_registrations')->value('family_card_number'));
    }

    public function test_family_card_number_must_be_sixteen_digits_when_provided(): void
    {
        $this->createOpenWave();

        $this->validWizard()
            ->set('familyCardNumber', '1234')
            ->call('nextStep')
            ->assertHasErrors(['familyCardNumber']);
    }

    public function test_photo_must_use_an_allowed_format_and_stay_below_two_megabytes(): void
    {
        $this->createOpenWave();

        $this->validWizard()
            ->set('photo', UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'))
            ->call('nextStep')
            ->assertHasErrors(['photo']);

        $this->validWizard()
            ->set('photo', UploadedFile::fake()->image('formal.jpg')->size(2049))
            ->call('nextStep')
            ->assertHasErrors(['photo']);
    }

    public function test_duplicate_nik_is_rejected_without_leaving_a_second_photo(): void
    {
        Storage::fake('local');
        $this->createOpenWave();

        $this->validWizard()
            ->set('accuracyAccepted', true)
            ->set('privacyAccepted', true)
            ->call('submit')
            ->assertHasNoErrors();

        $this->validWizard()
            ->set('accuracyAccepted', true)
            ->set('privacyAccepted', true)
            ->call('submit')
            ->assertSet('step', 1)
            ->assertHasErrors(['nik']);

        $this->assertDatabaseCount('ppdb_registrations', 1);
        $this->assertCount(1, Storage::disk('local')->allFiles('ppdb/photos'));
    }

    public function test_honeypot_blocks_bot_submission(): void
    {
        Storage::fake('local');
        $this->createOpenWave();

        $this->validWizard()
            ->set('website', 'https://spam.example')
            ->set('accuracyAccepted', true)
            ->set('privacyAccepted', true)
            ->call('submit')
            ->assertHasErrors(['website']);

        $this->assertDatabaseCount('ppdb_registrations', 0);
        $this->assertSame([], Storage::disk('local')->allFiles('ppdb/photos'));
    }

    public function test_submission_is_rate_limited_after_five_attempts(): void
    {
        Storage::fake('local');
        $this->createOpenWave();
        RateLimiter::clear('ppdb-registration:127.0.0.1');

        foreach (range(1, 5) as $attempt) {
            $this->validWizard()
                ->set('nik', '32750115010'.str_pad((string) $attempt, 5, '0', STR_PAD_LEFT))
                ->set('accuracyAccepted', true)
                ->set('privacyAccepted', true)
                ->call('submit')
                ->assertHasNoErrors();
        }

        $this->validWizard()
            ->set('nik', '3275011501099999')
            ->set('accuracyAccepted', true)
            ->set('privacyAccepted', true)
            ->call('submit')
            ->assertHasErrors(['registration']);

        $this->assertDatabaseCount('ppdb_registrations', 5);
    }

    public function test_database_failure_removes_the_stored_photo(): void
    {
        Storage::fake('local');
        $this->createOpenWave();
        Event::listen('eloquent.creating: '.PpdbRegistration::class, fn () => throw new \RuntimeException('database failed'));

        try {
            $this->validWizard()
                ->set('accuracyAccepted', true)
                ->set('privacyAccepted', true)
                ->call('submit');

            $this->fail('Database failure was not thrown.');
        } catch (\RuntimeException $exception) {
            $this->assertSame('database failed', $exception->getMessage());
        } finally {
            Event::forget('eloquent.creating: '.PpdbRegistration::class);
        }

        $this->assertSame([], Storage::disk('local')->allFiles('ppdb/photos'));
    }

    private function validWizard(): Testable
    {
        return Livewire::test(RegistrationWizard::class)
            ->set('fullName', 'Muhammad Fadhil Ramadhan')
            ->set('gender', 'male')
            ->set('nik', '3275011501010001')
            ->set('nisn', '0061234567')
            ->set('birthPlace', 'Bandung')
            ->set('birthDate', '2012-05-14')
            ->set('address', 'Jl. Melati No. 12')
            ->set('provinceCode', '32')
            ->set('districtCityCode', '01')
            ->set('subdistrictCode', '010')
            ->set('villageCode', '001')
            ->set('postalCode', '40286')
            ->set('studentPhone', '081234567890')
            ->set('photo', UploadedFile::fake()->image('formal.jpg', 400, 600)->size(500))
            ->set('schoolName', 'SDN Sukamaju 01')
            ->set('npsn', '20212345')
            ->set('fatherName', 'Ahmad Hidayat')
            ->set('fatherEducation', 'S1')
            ->set('fatherJob', 'Karyawan Swasta')
            ->set('motherName', 'Siti Nur Aisyah')
            ->set('motherEducation', 'SMA')
            ->set('motherJob', 'Ibu Rumah Tangga')
            ->set('primaryContactRelation', 'father')
            ->set('primaryContactPhone', '081234567890');
    }

    private function createOpenWave()
    {
        Carbon::setTestNow('2026-08-08 09:00:00');

        $period = PpdbPeriod::create([
            'academic_year' => '2026/2027',
            'is_active' => true,
        ]);

        return $period->waves()->create([
            'name' => 'Gelombang 1',
            'registration_start' => '2026-08-01',
            'registration_end' => '2026-08-31',
            'is_active' => true,
            'order' => 1,
        ]);
    }
}
