@extends('layouts.app')

@section('meta_title', 'Fasilitas | ' . ($settings['site_name'] ?? 'Al-Falah Boarding School'))

@push('styles')
<style>
    /* Hide scrollbar for Chrome, Safari and Opera */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    /* Hide scrollbar for IE, Edge and Firefox */
    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>
@endpush

@section('content')
<div class="pt-24 pb-24 bg-surface min-h-screen relative" x-data="{ 
        lightboxOpen: false, 
        lightboxImages: [],
        lightboxIndex: 0,
        openLightbox(images, index) {
            this.lightboxImages = images;
            this.lightboxIndex = index;
            this.lightboxOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeLightbox() {
            this.lightboxOpen = false;
            document.body.style.overflow = '';
        },
        nextImage() {
            if (this.lightboxImages.length > 0) {
                this.lightboxIndex = (this.lightboxIndex + 1) % this.lightboxImages.length;
            }
        },
        prevImage() {
            if (this.lightboxImages.length > 0) {
                this.lightboxIndex = (this.lightboxIndex - 1 + this.lightboxImages.length) % this.lightboxImages.length;
            }
        }
    }"
    @keydown.escape.window="closeLightbox()"
    @keydown.right.window="if(lightboxOpen) nextImage()"
    @keydown.left.window="if(lightboxOpen) prevImage()">
    
    {{-- Aksen Latar --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/3 z-0 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {{-- Header Section --}}
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-black text-on-surface font-headline tracking-tight mb-4">
                Fasilitas <span class="text-primary">Unggulan</span>
            </h1>
            <p class="text-on-surface-variant text-lg max-w-2xl mx-auto">
                Lingkungan asri dan infrastruktur modern berpadu mendukung pertumbuhan inteltual, fisik, dan spiritual santri secara maksimal.
            </p>
        </div>

        {{-- Daftar Fasilitas --}}
        @if($facilities->count() > 0)
            <div class="space-y-20">
                @foreach($facilities as $facility)
                <div class="bg-white rounded-3xl p-8 border border-outline-variant/30 shadow-sm">
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold font-headline text-on-surface mb-3">{{ $facility->name }}</h2>
                        @if($facility->description)
                        <p class="text-on-surface-variant max-w-4xl text-lg leading-relaxed">{{ $facility->description }}</p>
                        @endif
                    </div>

                    {{-- Slider Gambar --}}
                    @if(!empty($facility->images) && count($facility->images) > 0)
                    @php
                        // Siapkan array URL penuh untuk Alpine JS
                        $fullImageUrls = array_map(function($img) {
                            return asset('storage/' . $img);
                        }, $facility->images);
                    @endphp
                    <div class="relative group" x-data="{
                        scrollNext() { this.$refs.slider.scrollBy({ left: 320, behavior: 'smooth' }); },
                        scrollPrev() { this.$refs.slider.scrollBy({ left: -320, behavior: 'smooth' }); }
                    }">
                        {{-- Tombol Panah Kiri --}}
                        <button @click="scrollPrev()" class="absolute -left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 backdrop-blur border border-outline-variant/30 text-primary rounded-full flex items-center justify-center shadow-lg z-10 hover:bg-primary hover:text-white transition-colors duration-300 opacity-0 md:group-hover:opacity-100 hidden md:flex">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>

                        {{-- Area Carousel --}}
                        <div x-ref="slider" class="flex overflow-x-auto gap-6 snap-x snap-mandatory no-scrollbar pb-6 pt-2">
                            @foreach($facility->images as $index => $img)
                            <div class="shrink-0 w-72 md:w-80 h-56 snap-center rounded-2xl overflow-hidden cursor-pointer relative group/item box-border shadow-sm border border-outline-variant/20 hover:border-primary/50 transition-all"
                                 @click="openLightbox({{ json_encode($fullImageUrls) }}, {{ $index }})">
                                <img src="{{ asset('storage/' . $img) }}" alt="Foto {{ $facility->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover/item:scale-110" loading="lazy">
                                <div class="absolute inset-0 bg-primary/0 group-hover/item:bg-primary/20 transition-colors duration-300 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-4xl opacity-0 group-hover/item:opacity-100 transition-opacity duration-300 drop-shadow-md">zoom_out_map</span>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Tombol Panah Kanan --}}
                        <button @click="scrollNext()" class="absolute -right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 backdrop-blur border border-outline-variant/30 text-primary rounded-full flex items-center justify-center shadow-lg z-10 hover:bg-primary hover:text-white transition-colors duration-300 opacity-0 md:group-hover:opacity-100 hidden md:flex">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>
                    @endif

                    {{-- Grid Video --}}
                    @if(!empty($facility->videos) && count($facility->videos) > 0)
                    <div class="mt-8 border-t border-outline-variant/30 pt-8">
                        <div class="flex items-center gap-2 mb-6 text-primary font-bold">
                            <span class="material-symbols-outlined">play_circle</span>
                            <span>Liputan Video</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($facility->videos as $video)
                                @php
                                    $embedUrl = $video['url'] ?? '';
                                    if(str_contains($embedUrl, 'watch?v=')) {
                                        $embedUrl = str_replace('watch?v=', 'embed/', $embedUrl);
                                        $embedUrl = explode('&', $embedUrl)[0];
                                    } elseif(str_contains($embedUrl, 'youtu.be/')) {
                                        $embedUrl = str_replace('youtu.be/', 'youtube.com/embed/', $embedUrl);
                                        $embedUrl = explode('?', $embedUrl)[0];
                                    }
                                @endphp
                                @if($embedUrl)
                                <div class="w-full rounded-2xl overflow-hidden aspect-video bg-neutral-900 shadow-md">
                                    <iframe src="{{ $embedUrl }}" class="w-full h-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-20 bg-white rounded-3xl border border-outline-variant/30 shadow-sm">
                <span class="material-symbols-outlined text-6xl text-on-surface-variant/30 mb-4 inline-block">business</span>
                <h3 class="text-2xl font-bold font-headline text-on-surface mb-2">Data Fasilitas Belum Tersedia</h3>
                <p class="text-on-surface-variant max-w-md mx-auto">Informasi fasilitas sedang dalam penyusunan oleh tata usaha.</p>
            </div>
        @endif
    </div>

    {{-- Lightbox / Layar Penuh --}}
    <div x-show="lightboxOpen" 
         style="display: none;" 
         class="fixed inset-0 z-[100] bg-black/95 flex items-center justify-center transition-opacity"
         x-transition.opacity.duration.300ms>
        
        {{-- Area Klik untuk Tutup --}}
        <div class="absolute inset-0 z-0" @click="closeLightbox()"></div>

        {{-- Tombol Tutup --}}
        <button @click="closeLightbox()" class="absolute top-6 right-6 text-white/50 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-full backdrop-blur-sm transition-all focus:outline-none z-20">
            <span class="material-symbols-outlined text-3xl block">close</span>
        </button>

        {{-- Navigasi Kiri --}}
        <button x-show="lightboxImages.length > 1" @click="prevImage()" class="absolute left-4 md:left-12 top-1/2 -translate-y-1/2 text-white/50 hover:text-white bg-white/10 hover:bg-white/20 p-3 md:p-4 rounded-full backdrop-blur-sm transition-all focus:outline-none z-20">
            <span class="material-symbols-outlined text-3xl md:text-5xl block">chevron_left</span>
        </button>

        {{-- Navigasi Kanan --}}
        <button x-show="lightboxImages.length > 1" @click="nextImage()" class="absolute right-4 md:right-12 top-1/2 -translate-y-1/2 text-white/50 hover:text-white bg-white/10 hover:bg-white/20 p-3 md:p-4 rounded-full backdrop-blur-sm transition-all focus:outline-none z-20">
            <span class="material-symbols-outlined text-3xl md:text-5xl block">chevron_right</span>
        </button>

        {{-- Indikator Urutan --}}
        <div x-show="lightboxImages.length > 1" class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white bg-black/50 px-4 py-2 rounded-full text-sm tracking-widest font-mono z-20">
            <span x-text="lightboxIndex + 1"></span> / <span x-text="lightboxImages.length"></span>
        </div>

        {{-- Gambar Utama --}}
        <div class="relative z-10 w-full h-full p-4 md:p-16 flex items-center justify-center pointer-events-none">
            <template x-for="(img, index) in lightboxImages" :key="index">
                <img x-show="index === lightboxIndex"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     :src="img" 
                     class="max-w-full max-h-full object-contain rounded-lg shadow-[0_0_50px_rgba(0,0,0,0.5)] pointer-events-auto" 
                     :alt="'Fasilitas Zoom ' + (index+1)">
            </template>
        </div>
    </div>

</div>
@endsection
