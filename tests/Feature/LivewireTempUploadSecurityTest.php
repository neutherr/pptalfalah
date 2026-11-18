<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class LivewireTempUploadSecurityTest extends TestCase
{
    private function uploadUrl(): string
    {
        return URL::temporarySignedRoute('livewire.upload-file', now()->addMinutes(5));
    }

    public function test_php_file_is_rejected_and_never_reaches_public_disk(): void
    {
        Storage::fake('tmp-for-tests');
        Storage::fake('public');

        $this->post($this->uploadUrl(), [
            'files' => [UploadedFile::fake()->createWithContent('x.php', '<?php echo 1;')],
        ], ['Accept' => 'application/json'])->assertStatus(422);

        $this->assertEmpty(Storage::disk('public')->allFiles());
        $this->assertEmpty(Storage::disk('tmp-for-tests')->allFiles());
    }

    public function test_image_upload_still_works(): void
    {
        Storage::fake('tmp-for-tests'); // Livewire swaps the temp disk to this one in tests.

        $this->post($this->uploadUrl(), [
            'files' => [UploadedFile::fake()->image('foto.jpg')],
        ], ['Accept' => 'application/json'])->assertOk();
    }

    public function test_temp_uploads_use_private_disk(): void
    {
        $this->assertSame('local', config('livewire.temporary_file_upload.disk'));
        $this->assertSame('local', config('filesystems.disks.local.driver'));
        $this->assertStringNotContainsString('public', config('filesystems.disks.local.root'));
    }
}
