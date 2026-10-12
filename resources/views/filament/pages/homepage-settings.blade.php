<x-filament-panels::page>
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Atur teks beranda di sini. Gambar latar tetap dikelola melalui menu Hero Section.
        Tombol Muat revisi klien mengisi formulir; perubahan baru tampil setelah disimpan.
    </p>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}
        <x-filament::button type="submit" wire:loading.attr="disabled" wire:target="save">
            Simpan perubahan
        </x-filament::button>
    </form>
</x-filament-panels::page>
