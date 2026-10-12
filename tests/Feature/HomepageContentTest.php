<?php

namespace Tests\Feature;

use App\Filament\Pages\HomepageSettings;
use App\Models\SiteSetting;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HomepageContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_independent_hero_vision_subtitle_and_motto(): void
    {
        SiteSetting::set('hero_title', 'Judul hero tersendiri');
        SiteSetting::set('vision_headline', 'Visi pendidikan tersendiri');
        SiteSetting::set('hero_description', 'Deskripsi sekolah yang dapat diedit');
        SiteSetting::set('school_motto', 'Ilmu, Iman, Terampil, dan Mandiri.');

        $response = $this->get('/')->assertOk();
        $response->assertSeeText('Judul hero tersendiri')
            ->assertSeeText('Visi pendidikan tersendiri')
            ->assertSeeText('Deskripsi sekolah yang dapat diedit')
            ->assertSeeText('Ilmu, Iman, Terampil, dan Mandiri.');
        preg_match('/<h1\b[^>]*>(.*?)<\/h1>/s', $response->getContent(), $matches);
        $this->assertStringContainsString('Judul hero tersendiri', $matches[1]);
        $this->assertStringNotContainsString('Visi pendidikan tersendiri', $matches[1]);
    }

    public function test_all_eight_description_only_missions_are_shown_in_order(): void
    {
        for ($i = 1; $i <= 8; $i++) {
            SiteSetting::set("mission_{$i}_desc", "Isi misi nomor {$i}");
        }

        $this->get('/')->assertOk()->assertSeeTextInOrder([
            'Isi misi nomor 1', 'Isi misi nomor 2', 'Isi misi nomor 3', 'Isi misi nomor 4',
            'Isi misi nomor 5', 'Isi misi nomor 6', 'Isi misi nomor 7', 'Isi misi nomor 8',
        ]);
    }

    public function test_new_hero_text_is_escaped_and_supports_line_breaks(): void
    {
        SiteSetting::set('hero_title', "Baris satu\n<script>alert(1)</script>");

        $this->get('/')->assertOk()
            ->assertSee('Baris satu<br />', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_legacy_hero_and_mission_content_remains_visible(): void
    {
        SiteSetting::set('vision_headline', 'Judul lama<br/>Baris kedua');
        SiteSetting::set('mission_1_title', 'Misi lama');
        SiteSetting::set('mission_1_desc', 'Isi misi lama');

        $this->get('/')->assertOk()->assertSeeText('Judul lama')
            ->assertSeeText('Baris kedua')->assertSeeText('Misi lama')->assertSeeText('Isi misi lama');
    }

    public function test_admin_can_save_homepage_content_without_changing_other_settings(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs(User::factory()->create());
        SiteSetting::set('phone', '08123456789', 'contact');
        SiteSetting::set('vision_headline', 'Visi awal', 'vision');

        Livewire::test(HomepageSettings::class)
            ->fillForm([
                'hero_title' => 'Hero baru',
                'site_tagline' => 'Tagline baru',
                'hero_description' => 'Deskripsi baru',
                'vision_headline' => 'Visi baru',
                'school_motto' => 'Moto baru',
                'mission_8_title' => '',
                'mission_8_desc' => 'Misi kedelapan baru',
            ])->call('save')->assertHasNoFormErrors();

        $this->assertSame('Hero baru', SiteSetting::get('hero_title'));
        $this->assertSame('Visi baru', SiteSetting::get('vision_headline'));
        $this->assertSame('08123456789', SiteSetting::get('phone'));
        $this->get('/')->assertOk()->assertSeeText('Hero baru')->assertSeeText('Visi baru')
            ->assertSeeText('Moto baru')->assertSeeText('Misi kedelapan baru');
    }

    public function test_client_revision_is_previewed_before_saving(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs(User::factory()->create());
        SiteSetting::set('vision_headline', 'Visi lama', 'vision');
        SiteSetting::set('mission_1_title', 'Judul lama', 'vision');

        $page = Livewire::test(HomepageSettings::class)->callAction('loadClientRevision');
        $this->assertSame('Visi lama', SiteSetting::get('vision_headline'));
        $page->assertFormSet([
            'hero_description' => 'SMK Pertanian berbasis Boarding School',
            'school_motto' => 'Ilmu, Iman, Terampil, dan Mandiri.',
            'mission_1_title' => '',
            'mission_8_desc' => 'Mengembangkan peran sekolah sebagai pusat pengembangan keahlian, riset, inovasi, budaya hidup sehat, dan wawasan lingkungan.',
        ])->call('save')->assertHasNoFormErrors();

        $this->get('/')->assertOk()->assertSeeText('Berkarakter Islami, Berjiwa Kewirausahaan')
            ->assertSeeText('SMK Pertanian berbasis Boarding School')
            ->assertSeeText('Mengembangkan peran sekolah sebagai pusat pengembangan keahlian, riset, inovasi, budaya hidup sehat, dan wawasan lingkungan.')
            ->assertDontSeeText('Judul lama');
    }

    public function test_homepage_editor_requires_login(): void
    {
        $this->get('/admin/homepage-settings')->assertRedirect('/admin/login');
    }

    public function test_empty_missions_are_omitted_and_optional_copy_can_be_cleared(): void
    {
        SiteSetting::set('hero_description', '');
        SiteSetting::set('school_motto', '');
        SiteSetting::set('mission_1_title', '   ');
        SiteSetting::set('mission_1_desc', '   ');
        SiteSetting::set('mission_8_desc', 'Misi terakhir');

        $this->get('/')->assertOk()->assertViewHas('missions', fn ($missions) => count($missions) === 1
            && $missions[0]['number'] === '08')
            ->assertDontSeeText('SMK Pertanian & IT berbasis Pesantren Tahfidz di Jonggol, Bogor.')
            ->assertDontSeeText('Moto')->assertSeeText('Misi terakhir');
    }

    public function test_invalid_form_does_not_partially_save_content(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs(User::factory()->create());
        SiteSetting::set('hero_title', 'Hero tersimpan');

        Livewire::test(HomepageSettings::class)
            ->fillForm(['hero_title' => 'Perubahan belum valid', 'vision_headline' => ''])
            ->call('save')->assertHasFormErrors(['vision_headline' => 'required']);

        $this->assertSame('Hero tersimpan', SiteSetting::get('hero_title'));
    }
}
