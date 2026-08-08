<?php

namespace Tests\Feature;

use App\Filament\Exports\PpdbRegistrationExporter;
use App\Filament\Resources\PpdbRegistrationResource;
use App\Filament\Resources\PpdbRegistrationResource\Pages\ListPpdbRegistrations;
use App\Filament\Resources\PpdbRegistrationResource\Pages\ViewPpdbRegistration;
use App\Models\PpdbPeriod;
use App\Models\PpdbRegistration;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class PpdbRegistrationAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs(User::factory()->create());
        Carbon::setTestNow('2026-08-08 09:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_filament_export_support_tables_are_available(): void
    {
        $this->assertTrue(Schema::hasTable('exports'));
        $this->assertTrue(Schema::hasTable('job_batches'));
        $this->assertTrue(Schema::hasTable('notifications'));
    }

    public function test_admin_can_search_by_name_number_and_exact_nik(): void
    {
        $first = $this->createRegistration(['full_name' => 'Muhammad Fadhil', 'nik' => '3275011501010001']);
        $second = $this->createRegistration([
            'registration_number' => 'PPDB-2627-000002',
            'public_token' => '22222222-2222-4222-8222-222222222222',
            'full_name' => 'Rizki Maulana',
            'nik' => '3275011501010002',
        ]);

        Livewire::test(ListPpdbRegistrations::class)
            ->assertCanSeeTableRecords([$first, $second])
            ->searchTable('Muhammad')
            ->assertCanSeeTableRecords([$first])
            ->assertCanNotSeeTableRecords([$second])
            ->searchTable($second->registration_number)
            ->assertCanSeeTableRecords([$second])
            ->assertCanNotSeeTableRecords([$first])
            ->searchTable('3275011501010001')
            ->assertCanSeeTableRecords([$first])
            ->assertCanNotSeeTableRecords([$second]);
    }

    public function test_admin_can_mark_a_registration_as_reviewed(): void
    {
        $registration = $this->createRegistration();

        Livewire::test(ListPpdbRegistrations::class)
            ->callTableAction('markReviewed', $registration)
            ->assertHasNoTableActionErrors();

        $this->assertSame(PpdbRegistration::STATUS_REVIEWED, $registration->fresh()->status);
        $this->assertNotNull($registration->fresh()->reviewed_at);
    }

    public function test_navigation_badge_counts_only_new_registrations(): void
    {
        $newRegistration = $this->createRegistration();
        $this->createRegistration([
            'registration_number' => 'PPDB-2627-000002',
            'public_token' => '22222222-2222-4222-8222-222222222222',
            'nik' => '3275011501010002',
            'status' => PpdbRegistration::STATUS_REVIEWED,
            'reviewed_at' => now(),
        ]);

        $this->assertSame('1', PpdbRegistrationResource::getNavigationBadge());

        $newRegistration->update([
            'status' => PpdbRegistration::STATUS_REVIEWED,
            'reviewed_at' => now(),
        ]);

        $this->assertNull(PpdbRegistrationResource::getNavigationBadge());
    }

    public function test_reviewed_registration_has_a_safe_prefilled_whatsapp_url(): void
    {
        $registration = $this->createRegistration([
            'status' => PpdbRegistration::STATUS_REVIEWED,
            'reviewed_at' => now(),
            'primary_contact_phone' => '0812 3456-7890',
        ]);

        $url = PpdbRegistrationResource::contactWhatsappUrl($registration);
        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
        $message = $query['text'] ?? '';

        $this->assertStringStartsWith('https://wa.me/6281234567890?text=', $url);
        $this->assertStringContainsString($registration->full_name, $message);
        $this->assertStringContainsString($registration->registration_number, $message);
        $this->assertStringContainsString('telah ditinjau', $message);
        $this->assertStringNotContainsString($registration->nik, $message);
        $this->assertStringNotContainsString($registration->address, $message);
        $this->assertStringNotContainsString($registration->public_token, $message);
    }

    public function test_whatsapp_action_is_only_available_after_review(): void
    {
        $registration = $this->createRegistration();

        Livewire::test(ListPpdbRegistrations::class)
            ->assertTableActionHidden('contactWhatsapp', $registration);

        Livewire::test(ViewPpdbRegistration::class, ['record' => $registration->getRouteKey()])
            ->assertActionVisible('markReviewed')
            ->assertActionHidden('contactWhatsapp');

        $registration->update([
            'status' => PpdbRegistration::STATUS_REVIEWED,
            'reviewed_at' => now(),
        ]);

        Livewire::test(ListPpdbRegistrations::class)
            ->assertTableActionVisible('contactWhatsapp', $registration);

        Livewire::test(ViewPpdbRegistration::class, ['record' => $registration->getRouteKey()])
            ->assertActionHidden('markReviewed')
            ->assertActionVisible('contactWhatsapp');
    }

    public function test_retention_filter_only_shows_records_one_year_after_the_period_ends(): void
    {
        $eligible = $this->createRegistration();
        $eligible->wave->update(['registration_end' => '2025-08-07']);

        $current = $this->createRegistration([
            'registration_number' => 'PPDB-2627-000002',
            'public_token' => '22222222-2222-4222-8222-222222222222',
            'nik' => '3275011501010002',
        ], '2026-08-31');

        Livewire::test(ListPpdbRegistrations::class)
            ->filterTable('retention', true)
            ->assertCanSeeTableRecords([$eligible])
            ->assertCanNotSeeTableRecords([$current]);
    }

    public function test_exporter_uses_sync_connection_and_contains_safe_admin_columns(): void
    {
        $exporter = (new \ReflectionClass(PpdbRegistrationExporter::class))->newInstanceWithoutConstructor();
        $columnNames = collect(PpdbRegistrationExporter::getColumns())->map->getName();

        $this->assertSame('sync', $exporter->getJobConnection());
        $this->assertTrue($columnNames->contains('registration_number'));
        $this->assertTrue($columnNames->contains('nik_last4'));
        $this->assertTrue($columnNames->contains('nik'));
        $this->assertTrue($columnNames->contains('family_card_number'));
        $this->assertFalse($columnNames->contains('photo_path'));
    }

    private function createRegistration(array $overrides = [], string $registrationEnd = '2026-08-31'): PpdbRegistration
    {
        $period = PpdbPeriod::create(['academic_year' => '2026/2027', 'is_active' => true]);
        $wave = $period->waves()->create([
            'name' => 'Gelombang 1',
            'registration_start' => '2026-08-01',
            'registration_end' => $registrationEnd,
            'is_active' => true,
            'order' => 1,
        ]);

        return PpdbRegistration::create(array_merge([
            'ppdb_period_id' => $period->id,
            'ppdb_wave_id' => $wave->id,
            'registration_number' => 'PPDB-2627-000001',
            'public_token' => '11111111-1111-4111-8111-111111111111',
            'status' => PpdbRegistration::STATUS_NEW,
            'full_name' => 'Muhammad Fadhil Ramadhan',
            'gender' => 'male',
            'nik' => '3275011501010001',
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
        ], $overrides));
    }
}
