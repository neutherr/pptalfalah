@extends('layouts.app')

@section('content')
<div x-data="{ lightboxOpen: false, lightboxSrc: '', lightboxTitle: '' }" class="pt-32 pb-24 bg-surface min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Elegant Header Section --}}
        <div class="flex flex-col lg:flex-row justify-between items-end gap-8 mb-16 border-b border-outline-variant/30 pb-10">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary text-sm font-bold rounded-full mb-4">
                    <span class="material-symbols-outlined text-[16px]">photo_camera</span>
                    Eksplorasi Visual
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-on-surface mb-4 font-headline tracking-tight leading-tight">
                    Galeri <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-emerald-400">Kegiatan & Fasilitas</span>
                </h1>
                <p class="text-on-surface-variant text-lg">
                    Sebuah rekam jejak visual aktivitas, dedikasi, dan keseharian santri kami di {{ $settings['institution_name'] ?? 'Al-Falah Boarding School' }}.
                </p>
            </div>
            
            {{-- Categories Filter (Integrated into header) --}}
            @if($categories->isNotEmpty())
            <div class="flex flex-wrap gap-2 lg:justify-end shrink-0 w-full lg:w-auto">
                <a href="{{ route('gallery') }}" class="px-5 py-2.5 rounded-full text-sm font-bold transition-all {{ !request('category') ? 'bg-on-surface text-surface shadow-xl' : 'bg-surface-container text-on-surface hover:bg-surface-container-highest' }}">
                    Semua
                </a>
                @foreach($categories as $category)
                <a href="{{ route('gallery', ['category' => $category->slug]) }}" class="px-5 py-2.5 rounded-full text-sm font-bold transition-all {{ request('category') === $category->slug ? 'bg-primary text-white shadow-xl shadow-primary/20' : 'bg-surface-container text-on-surface hover:bg-surface-container-highest' }}">
                    {{ $category->name }}
                </a>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Standard Uniform Gallery Grid --}}
        @if($items->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($items as $item)
                <div class="group relative rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-700 bg-surface-container cursor-pointer"
                     @click="lightboxSrc = '{{ asset('storage/' . $item->image) }}'; lightboxTitle = '{{ addslashes($item->title) }}'; lightboxOpen = true">
                    
                    {{-- Fixed Aspect Ratio for Uniform Sizes --}}
                    <div class="relative w-full h-72 sm:h-80 overflow-hidden">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" loading="lazy">
                        
                        {{-- Elegant Blur Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        {{-- Content Slide-Up --}}
                        <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 translate-y-8 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 ease-out">
                            @if($item->category)
                                <div class="mb-3">
                                    <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md text-white text-xs font-bold rounded-lg border border-white/20">
                                        {{ $item->category->name }}
                                    </span>
                                </div>
                            @endif
                            <h3 class="text-2xl font-bold text-white font-headline leading-tight mb-2">{{ $item->title }}</h3>
                            @if($item->caption)
                                <p class="text-white/80 text-sm line-clamp-3 leading-relaxed">{{ $item->caption }}</p>
                            @endif
                            
                            {{-- Interactive Enlarge Button (for UX feel) --}}
                            <div class="mt-4 flex items-center gap-2 text-white/90 text-xs font-bold uppercase tracking-wider">
                                <span class="material-symbols-outlined text-[16px]">zoom_in</span> Lihat Gambar
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($items->hasPages())
            <div class="mt-20 flex justify-center">
                {{ $items->links() }}
            </div>
            @endif

        @else
            {{-- EMPTY STATE PREMUM --}}
            <div class="flex flex-col items-center justify-center py-32 bg-gradient-to-b from-surface-container-lowest to-surface-container-low rounded-[3rem] border border-outline-variant/30 text-center px-4">
                <div class="w-24 h-24 bg-primary/10 rounded-full flex items-center justify-center mb-6 text-primary">
                    <span class="material-symbols-outlined text-4xl">photo_library</span>
                </div>
                <h3 class="text-3xl font-black text-on-surface mb-4 font-headline">Galeri Belum Tersedia</h3>
                <p class="text-on-surface-variant max-w-lg mx-auto text-lg">Album dan dokumentasi kegiatan saat ini belum diunggah. Kami akan segera memperbarui koleksi visual ini.</p>
            </div>
        @endif

    </div>

    {{-- Lightbox Modal (Alpine.js) --}}
    <div x-show="lightboxOpen" style="display: none;"
         class="fixed inset-0 z-[999] flex items-center justify-center p-4 sm:p-6 bg-black/90 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
        {{-- Close Overlay --}}
        <div class="absolute inset-0 cursor-pointer" @click="lightboxOpen = false"></div>
        
        {{-- Close Button --}}
        <button @click="lightboxOpen = false" class="absolute top-6 right-6 z-10 w-12 h-12 flex items-center justify-center bg-white/10 hover:bg-white/20 text-white rounded-full backdrop-blur-md transition-colors">
            <span class="material-symbols-outlined text-2xl">close</span>
        </button>

        {{-- Image Container --}}
        <div class="relative z-10 max-w-5xl w-full max-h-screen flex flex-col items-center justify-center"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-8">
            <img :src="lightboxSrc" :alt="lightboxTitle" class="max-w-full max-h-[80vh] object-contain rounded-xl shadow-2xl">
            <h3 x-text="lightboxTitle" class="text-white text-xl md:text-2xl font-headline mt-6 text-center drop-shadow-md"></h3>
        </div>
    </div>
</div>
@endsection
