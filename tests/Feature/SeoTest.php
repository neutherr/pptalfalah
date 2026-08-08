<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Article;
use App\Models\Page;
use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_has_complete_seo_metadata(): void
    {
        $response = $this->get('https://pptalfalah.com/');

        $response
            ->assertOk()
            ->assertSee('<title>SMK Pertanian &amp; IT Pesantren Tahfidz Jonggol | Al-Falah</title>', false)
            ->assertSee('<meta name="description" content="SMK Pertanian &amp; IT berbasis Pesantren Tahfidz di Jonggol, Bogor', false)
            ->assertSee('<meta name="robots" content="index,follow"', false)
            ->assertSee('<meta property="og:image" content="https://pptalfalah.com/assets/LOGO1.jpeg"', false)
            ->assertSee('<meta property="og:url" content="https://pptalfalah.com"', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image"', false)
            ->assertSee('<link rel="canonical" href="https://pptalfalah.com"', false)
            ->assertSee('"EducationalOrganization"', false)
            ->assertSee('"School"', false);
    }

    public function test_article_has_specific_metadata_and_structured_data(): void
    {
        $article = Article::create([
            'title' => 'Santri Belajar Pertanian Digital',
            'slug' => 'santri-belajar-pertanian-digital',
            'excerpt' => 'Santri memadukan tahfidz, pertanian modern, dan teknologi informasi.',
            'content' => '<p>Isi artikel.</p>',
            'featured_image' => 'articles/pertanian-digital.jpg',
            'meta_title' => 'Pertanian Digital untuk Santri Tahfidz',
            'meta_description' => 'Pembelajaran pertanian digital untuk santri tahfidz di Jonggol, Bogor.',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get('https://pptalfalah.com/berita/'.$article->slug);

        $response
            ->assertOk()
            ->assertSee('<title>Pertanian Digital untuk Santri Tahfidz</title>', false)
            ->assertSee('<meta name="description" content="Pembelajaran pertanian digital untuk santri tahfidz di Jonggol, Bogor."', false)
            ->assertSee('<meta property="og:image" content="https://pptalfalah.com/storage/articles/pertanian-digital.jpg"', false)
            ->assertSee('<meta property="og:type" content="article"', false)
            ->assertSee('"@type":"Article"', false)
            ->assertSee('"@type":"BreadcrumbList"', false);
    }

    public function test_program_has_specific_metadata_and_breadcrumb_schema(): void
    {
        $program = Program::create([
            'title' => 'Teknologi Informasi',
            'slug' => 'teknologi-informasi',
            'description' => 'Program IT berbasis Pesantren Tahfidz di Jonggol, Bogor.',
            'image' => 'programs/teknologi-informasi.jpg',
            'is_active' => true,
        ]);

        $response = $this->get('https://pptalfalah.com/program/'.$program->slug);

        $response
            ->assertOk()
            ->assertSee('<title>Teknologi Informasi | Al-Falah Boarding School</title>', false)
            ->assertSee('<meta name="description" content="Program IT berbasis Pesantren Tahfidz di Jonggol, Bogor."', false)
            ->assertSee('<meta property="og:image" content="https://pptalfalah.com/storage/programs/teknologi-informasi.jpg"', false)
            ->assertSee('"@type":"BreadcrumbList"', false);
    }

    public function test_priority_public_pages_have_unique_metadata(): void
    {
        $pages = [
            '/program' => 'Program Tahfidz, Pertanian &amp; IT | PPT Al-Falah Jonggol',
            '/ppdb' => 'PPDB SMK Pesantren Tahfidz Jonggol | PPT Al-Falah',
            '/galeri' => 'Galeri Kegiatan PPT Al-Falah Jonggol',
            '/berita' => 'Berita &amp; Artikel PPT Al-Falah | Jonggol, Bogor',
            '/agenda' => 'Agenda PPT Al-Falah Jonggol, Bogor',
            '/pengumuman' => 'Pengumuman PPT Al-Falah Jonggol, Bogor',
            '/fasilitas' => 'Fasilitas Pesantren &amp; SMK Boarding School Jonggol | Al-Falah',
            '/kontak' => 'Kontak PPT Al-Falah Jonggol, Bogor',
        ];

        foreach ($pages as $path => $title) {
            $this->get('https://pptalfalah.com'.$path)
                ->assertOk()
                ->assertSee('<title>'.$title.'</title>', false)
                ->assertDontSee('<meta name="description" content="SMK Pertanian &amp; IT berbasis Pesantren Tahfidz di Jonggol, Bogor untuk membentuk generasi Qurani yang mandiri dan siap berkarya."', false);
        }
    }

    public function test_agenda_and_announcement_have_metadata_and_breadcrumbs(): void
    {
        $agenda = Agenda::create([
            'title' => 'Open House Al-Falah',
            'slug' => 'open-house-al-falah',
            'description' => 'Kunjungi SMK Pesantren Tahfidz Al-Falah di Jonggol, Bogor.',
            'start_datetime' => now()->addWeek(),
            'is_published' => true,
        ]);
        $announcement = Announcement::create([
            'title' => 'Jadwal PPDB Gelombang Pertama',
            'slug' => 'jadwal-ppdb-gelombang-pertama',
            'content' => '<p>Informasi jadwal PPDB Al-Falah Jonggol.</p>',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('https://pptalfalah.com/agenda/'.$agenda->slug)
            ->assertOk()
            ->assertSee('<title>Open House Al-Falah | Agenda PPT Al-Falah</title>', false)
            ->assertSee('"@type":"BreadcrumbList"', false);

        $this->get('https://pptalfalah.com/pengumuman/'.$announcement->slug)
            ->assertOk()
            ->assertSee('<title>Jadwal PPDB Gelombang Pertama | PPT Al-Falah</title>', false)
            ->assertSee('"@type":"BreadcrumbList"', false);
    }

    public function test_cms_page_has_social_image_and_breadcrumb_schema(): void
    {
        $page = Page::create([
            'title' => 'Profil Pondok',
            'slug' => 'profil',
            'content' => '<p>Profil PPT Al-Falah Jonggol.</p>',
            'meta_title' => 'Profil PPT Al-Falah',
            'meta_description' => 'Profil SMK berbasis Pesantren Tahfidz di Jonggol, Bogor.',
            'og_image' => 'pages/profil.jpg',
            'is_published' => true,
        ]);

        $this->get('https://pptalfalah.com/profil')
            ->assertOk()
            ->assertSee('<title>Profil PPT Al-Falah | Al-Falah Boarding School</title>', false)
            ->assertSee('<meta property="og:image" content="https://pptalfalah.com/storage/pages/profil.jpg"', false)
            ->assertSee('<link rel="canonical" href="https://pptalfalah.com/profil"', false)
            ->assertSee('"@type":"BreadcrumbList"', false);
    }

    public function test_priority_pages_explain_local_positioning(): void
    {
        $this->get('https://pptalfalah.com/')
            ->assertOk()
            ->assertSee('<title>SMK Pertanian &amp; IT Pesantren Tahfidz Jonggol | Al-Falah</title>', false)
            ->assertSee('<span class="block mt-2">SMK Pertanian & IT berbasis Pesantren Tahfidz di Jonggol, Bogor.</span>', false)
            ->assertSee(route('programs.index'), false)
            ->assertSee(route('ppdb'), false)
            ->assertSee(route('fasilitas'), false)
            ->assertSee(route('contact'), false);

        $this->get('https://pptalfalah.com/program')
            ->assertOk()
            ->assertSeeText('Tahfidz Al-Qur’an, pertanian modern, dan teknologi informasi');

        $this->get('https://pptalfalah.com/ppdb')
            ->assertOk()
            ->assertSee('Pesantren Tahfidz di Jonggol, Bogor.', false);

        $this->get('https://pptalfalah.com/fasilitas')
            ->assertOk()
            ->assertSeeText('Fasilitas pendidikan, asrama, ibadah, pertanian, dan teknologi di Jonggol, Bogor');

        $this->get('https://pptalfalah.com/kontak')
            ->assertOk()
            ->assertSeeText('Kampus PPT Al-Falah di Jonggol, Bogor');
    }

    public function test_site_uses_compiled_tailwind_assets(): void
    {
        $html = $this->get('https://pptalfalah.com/')
            ->assertOk()
            ->getContent();

        $this->assertStringNotContainsString('cdn.tailwindcss.com', $html);
        $this->assertStringNotContainsString('id="tailwind-config"', $html);
        $this->assertMatchesRegularExpression('/<link[^>]+href="https:\/\/pptalfalah\.com\/build\/assets\/app-[^"]+\.css"/', $html);
    }

    public function test_sitemap_contains_only_public_content(): void
    {
        $publicProgram = Program::create([
            'title' => 'Agribisnis',
            'slug' => 'agribisnis',
            'description' => 'Program pertanian modern.',
            'is_active' => true,
        ]);
        $hiddenProgram = Program::create([
            'title' => 'Program Tersembunyi',
            'slug' => 'program-tersembunyi',
            'description' => 'Tidak aktif.',
            'is_active' => false,
        ]);
        $publicArticle = Article::create([
            'title' => 'Artikel Publik',
            'slug' => 'artikel-publik',
            'content' => '<p>Publik.</p>',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
        $draftArticle = Article::create([
            'title' => 'Artikel Draft',
            'slug' => 'artikel-draft',
            'content' => '<p>Draft.</p>',
            'status' => 'draft',
        ]);
        $profile = Page::create([
            'title' => 'Profil',
            'slug' => 'profil',
            'is_published' => true,
        ]);
        $hiddenPage = Page::create([
            'title' => 'Halaman Tersembunyi',
            'slug' => 'halaman-tersembunyi',
            'is_published' => false,
        ]);
        $publicAgenda = Agenda::create([
            'title' => 'Agenda Publik',
            'slug' => 'agenda-publik',
            'start_datetime' => now()->addDay(),
            'is_published' => true,
        ]);
        $hiddenAgenda = Agenda::create([
            'title' => 'Agenda Tersembunyi',
            'slug' => 'agenda-tersembunyi',
            'start_datetime' => now()->addDay(),
            'is_published' => false,
        ]);
        $publicAnnouncement = Announcement::create([
            'title' => 'Pengumuman Publik',
            'slug' => 'pengumuman-publik',
            'content' => '<p>Publik.</p>',
            'is_published' => true,
        ]);
        $hiddenAnnouncement = Announcement::create([
            'title' => 'Pengumuman Tersembunyi',
            'slug' => 'pengumuman-tersembunyi',
            'content' => '<p>Tersembunyi.</p>',
            'is_published' => false,
        ]);

        $response = $this->get('https://pptalfalah.com/sitemap.xml');

        $this->assertTrue((new \DOMDocument)->loadXML($response->getContent()));

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('programs.show', $publicProgram->slug), false)
            ->assertSee(route('articles.show', $publicArticle->slug), false)
            ->assertSee(route('profil'), false)
            ->assertSee(route('agendas.show', $publicAgenda->slug), false)
            ->assertSee(route('announcements.show', $publicAnnouncement->slug), false)
            ->assertDontSee($hiddenProgram->slug, false)
            ->assertDontSee($draftArticle->slug, false)
            ->assertDontSee($hiddenPage->slug, false)
            ->assertDontSee($hiddenAgenda->slug, false)
            ->assertDontSee($hiddenAnnouncement->slug, false);
    }

    public function test_robots_points_to_sitemap_and_blocks_admin(): void
    {
        $robots = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Disallow: /admin', $robots);
        $this->assertStringContainsString('Sitemap: https://pptalfalah.com/sitemap.xml', $robots);
    }

    public function test_homepage_navigation_is_transparent_until_scrolled(): void
    {
        $this->get('https://pptalfalah.com/')
            ->assertOk()
            ->assertSee('data-home-navbar', false)
            ->assertSee('x-data="{ scrolled: window.scrollY > 24 }"', false)
            ->assertSee('@scroll.window="scrolled = window.scrollY > 24"', false)
            ->assertSee(':class="{ \'bg-primary/95 backdrop-blur-xl shadow-lg shadow-emerald-950/15\': scrolled }"', false)
            ->assertSee('fixed top-0', false)
            ->assertSee('text-white/80 hover:text-white', false)
            ->assertSee('max-w-[1600px]', false)
            ->assertSee('hidden xl:flex items-center gap-5 2xl:gap-7 whitespace-nowrap', false)
            ->assertSee('hidden xl:flex items-center gap-2 whitespace-nowrap', false)
            ->assertSee('xl:hidden p-2 rounded-xl', false)
            ->assertSee('class="xl:hidden absolute top-full', false)
            ->assertSee('Info PPDB', false);

        $this->get('https://pptalfalah.com/program')
            ->assertOk()
            ->assertDontSee('data-home-navbar', false)
            ->assertSee('bg-primary/95 backdrop-blur-xl shadow-lg shadow-emerald-950/15 sticky top-0', false);
    }

    public function test_only_the_current_desktop_navigation_item_is_active(): void
    {
        $response = $this->get('https://pptalfalah.com/fasilitas');
        $response->assertOk();

        $document = new \DOMDocument;
        $previousErrorHandling = libxml_use_internal_errors(true);
        $document->loadHTML($response->getContent());
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrorHandling);
        $activeItems = (new \DOMXPath($document))->query(
            '//nav//div[contains(@class, "hidden xl:flex")]//*[contains(concat(" ", normalize-space(@class), " "), " border-b-2 ")]'
        );

        $this->assertSame(1, $activeItems->length);
        $this->assertSame('Fasilitas', trim($activeItems->item(0)->textContent));
    }

    public function test_article_search_is_not_indexed_and_uses_clean_canonical(): void
    {
        $response = $this->get('https://pptalfalah.com/berita?search=tahfidz');

        $response
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex,follow"', false)
            ->assertSee('<link rel="canonical" href="https://pptalfalah.com/berita"', false);
    }
}
