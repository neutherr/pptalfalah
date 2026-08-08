<?php

namespace Tests\Feature;

use App\Models\PpdbPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PpdbCtaPrivacyTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_open_registration_routes_all_direct_ctas_to_the_online_form(): void
    {
        $this->createOpenWave();
        $registerUrl = route('ppdb.register');

        $home = $this->get(route('home'))->assertOk()->getContent();
        $this->assertStringContainsString('data-ppdb-cta="desktop" href="'.$registerUrl.'"', $home);
        $this->assertStringContainsString('data-ppdb-cta="mobile" href="'.$registerUrl.'"', $home);
        $this->assertStringContainsString('data-ppdb-cta="home-hero" href="'.$registerUrl.'"', $home);
        $this->assertStringContainsString('href="'.route('ppdb').'"', $home);
        $this->assertStringContainsString('Lihat Syarat Pendaftaran', $home);

        $ppdb = $this->get(route('ppdb'))->assertOk()->getContent();
        $this->assertStringContainsString('data-ppdb-cta="main" href="'.$registerUrl.'"', $ppdb);
        $this->assertStringContainsString('data-ppdb-cta="fee" href="'.$registerUrl.'"', $ppdb);
        $this->assertStringContainsString('Tanya Panitia PPDB', $ppdb);
    }

    public function test_closed_registration_changes_navbar_ctas_to_information_links(): void
    {
        $html = $this->get(route('home'))->assertOk()->getContent();

        $this->assertStringContainsString('data-ppdb-cta="desktop" href="'.route('ppdb').'"', $html);
        $this->assertStringContainsString('data-ppdb-cta="mobile" href="'.route('ppdb').'"', $html);
        $this->assertStringContainsString('Info PPDB', $html);
    }

    public function test_ppdb_page_explains_which_documents_are_brought_to_the_pondok(): void
    {
        $this->createOpenWave();

        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('Setelah pendaftaran online berhasil, bawa dokumen berikut saat verifikasi dan tes di pondok.')
            ->assertSee('Fotokopi Akta Kelahiran dan Kartu Keluarga')
            ->assertSee('Pas foto 3×4 sebanyak empat lembar')
            ->assertSee('Fotokopi rapor terakhir')
            ->assertSee('tes akademik di pondok')
            ->assertSee('Rp150.000 saat datang')
            ->assertSee('tidak perlu diunggah ke website');
    }

    public function test_privacy_page_is_created_and_linked_from_the_footer(): void
    {
        $this->assertDatabaseHas('pages', ['slug' => 'kebijakan-privasi-ppdb', 'is_published' => true]);

        $this->get(route('page.show', 'kebijakan-privasi-ppdb'))
            ->assertOk()
            ->assertSee('Tujuan Pengumpulan Data')
            ->assertSee('Akses Terbatas')
            ->assertSee('satu tahun');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('href="'.route('page.show', 'kebijakan-privasi-ppdb').'"', false);
    }

    private function createOpenWave(): void
    {
        Carbon::setTestNow('2026-08-08 09:00:00');

        $period = PpdbPeriod::create([
            'academic_year' => '2026/2027',
            'description' => 'Pendaftaran tahun ajaran baru.',
            'is_active' => true,
        ]);
        $period->waves()->create([
            'name' => 'Gelombang 1',
            'registration_start' => '2026-08-01',
            'registration_end' => '2026-08-31',
            'is_active' => true,
            'order' => 1,
        ]);
        $period->fees()->create(['name' => 'Biaya Pendaftaran', 'amount' => 150000, 'order' => 1]);
    }
}
