<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\PpdbPeriod;
use App\Models\SiteSetting;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeAccessibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_pagination_controls_have_names_and_large_hit_areas(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('x-bind:aria-label="\'Buka slide galeri \' + i"', false)
            ->assertSee('class="w-11 h-11 flex items-center justify-center rounded-full', false);
    }

    public function test_homepage_section_item_headings_use_the_correct_level(): void
    {
        SiteSetting::create([
            'key' => 'mission_1_title',
            'value' => 'Membentuk Santri Mandiri',
            'group' => 'homepage',
        ]);
        PpdbPeriod::create([
            'academic_year' => '2026/2027',
            'is_active' => true,
        ]);

        $xpath = $this->xpath($this->get('/')->assertOk()->getContent());

        $this->assertSame(1, $xpath->query('//h3[normalize-space()="Membentuk Santri Mandiri"]')->length);
        $this->assertSame(1, $xpath->query('//h3[normalize-space()="Registrasi Online / Offline"]')->length);
    }

    public function test_location_map_has_a_title(): void
    {
        $xpath = $this->xpath($this->get('/')->assertOk()->getContent());
        $map = $xpath->query('//iframe[contains(@src, "maps.google.com")]')->item(0);

        $this->assertNotNull($map);
        $this->assertSame('Peta lokasi Pondok Pesantren Tahfidz Al-Falah', $map->getAttribute('title'));
    }

    public function test_article_cards_use_their_link_instead_of_a_click_handler(): void
    {
        $article = Article::create([
            'title' => 'Belajar Pertanian Digital',
            'slug' => 'belajar-pertanian-digital',
            'content' => '<p>Isi artikel.</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $xpath = $this->xpath($this->get('/')->assertOk()->getContent());
        $card = $xpath->query('//article[.//h3[normalize-space()="Belajar Pertanian Digital"]]')->item(0);

        $this->assertNotNull($card);
        $this->assertFalse($card->hasAttribute('onclick'));
        $this->assertStringNotContainsString('cursor-pointer', $card->getAttribute('class'));
        $this->assertSame(1, $xpath->query('.//a[@href="'.route('articles.show', $article->slug).'"]', $card)->length);
    }

    public function test_homepage_does_not_use_the_unloaded_alpine_collapse_plugin(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertDontSee('x-collapse', false);
    }

    public function test_homepage_uses_a_soft_blur_between_the_hero_and_next_section(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('data-hero-transition="blur"', false)
            ->assertSee('backdrop-blur-xl', false);
    }

    public function test_footer_headings_do_not_skip_levels(): void
    {
        $xpath = $this->xpath($this->get('/')->assertOk()->getContent());

        $this->assertSame(1, $xpath->query('//footer//h2[normalize-space()="SMK Al-Falah Boarding School"]')->length);
        $this->assertSame(3, $xpath->query('//footer//h3')->length);
        $this->assertSame(0, $xpath->query('//footer//h4 | //footer//h5')->length);
    }

    private function xpath(string $html): DOMXPath
    {
        $document = new DOMDocument;
        $previousErrorHandling = libxml_use_internal_errors(true);
        $document->loadHTML($html);
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrorHandling);

        return new DOMXPath($document);
    }
}
