@extends('layouts.app')

@section('meta_title', ($settings['meta_title'] ?? 'Al-Falah Boarding School') . ' | Beranda')

@section('content')

{{-- ===== HERO SECTION ===== --}}
@php
    $fallbackSlides = [
        ['title' => 'Campus Landscape', 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCHgJD11gfmB0S5GJXUHI-Pb5FHYNg65149uCZiZiWKlm9fRuj0RmwZHSA14hyG8N9ipAaAlUToaNbg-vtuFTU2jpzyBCIOis-ylI7jPSByBSyf57MzQHda0L06vwKeJldaVo1hO2f7N0fSsb_lvi0qoe_HOzQVJzf84ZzAlUfQUW1gDn987QB2HpdDSpKXC6W5sECKXkoMzxh0NrDLVRl1SYTEFew_B8xjCPBlK-mf1LK7k7xItxCwG5S8d2E0qjw-Lk2GzkRoJ8o'],
        ['title' => 'Students Studying', 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCdiKuaWCrTGvdN0rsNO4bYxT4Ioh3XWNHfBwY8qw6M-Zx4XI0jKN9VRGVAxK2GQ8CRzvNXNzDr6Zv9A7MP9pdEs8EE4vNaepc5RgiOwKUhjzAYnyr-8kZMMy96EP0ADeaHSTY0_Ux-WobhLatDmDClwHJg1QZ0sJBUjU0aWKBm6sXmD348k6b7JP22IPTnzBEIOngz4lY3ZoOnSA_6e9Hg9VyfphJG9HI-allm3VXuQtFsdFUos_1O3GVTmUxj-85JxC__RJXS8nA'],
        ['title' => 'Agricultural Activities', 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDDcZsxG5SewyK8JZxLEfM1O5zIBC2vkdGlO4r-Zhn0mpCQDYXv9rGfnQVwqBTqC9wEVNRq2mk6hqd9EgOQLG1jiWa6yI9diYAiHK3HgQEnDAheVLSWlMDBT0pATmMTfuhDL6fV9VLfrt5k90rQI22z1QwkZsjwrbBQAEGGcJf0rWnNfRNXbYzr9GSp-1xGT4LltlBYkpmSyWk_ULyz9GNrvbTdMZgbWUyXmwQAd8rZhVLJPnb4CUUSGbtHATx53HBlgBBsSUj9Yuw']
    ];
    $displaySlides = $heroSlides->count() > 0 ? $heroSlides : $fallbackSlides;
    $totalSlides = count($displaySlides);
@endphp
<section class="relative min-h-screen flex items-center overflow-hidden bg-primary"
         x-data="{ activeSlide: 0, 
                   init() {
                       setInterval(() => {
                           this.activeSlide = this.activeSlide === {{ $totalSlides - 1 }} ? 0 : this.activeSlide + 1;
                       }, 5000);
                   }
         }">

    {{-- Slider Background --}}
    <div class="absolute inset-0 z-0">
        @foreach($displaySlides as $index => $slide)
            @php
                $imgSrc = is_array($slide) ? $slide['image'] : ($slide->image ? asset('storage/' . $slide->image) : $fallbackSlides[0]['image']);
                $imgTitle = is_array($slide) ? $slide['title'] : $slide->title;
            @endphp
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                 :class="{ 'opacity-100 z-10': activeSlide === {{ $index }}, 'opacity-0 z-0': activeSlide !== {{ $index }} }">
                <img alt="{{ $imgTitle }}"
                     class="w-full h-full object-cover origin-center transform transition-transform ease-out"
                     style="transition-duration: 5000ms"
                     :class="{ 'scale-105': activeSlide === {{ $index }}, 'scale-100': activeSlide !== {{ $index }} }"
                     src="{{ $imgSrc }}"
                     loading="{{ $loop->first ? 'eager' : 'lazy' }}"/>
                <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
            </div>
        @endforeach
    </div>

    {{-- Hero Content --}}
    <div class="max-w-7xl mx-auto px-6 lg:px-8 w-full relative z-10 py-20 lg:py-32">
        <div class="max-w-3xl">
            @if($activePpdb)
            <span class="inline-block px-4 py-1.5 rounded-full bg-secondary-fixed text-on-secondary-fixed text-xs font-bold uppercase tracking-[0.2em] mb-6">
                Penerimaan Santri Baru {{ $activePpdb->academic_year }}
            </span>
            @endif

            <h1 class="font-hero text-3xl sm:text-4xl lg:text-6xl font-extrabold text-white leading-[1.2] tracking-tighter mb-4 sm:mb-6 animate-on-scroll">
                {!! $settings['vision_headline'] ?? "Menanam Iman, Menuai Kemandirian<br/><span class='text-primary-fixed'>Pesantren Tahfidz</span>, <span class='text-secondary-fixed'>Informatika</span> & Agribisnis Terpadu" !!}
            </h1>
            <p class="text-base sm:text-lg lg:text-xl text-emerald-50/80 max-w-xl mb-8 sm:mb-10 leading-relaxed font-medium animate-on-scroll" style="animation-delay: 200ms;">
                {{ $settings['site_tagline'] ?? "SMK Al-Falah Boarding School" }}
            </p>

            <div class="flex flex-col sm:flex-row flex-wrap gap-4">
                <a class="w-full sm:w-auto px-6 py-3 sm:px-8 sm:py-4 bg-primary-container text-white border border-primary-fixed/30 rounded-full font-bold uppercase text-xs sm:text-sm tracking-widest flex items-center justify-center gap-2 hover:bg-primary transition-all shadow-xl text-center"
                   href="https://wa.me/{{ $settings['whatsapp_number'] ?? '6281510029919' }}?text={{ urlencode($settings['whatsapp_message'] ?? '') }}"
                   target="_blank"
                   id="click_whatsapp_home">
                    Daftar
                    <span class="material-symbols-outlined text-base sm:text-lg">arrow_forward</span>
                </a>

                @if(!empty($settings['active_brochure_url']))
                <a class="px-6 py-3 sm:px-8 sm:py-4 bg-secondary-container text-on-secondary-container rounded-full font-bold uppercase text-xs sm:text-sm tracking-widest hover:bg-secondary-fixed transition-all shadow-xl flex items-center justify-center gap-2 text-center"
                   href="{{ $settings['active_brochure_url'] }}"
                   target="_blank"
                   id="click_download_brochure_home"
                   download>
                    <span class="material-symbols-outlined text-base sm:text-lg">download</span>
                    Unduh Brosur PDF
                </a>
                @else
                <button class="px-6 py-3 sm:px-8 sm:py-4 bg-secondary-container text-on-secondary-container rounded-full font-bold uppercase text-xs sm:text-sm tracking-widest hover:bg-secondary-fixed transition-all shadow-xl flex items-center justify-center gap-2 text-center w-full sm:w-auto"
                        id="click_download_brochure_home"
                        onclick="alert('Brosur segera tersedia. Silakan hubungi admin via WhatsApp.')">
                    <span class="material-symbols-outlined text-base sm:text-lg">download</span>
                    Unduh Brosur PDF
                </button>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ===== WHO WE ARE ===== --}}
<section class="py-12 lg:py-24 relative overflow-hidden bg-surface-container">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            {{-- Bagian Teks --}}
            <div>
                <div class="inline-flex items-center gap-4 mb-4">
                    <span class="h-px w-10 bg-secondary/30"></span>
                    <span class="text-secondary font-bold tracking-[0.4em] uppercase text-xs">Who We Are</span>
                </div>
                <h2 class="font-headline text-3xl sm:text-4xl lg:text-5xl font-extrabold text-primary leading-tight mb-6">
                    Pioneer of Agri-Preneur & <br><span class="text-amber-500">Future Leaders</span>
                </h2>
                <div class="space-y-6 text-on-surface-variant text-lg leading-relaxed">
                    <p>
                        Berawal dari kesadaran, idealisme, dan semangat untuk melahirkan generasi muslim yang mandiri dan tangguh, kami memadukan generasi Qur'ani dengan kemandirian ekologi. Di atas keyakinan bahwa ketahanan umat berakar pada ketahanan pangan dan fondasi tauhid yang kuat, maka di Jonggol Utara ini dibangun sebuah rintisan lembaga pendidikan terpadu: <strong>Pondok Pesantren Tahfidz Al-Falah</strong>.
                    </p>
                    <p>
                        <strong>Kenapa Harus Memilih Al-Falah Boarding School?</strong><br>
                        Pondok Pesantren Tahfidz Al-Falah, didirikan oleh para praktisi bisnis, hafizh Qur'an dan juga aktivis dakwah, sangat memahami betul tentang pilihan arah dan jenjang pendidikan putra Anda saat ini. Oleh karena itu, kami telah mempersiapkan dengan sangat teliti, detail, dan menyeluruh 4 pilar keunggulan pembelajaran kami, yaitu:
                        <span class="block mt-3 font-semibold text-primary">
                            1. Tahfidz Al-Qur'an 30 Juz<br>
                            2. Pendidikan Boarding School Menyeluruh<br>
                            3. Program Keahlian Informatika<br>
                            4. Program Keahlian Agribisnis Terpadu
                        </span>
                    </p>
                </div>
            </div>

            {{-- Bagian Gambar Eksklusif --}}
            <div class="relative">
                <div class="absolute -inset-4 bg-primary/5 rounded-3xl transform rotate-3 z-0"></div>
                <div class="absolute -inset-4 bg-secondary/10 rounded-3xl transform -rotate-2 z-0"></div>
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDDcZsxG5SewyK8JZxLEfM1O5zIBC2vkdGlO4r-Zhn0mpCQDYXv9rGfnQVwqBTqC9wEVNRq2mk6hqd9EgOQLG1jiWa6yI9diYAiHK3HgQEnDAheVLSWlMDBT0pATmMTfuhDL6fV9VLfrt5k90rQI22z1QwkZsjwrbBQAEGGcJf0rWnNfRNXbYzr9GSp-1xGT4LltlBYkpmSyWk_ULyz9GNrvbTdMZgbWUyXmwQAd8rZhVLJPnb4CUUSGbtHATx53HBlgBBsSUj9Yuw" 
                     alt="Program Informatika dan Agribisnis Al-Falah" 
                     class="relative z-10 w-full h-[300px] lg:h-[500px] object-cover rounded-2xl shadow-xl">
            </div>
        </div>
    </div>
</section>

{{-- ===== VISI & MISI ===== --}}
<section class="py-12 lg:py-16 relative overflow-hidden bg-surface">
    <div class="absolute inset-0" style="background-image: url('{{ asset('assets/backgroundpattern.jpg') }}'); background-repeat: no-repeat; background-size: cover; background-position: center; opacity: 0.15;"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">

        {{-- Visi --}}
        <div class="text-center mb-16 max-w-5xl mx-auto">
            <div class="inline-flex items-center gap-4 mb-4 justify-center">
                <span class="h-px w-10 bg-secondary/30"></span>
                <span class="text-secondary font-bold tracking-[0.4em] uppercase text-[10px]">Visi</span>
                <span class="h-px w-10 bg-secondary/30"></span>
            </div>
            <h2 class="font-headline text-3xl sm:text-4xl lg:text-5xl font-black text-primary leading-tight tracking-tighter mb-6">
                {!! nl2br(e($settings['vision_headline'] ?? "Mencetak Generasi\nQur'ani, Mandiri,\ndan Berprestasi.")) !!}
            </h2>   
        </div>

        {{-- Misi --}}
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-4 justify-center">
                    <span class="h-px w-10 bg-secondary/30"></span>
                    <span class="text-secondary font-bold tracking-[0.4em] uppercase text-[10px]">Misi</span>
                    <span class="h-px w-10 bg-secondary/30"></span>
                </div>
            </div>
            <div class="space-y-8">
                @foreach($missions as $mission)
                <div class="group flex flex-col md:flex-row items-center md:items-start gap-4 md:gap-8 pb-8 border-b border-outline-variant/10 last:border-0">
                    <div class="shrink-0 flex flex-col items-center">
                        <div class="text-secondary/20 font-headline font-black text-4xl transition-colors group-hover:text-secondary/40">{{ $mission['number'] }}</div>
                        <div class="w-1 h-6 bg-secondary/10 rounded-full group-hover:bg-secondary/30 transition-all"></div>
                    </div>
                    <div class="text-center md:text-left">
                        <h4 class="font-headline text-xl font-bold text-primary mb-2 group-hover:text-secondary transition-colors">{{ $mission['title'] }}</h4>
                        <p class="text-on-surface-variant text-base leading-relaxed">{{ $mission['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ===== PROGRAM UNGGULAN ===== --}}
<section class="py-12 lg:py-24 bg-surface-container-low">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-headline text-3xl lg:text-4xl font-extrabold text-primary mb-4">Program Unggulan</h2>
            <div class="w-24 h-1.5 bg-secondary mx-auto rounded-full"></div>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @forelse($programs as $program)
            <a href="{{ $program->slug ? route('programs.show', $program->slug) : '#' }}" class="bg-surface-container-lowest rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all duration-500 group overflow-hidden flex flex-col hover:-translate-y-2">
                <div class="h-48 overflow-hidden relative">
                    <img alt="{{ $program->title }}"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                         src="{{ $program->image ? asset('storage/' . $program->image) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuCdiKuaWCrTGvdN0rsNO4bYxT4Ioh3XWNHfBwY8qw6M-Zx4XI0jKN9VRGVAxK2GQ8CRzvNXNzDr6Zv9A7MP9pdEs8EE4vNaepc5RgiOwKUhjzAYnyr-8kZMMy96EP0ADeaHSTY0_Ux-WobhLatDmDClwHJg1QZ0sJBUjU0aWKBm6sXmD348k6b7JP22IPTnzBEIOngz4lY3ZoOnSA_6e9Hg9VyfphJG9HI-allm3VXuQtFsdFUos_1O3GVTmUxj-85JxC__RJXS8nA' }}"
                         loading="lazy"/>
                    <div class="absolute inset-0 bg-primary/20"></div>
                </div>
                <div class="p-10 pt-8 flex-grow flex flex-col">
                    <h3 class="font-headline text-2xl font-bold text-primary mb-4 group-hover:text-primary-dark transition-colors">{{ $program->title }}</h3>
                    <p class="text-on-surface-variant leading-relaxed mb-6 flex-grow">{{ $program->description }}</p>
                    @if($program->bullet_points)
                    <ul class="space-y-3 text-sm font-semibold text-primary/80 mb-6">
                        @foreach(array_slice($program->bullet_points, 0, 3) as $bullet)
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-lg shrink-0">check_circle</span>
                            <span class="line-clamp-1">{{ is_array($bullet) ? ($bullet['point'] ?? '') : $bullet }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    <div class="pt-4 border-t border-outline-variant/30 flex justify-between items-center text-primary font-bold">
                        <span>Lihat Detail Program</span>
                        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </div>
                </div>
            </a>
            @empty
                <p class="col-span-3 text-center text-on-surface-variant">Program segera tersedia.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ===== PPDB SUMMARY ===== --}}
@if($activePpdb)
<section class="py-12 lg:py-24 bg-surface overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-5 gap-12 items-center">
            <div class="lg:col-span-2">
                <h2 class="font-headline text-3xl lg:text-4xl font-extrabold text-primary mb-6">
                    Penerimaan Santri Baru Tahun Ajaran {{ $activePpdb->academic_year }}
                </h2>
                <p class="text-on-surface-variant mb-8 leading-relaxed">
                    {{ $activePpdb->description ?? 'Jangan lewatkan kesempatan untuk bergabung dengan keluarga besar Al-Falah Boarding School. Ikuti tahapan seleksi berikut.' }}
                </p>

                {{-- Gelombang Aktif --}}
                @php $firstWave = $activePpdb->waves->first(); @endphp
                @if($firstWave)
                <div class="p-6 lg:p-8 bg-primary rounded-3xl text-white shadow-2xl">
                    <div class="flex items-center gap-4 mb-4">
                        <span class="material-symbols-outlined text-secondary-fixed text-4xl">event</span>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-primary-fixed">{{ $firstWave->name }}</p>
                            <p class="text-xl font-bold">
                                {{ $firstWave->registration_start->format('d M') }} - {{ $firstWave->registration_end->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <hr class="border-white/10 my-4"/>
                    <a href="{{ route('ppdb') }}"
                       class="block w-full py-4 bg-secondary-container text-on-secondary-container rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-secondary-fixed transition-colors text-center">
                        Lihat Syarat Pendaftaran
                    </a>
                </div>
                @endif
            </div>

            {{-- Alur Pendaftaran --}}
            <div class="lg:col-span-3 space-y-8">
                @foreach([
                    ['title' => $settings['ppdb_step_1_title'] ?? 'Registrasi Online / Offline', 'desc' => $settings['ppdb_step_1_desc'] ?? ''],
                    ['title' => $settings['ppdb_step_2_title'] ?? 'Tes Seleksi Akademik',        'desc' => $settings['ppdb_step_2_desc'] ?? ''],
                    ['title' => $settings['ppdb_step_3_title'] ?? 'Pengumuman & Daftar Ulang',   'desc' => $settings['ppdb_step_3_desc'] ?? ''],
                ] as $index => $step)
                <div class="flex gap-6 items-start relative {{ !$loop->last ? 'pb-8' : '' }} group">
                    @if(!$loop->last)
                    <div class="absolute left-6 top-12 bottom-0 w-0.5 bg-outline-variant/30 hidden md:block"></div>
                    @endif
                    <div class="w-12 h-12 rounded-full bg-surface-container-highest flex items-center justify-center shrink-0 border-4 border-surface group-hover:bg-primary transition-colors">
                        <span class="text-lg font-bold text-primary group-hover:text-white">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div>
                        <h4 class="font-headline text-xl font-bold text-primary">{{ $step['title'] }}</h4>
                        <p class="text-on-surface-variant">{{ $step['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
</section>
@endif

{{-- ===== BERITA TERKINI ===== --}}
<section class="py-12 lg:py-24 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <span class="text-secondary font-bold tracking-[0.2em] uppercase text-xs block mb-4">Informasi Terupdate</span>
            <h2 class="font-headline text-3xl lg:text-4xl font-extrabold text-primary mb-4">Berita & Artikel Terkini</h2>
            <p class="text-on-surface-variant max-w-2xl mx-auto">Tetap terhubung dengan kegiatan dan prestasi terbaru kami sebagai wujud nyata dedikasi dalam mencetak generasi unggul.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 mb-16">
            @forelse($latestArticles as $article)
            <article class="group bg-surface-container-low rounded-[2rem] overflow-hidden border border-outline-variant/30 flex flex-col transition-all duration-300 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-1 cursor-pointer"
                     onclick="window.location.href='{{ route('articles.show', $article->slug) }}'">
                <div class="relative h-56 overflow-hidden">
                    <img alt="{{ $article->title }}"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuCdiKuaWCrTGvdN0rsNO4bYxT4Ioh3XWNHfBwY8qw6M-Zx4XI0jKN9VRGVAxK2GQ8CRzvNXNzDr6Zv9A7MP9pdEs8EE4vNaepc5RgiOwKUhjzAYnyr-8kZMMy96EP0ADeaHSTY0_Ux-WobhLatDmDClwHJg1QZ0sJBUjU0aWKBm6sXmD348k6b7JP22IPTnzBEIOngz4lY3ZoOnSA_6e9Hg9VyfphJG9HI-allm3VXuQtFsdFUos_1O3GVTmUxj-85JxC__RJXS8nA' }}"
                         loading="lazy"/>
                    @if($article->category)
                    <div class="absolute top-4 left-4 text-white text-[10px] font-bold uppercase tracking-widest px-4 py-1.5 rounded-full"
                         style="background-color: {{ $article->category->color ?? '#002c13' }}">
                        {{ $article->category->name }}
                    </div>
                    @endif
                </div>
                <div class="p-8 flex-grow flex flex-col">
                    <div class="flex items-center gap-3 text-xs text-on-surface-variant mb-4 font-semibold uppercase tracking-wider">
                        <span>{{ $article->published_at?->format('d M Y') }}</span>
                        <span class="w-1 h-1 bg-outline rounded-full"></span>
                        <span>{{ $article->author?->name ?? 'Admin' }}</span>
                    </div>
                    <h3 class="font-headline text-xl font-bold text-primary mb-3 leading-tight group-hover:text-secondary transition-colors">
                        {{ $article->title }}
                    </h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed mb-6 line-clamp-3">
                        {{ $article->excerpt ?: Str::limit(strip_tags($article->content), 150) }}
                    </p>
                    <div class="mt-auto">
                        <a class="inline-flex items-center gap-2 text-primary font-bold text-xs uppercase tracking-widest hover:gap-3 transition-all"
                           href="{{ route('articles.show', $article->slug) }}">
                            Baca Selengkapnya
                            <span class="material-symbols-outlined text-sm">trending_flat</span>
                        </a>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-3 text-center py-16 text-on-surface-variant">
                <span class="material-symbols-outlined text-5xl mb-4 block text-outline">newspaper</span>
                <p class="font-medium">Belum ada artikel yang terbit.</p>
            </div>
            @endforelse
        </div>

        @if($latestArticles->isNotEmpty())
        <div class="text-center">
            <a href="{{ route('articles.index') }}"
               class="px-10 py-4 bg-primary text-white rounded-full font-bold uppercase text-xs tracking-widest hover:bg-primary-container transition-all shadow-xl shadow-primary/10 inline-block">
                Lihat Semua Berita
            </a>
        </div>
        @endif
    </div>
    <div class="absolute -top-12 -left-12 w-64 h-64 bg-secondary/5 rounded-full blur-3xl"></div>
</section>

{{-- ===== GALERI ===== --}}
<section class="py-12 lg:py-24 bg-surface-container-low overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative">
        <div class="text-center mb-16">
            <h2 class="font-headline text-3xl lg:text-4xl font-extrabold text-primary mb-4 tracking-tight">Galeri Kegiatan & Fasilitas</h2>
            <div class="w-24 h-1.5 bg-secondary mx-auto rounded-full"></div>
        </div>

        <div class="relative group" x-data="{
            active: 0,
            total: {{ $featuredGallery->isNotEmpty() ? $featuredGallery->count() : 3 }},
            init() {
                let options = { root: this.$refs.slider, rootMargin: '0px', threshold: 0.5 };
                let observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.active = Number(entry.target.dataset.index);
                        }
                    });
                }, options);
                
                Array.from(this.$refs.slider.children).forEach((child, i) => {
                    child.dataset.index = i;
                    observer.observe(child);
                });
            },
            scrollNext() {
                if (this.active < this.total - 1) {
                    this.$refs.slider.children[this.active + 1].scrollIntoView({behavior: 'smooth', block: 'nearest', inline: 'center'});
                }
            },
            scrollPrev() {
                if (this.active > 0) {
                    this.$refs.slider.children[this.active - 1].scrollIntoView({behavior: 'smooth', block: 'nearest', inline: 'center'});
                }
            },
            goTo(i) {
                this.$refs.slider.children[i].scrollIntoView({behavior: 'smooth', block: 'nearest', inline: 'center'});
            }
        }">
            <div x-ref="slider" class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar gap-6 pb-12">
                @forelse($featuredGallery as $item)
                <div class="min-w-full md:min-w-[70%] snap-center shrink-0">
                    <div class="relative h-[350px] lg:h-[500px] rounded-[2rem] overflow-hidden group/slide shadow-2xl">
                        <img alt="{{ $item->title }}"
                             class="w-full h-full object-cover transition-transform duration-1000 group-hover/slide:scale-110"
                             src="{{ $item->image ? asset('storage/' . $item->image) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuCdiKuaWCrTGvdN0rsNO4bYxT4Ioh3XWNHfBwY8qw6M-Zx4XI0jKN9VRGVAxK2GQ8CRzvNXNzDr6Zv9A7MP9pdEs8EE4vNaepc5RgiOwKUhjzAYnyr-8kZMMy96EP0ADeaHSTY0_Ux-WobhLatDmDClwHJg1QZ0sJBUjU0aWKBm6sXmD348k6b7JP22IPTnzBEIOngz4lY3ZoOnSA_6e9Hg9VyfphJG9HI-allm3VXuQtFsdFUos_1O3GVTmUxj-85JxC__RJXS8nA' }}"
                             loading="lazy"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-transparent to-transparent flex flex-col justify-end p-12">
                            @if($item->category)
                            <span class="text-secondary-fixed font-bold uppercase tracking-widest text-sm mb-2">{{ $item->category->name }}</span>
                            @endif
                            <h3 class="text-3xl font-headline font-bold text-white">{{ $item->title }}</h3>
                            @if($item->caption)
                            <p class="text-white/80 mt-4 max-w-xl">{{ $item->caption }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                {{-- Fallback slides jika galeri kosong --}}
                @foreach([
                    ['label' => 'Spiritualitas', 'title' => "Tahfidz Al-Qur'an Intensif",    'caption' => "Membentuk karakter qur'ani melalui bimbingan intensif."],
                    ['label' => 'Kemandirian',   'title' => 'Informatika & Agribisnis',       'caption' => 'Integrasi teknologi digital dan inovasi agrikultur modern.'],
                    ['label' => 'Akademik',      'title' => 'Ruang Belajar Kolaboratif',      'caption' => 'Fasilitas modern untuk proses belajar mengajar.'],
                ] as $i => $slide)
                <div class="min-w-full md:min-w-[70%] snap-center shrink-0">
                    <div class="relative h-[350px] lg:h-[500px] rounded-[2rem] overflow-hidden group/slide shadow-2xl">
                        <img alt="{{ $slide['title'] }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover/slide:scale-110"
                             src="https://lh3.googleusercontent.com/aida-public/AB6AXuCdiKuaWCrTGvdN0rsNO4bYxT4Ioh3XWNHfBwY8qw6M-Zx4XI0jKN9VRGVAxK2GQ8CRzvNXNzDr6Zv9A7MP9pdEs8EE4vNaepc5RgiOwKUhjzAYnyr-8kZMMy96EP0ADeaHSTY0_Ux-WobhLatDmDClwHJg1QZ0sJBUjU0aWKBm6sXmD348k6b7JP22IPTnzBEIOngz4lY3ZoOnSA_6e9Hg9VyfphJG9HI-allm3VXuQtFsdFUos_1O3GVTmUxj-85JxC__RJXS8nA"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-transparent to-transparent flex flex-col justify-end p-12">
                            <span class="text-secondary-fixed font-bold uppercase tracking-widest text-sm mb-2">{{ $slide['label'] }}</span>
                            <h3 class="text-3xl font-headline font-bold text-white">{{ $slide['title'] }}</h3>
                            <p class="text-white/80 mt-4 max-w-xl">{{ $slide['caption'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
                @endforelse
            </div>

            {{-- Navigation Arrows --}}
            <button class="absolute left-4 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white transition-all shadow-xl border border-white/20"
                    @click="scrollPrev()"
                    x-show="active > 0"
                    x-transition
                    aria-label="Slide sebelumnya">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button class="absolute right-4 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white transition-all shadow-xl border border-white/20"
                    @click="scrollNext()"
                    x-show="active < total - 1"
                    x-transition
                    aria-label="Slide berikutnya">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>

            {{-- Pagination Dots --}}
            <div class="flex justify-center gap-3 mt-8">
                <template x-for="i in total" :key="i">
                    <button @click="goTo(i - 1)" 
                            class="h-2 rounded-full transition-all focus:outline-none"
                            :class="active === (i - 1) ? 'w-12 bg-primary' : 'w-2 bg-outline-variant hover:bg-primary/50'"></button>
                </template>
            </div>
        </div>

        @if($featuredGallery->isNotEmpty())
        <div class="text-center mt-8">
            <a href="{{ route('gallery') }}"
               class="inline-flex items-center gap-2 px-8 py-3 border-2 border-primary text-primary rounded-full font-bold text-xs uppercase tracking-widest hover:bg-primary hover:text-white transition-all">
                Lihat Semua Galeri
                <span class="material-symbols-outlined text-base">photo_library</span>
            </a>
        </div>
        @endif
    </div>
</section>

@endsection
