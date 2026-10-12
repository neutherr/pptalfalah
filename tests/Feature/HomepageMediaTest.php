<?php

namespace Tests\Feature;

use App\Filament\Pages\HomepageSettings;
use App\Models\SiteSetting;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class HomepageMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_youtube_share_url_is_rendered_as_a_responsive_privacy_embed(): void
    {
        SiteSetting::set('about_media_type', 'video');
        SiteSetting::set('about_video_url', 'https://youtu.be/aqz-KE-bpKQ?si=example');

        $this->get('/')->assertOk()
            ->assertSee('src="https://www.youtube-nocookie.com/embed/aqz-KE-bpKQ"', false)
            ->assertSee('title="Video profil Al-Falah"', false)
            ->assertDontSee('autoplay', false);
    }

    public function test_direct_video_has_controls_and_uses_the_uploaded_image_as_poster(): void
    {
        SiteSetting::set('about_media_type', 'video');
        SiteSetting::set('about_video_url', 'https://media.example.com/profile.mp4?version=2');
        SiteSetting::set('about_image', 'homepage/profile.jpg');

        $this->get('/')->assertOk()->assertSee('<video', false)
            ->assertSee('controls playsinline preload="none"', false)
            ->assertSee('src="https://media.example.com/profile.mp4?version=2"', false)
            ->assertSee('poster="'.Storage::disk('public')->url('homepage/profile.jpg').'"', false);
    }

    public function test_image_mode_uses_uploaded_image_even_when_a_video_url_is_saved(): void
    {
        SiteSetting::set('about_media_type', 'image');
        SiteSetting::set('about_video_url', 'https://media.example.com/profile.mp4');
        SiteSetting::set('about_image', 'homepage/profile.jpg');

        $this->get('/')->assertOk()
            ->assertSee('src="'.Storage::disk('public')->url('homepage/profile.jpg').'"', false)
            ->assertDontSee('<video', false);
    }

    public function test_invalid_video_setting_falls_back_to_the_image(): void
    {
        SiteSetting::set('about_media_type', 'video');
        SiteSetting::set('about_video_url', 'javascript:alert(1)');

        $this->get('/')->assertOk()->assertDontSee('javascript:alert(1)', false)
            ->assertDontSee('<video', false)
            ->assertSee('alt="Program Informatika dan Agribisnis Al-Falah"', false);
    }

    public function test_admin_can_save_video_and_loading_text_revision_preserves_media(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs(User::factory()->create());

        Livewire::test(HomepageSettings::class)
            ->fillForm(['about_media_type' => 'video', 'about_video_url' => 'https://example.com/school.webm'])
            ->callAction('loadClientRevision')
            ->assertFormSet(['about_media_type' => 'video', 'about_video_url' => 'https://example.com/school.webm'])
            ->call('save')->assertHasNoFormErrors();

        $this->assertSame('video', SiteSetting::get('about_media_type'));
        $this->assertSame('https://example.com/school.webm', SiteSetting::get('about_video_url'));
        $this->get('/')->assertOk()->assertSee('src="https://example.com/school.webm"', false);
    }

    #[DataProvider('invalidVideos')]
    public function test_admin_cannot_save_an_invalid_or_missing_video(string $url): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs(User::factory()->create());

        Livewire::test(HomepageSettings::class)
            ->fillForm(['about_media_type' => 'video', 'about_video_url' => $url])
            ->call('save')->assertHasFormErrors(['about_video_url']);

        $this->assertNull(SiteSetting::get('about_video_url'));
    }

    public static function invalidVideos(): array
    {
        return [
            'empty' => [''],
            'script' => ['javascript:alert(1)'],
            'iframe html' => ['<iframe src="https://example.com"></iframe>'],
            'arbitrary website' => ['https://example.com/page'],
            'spoofed youtube' => ['https://youtube.com.evil.example/watch?v=aqz-KE-bpKQ'],
            'invalid youtube id' => ['https://youtu.be/not-a-valid-id'],
            'insecure video' => ['http://example.com/profile.mp4'],
            'array youtube id' => ['https://youtube.com/watch?v[]=aqz-KE-bpKQ'],
        ];
    }

    #[DataProvider('youtubeVideos')]
    public function test_supported_youtube_formats_render_the_same_canonical_embed(string $url): void
    {
        SiteSetting::set('about_media_type', 'video');
        SiteSetting::set('about_video_url', $url);

        $this->get('/')->assertOk()
            ->assertSee('src="https://www.youtube-nocookie.com/embed/aqz-KE-bpKQ"', false);
    }

    public static function youtubeVideos(): array
    {
        return [
            ['https://www.youtube.com/watch?v=aqz-KE-bpKQ&autoplay=1'],
            ['https://www.youtube.com/embed/aqz-KE-bpKQ'],
            ['https://www.youtube.com/shorts/aqz-KE-bpKQ'],
            ['https://www.youtube.com/live/aqz-KE-bpKQ'],
        ];
    }

    public function test_switching_to_image_preserves_video_for_later_use(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs(User::factory()->create());
        SiteSetting::set('about_media_type', 'video');
        SiteSetting::set('about_video_url', 'https://example.com/profile.mp4');

        Livewire::test(HomepageSettings::class)->fillForm(['about_media_type' => 'image'])
            ->call('save')->assertHasNoFormErrors();

        $this->assertSame('https://example.com/profile.mp4', SiteSetting::get('about_video_url'));
        $this->get('/')->assertOk()->assertDontSee('<video', false);
    }

    public function test_non_image_uploads_are_rejected(): void
    {
        Storage::fake('public');
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs(User::factory()->create());

        Livewire::test(HomepageSettings::class)
            ->fillForm(['about_image' => UploadedFile::fake()->create('script.php', 1, 'application/x-php')])
            ->call('save')->assertHasFormErrors(['about_image']);

        $this->assertEmpty(Storage::disk('public')->allFiles('homepage'));
    }

    public function test_admin_can_upload_an_image_and_keep_it_when_loading_the_text_preset(): void
    {
        Storage::fake('public');
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs(User::factory()->create());

        Livewire::test(HomepageSettings::class)
            ->fillForm(['about_media_type' => 'image', 'about_image' => UploadedFile::fake()->image('school.jpg')])
            ->callAction('loadClientRevision')->call('save')->assertHasNoFormErrors();

        $path = SiteSetting::get('about_image');
        $this->assertNotEmpty($path);
        Storage::disk('public')->assertExists($path);
        $this->get('/')->assertOk()->assertSee('src="'.Storage::disk('public')->url($path).'"', false);
    }
}
