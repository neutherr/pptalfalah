<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\PpdbPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PpdbPageTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_ppdb_page_prioritizes_registration_status_and_practical_information(): void
    {
        Carbon::setTestNow('2026-08-03 09:00:00');

        $period = PpdbPeriod::create([
            'academic_year' => '2026/2027',
            'description' => 'Pendaftaran santri baru SMK Al-Falah.',
            'is_active' => true,
        ]);

        $period->waves()->create([
            'name' => 'Gelombang 1',
            'registration_start' => '2026-04-10',
            'registration_end' => '2026-06-15',
            'test_date' => '2026-06-20',
            'is_active' => true,
            'order' => 1,
        ]);
        $period->requirements()->create([
            'title' => 'Fotokopi Akta Kelahiran dan KK',
            'order' => 1,
        ]);
        $period->fees()->create([
            'name' => 'Administrasi',
            'amount' => 150000,
            'order' => 1,
        ]);

        $this->get('https://pptalfalah.com/ppdb')
            ->assertOk()
            ->assertSee('PPDB SMK Al-Falah 2026/2027')
            ->assertSee('Pendaftaran Ditutup')
            ->assertSee('Gelombang 1 berakhir 15 Jun 2026')
            ->assertDontSee('Tes seleksi 20 Jun 2026')
            ->assertDontSee('Pengumuman 30 Jun 2026')
            ->assertSee('Alur Pendaftaran')
            ->assertSee('Tanya Panitia PPDB')
            ->assertDontSee('Penerimaan Santri Baru')
            ->assertDontSee('bg-gradient-to-bl', false);
    }

    public function test_cms_page_does_not_render_an_official_information_badge(): void
    {
        $page = Page::create([
            'title' => 'Tentang Al-Falah',
            'slug' => 'tentang-al-falah',
            'content' => '<p>Profil sekolah.</p>',
            'is_published' => true,
        ]);

        $this->get(route('page.show', $page->slug))
            ->assertOk()
            ->assertDontSee('Informasi Resmi');
    }
}
