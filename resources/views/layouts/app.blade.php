<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>

    {{-- Dynamic SEO --}}
    <title>@yield('meta_title', $settings['meta_title'] ?? config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', $settings['meta_description'] ?? '')"/>
    <meta property="og:title" content="@yield('meta_title', $settings['meta_title'] ?? config('app.name'))"/>
    <meta property="og:description" content="@yield('meta_description', $settings['meta_description'] ?? '')"/>
    <meta property="og:image" content="@yield('og_image', $settings['og_image'] ?? '')"/>
    <meta property="og:type" content="website"/>
    <link rel="canonical" href="{{ url()->current() }}"/>

    {{-- Favicon --}}
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/LOGO1.jpeg') }}" />
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('assets/LOGO1.jpeg') }}" />
    <link rel="apple-touch-icon" href="{{ asset('assets/LOGO1.jpeg') }}" />

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries,typography"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#002c13",
                        "outline-variant": "#c0c9be",
                        "on-primary-fixed": "#00210d",
                        "tertiary-fixed-dim": "#88d982",
                        "secondary": "#785900",
                        "on-error-container": "#93000a",
                        "primary-container": "#014421",
                        "surface-tint": "#306a43",
                        "on-secondary-container": "#6c5000",
                        "on-primary": "#ffffff",
                        "surface-container": "#edeeed",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-highest": "#e1e3e2",
                        "tertiary-container": "#00450d",
                        "surface-dim": "#d9dad9",
                        "outline": "#717970",
                        "on-primary-container": "#76b284",
                        "surface-container-high": "#e7e8e7",
                        "on-surface-variant": "#404941",
                        "on-error": "#ffffff",
                        "error": "#ba1a1a",
                        "on-tertiary": "#ffffff",
                        "surface-bright": "#f9f9f8",
                        "error-container": "#ffdad6",
                        "on-tertiary-fixed": "#002204",
                        "primary-fixed-dim": "#97d5a5",
                        "inverse-primary": "#97d5a5",
                        "on-tertiary-container": "#66b664",
                        "on-background": "#191c1c",
                        "surface-container-low": "#f3f4f3",
                        "tertiary-fixed": "#a3f69c",
                        "inverse-on-surface": "#f0f1f0",
                        "secondary-fixed-dim": "#fabd00",
                        "background": "#f9f9f8",
                        "on-secondary": "#ffffff",
                        "on-secondary-fixed-variant": "#5b4300",
                        "surface-variant": "#e1e3e2",
                        "inverse-surface": "#2e3131",
                        "secondary-fixed": "#ffdf9e",
                        "tertiary": "#002c06",
                        "on-tertiary-fixed-variant": "#005312",
                        "primary-fixed": "#b2f1bf",
                        "surface": "#f9f9f8",
                        "secondary-container": "#fdc003",
                        "on-secondary-fixed": "#261a00",
                        "on-surface": "#191c1c",
                        "on-primary-fixed-variant": "#14512d"
                    },
                    borderRadius: {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    fontFamily: {
                        "headline": ["Plus Jakarta Sans"],
                        "body": ["Manrope"],
                        "label": ["Manrope"]
                    }
                },
            },
        }
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24
        }
        .glass-nav {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px)
        }
        .no-scrollbar::-webkit-scrollbar { display: none }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none }
        .premium-blur {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px)
        }
        .islamic-pattern-custom {
            background-image: url(https://lh3.googleusercontent.com/aida/ADBb0ujNA4-KUxCVK5sg3Xiz3dwHbNRRHvWhqsBmskN4s4hzEN10SoGudEDnSVkQrjajyrOFDrv6BbsHV0El5f0f9g4g8FEVwvcR-OSkuw3ECpzvjeu5fwhBOQMUIsAHsbFZ_z0LFsNp_ltrhmlJbKuBRB9lVPGODsFkKTxhX2tYKCDPM098tYwuzbyDvYX_2CLE18r14USZ0dPvRjT1wabpf9URLnmosqjc2ExmMuxI6Vjb2texbb17fXyLw4s1iaz7nJTNsbWQQKje);
            background-size: 600px;
            background-repeat: repeat;
            opacity: 0.04
        }
        @yield('styles')
    </style>

    {{-- Google Analytics --}}
    @if(!empty($settings['google_analytics_id']))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['google_analytics_id'] }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $settings['google_analytics_id'] }}');
    </script>
    @endif
</head>
<body class="bg-surface font-body text-on-background">

{{-- ===== TOP NAVIGATION ===== --}}
<nav class="bg-white/80 dark:bg-emerald-950/80 backdrop-blur-xl shadow-sm shadow-emerald-900/5 top-0 sticky z-[100]">
    <div class="flex justify-between items-center w-full px-6 lg:px-8 py-4 max-w-7xl mx-auto">

        {{-- Brand --}}
        <a href="{{ route('home') }}" class="flex items-center gap-4">
            <img src="{{ asset('assets/LOGO2.png') }}" alt="Logo {{ $settings['institution_name'] ?? 'Al-Falah' }}" class="h-14 w-auto object-contain drop-shadow-md">
            <div class="flex flex-col">
                <span class="text-xs font-headline font-bold text-emerald-800/60 uppercase tracking-widest line-clamp-1">
                    {{ $settings['institution_name'] ?? 'Pondok Pesantren Tahfidz Al-Falah' }}
                </span>
                <span class="text-xl font-bold leading-tight text-emerald-900 dark:text-emerald-50 font-headline tracking-tight line-clamp-1">
                    {{ $settings['site_name'] ?? 'SMK Al-Falah Boarding School' }}
                </span>
            </div>
        </a>

        {{-- Desktop Nav --}}
        <div class="hidden lg:flex items-center space-x-8 tracking-tight text-sm font-semibold font-headline">
            <a href="{{ route('home') }}"
               class="{{ request()->routeIs('home') ? 'text-amber-600 dark:text-amber-400 border-b-2 border-amber-600 pb-1' : 'text-emerald-800/70 dark:text-emerald-100/70 hover:text-emerald-900 transition-colors duration-300' }}">
                Beranda
            </a>

            {{-- Profil Dropdown --}}
            <div class="relative" x-data="{ open: false }" @click.outside="open = false" @mouseenter="open = true" @mouseleave="open = false">
                <button @click="open = !open"
                        class="{{ request()->is('halaman/*') || request()->routeIs('profil') ? 'text-amber-600 dark:text-amber-400 border-b-2 border-amber-600 pb-1' : 'text-emerald-800/70 dark:text-emerald-100/70 hover:text-emerald-900' }} transition-colors duration-300 flex items-center gap-1 pb-1">
                    Profil
                    <span class="material-symbols-outlined text-base transition-transform duration-300" :class="{ 'rotate-180': open }">expand_more</span>
                </button>
                <div x-show="open" x-transition.opacity.duration.200ms
                     class="absolute top-full left-0 pt-2 w-56 z-50">
                    <div class="bg-white rounded-xl shadow-xl border border-outline-variant/30 py-2 overflow-hidden">
                        <a href="{{ route('profil') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-lg text-primary/70">account_balance</span> Profil Pesantren
                        </a>
                        <a href="{{ route('page.show', 'profil-pengajar') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-lg text-primary/70">groups</span> Dewan Asatidz/Pengajar
                        </a>
                        <a href="{{ route('page.show', 'sejarah-yayasan') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-lg text-primary/70">history_edu</span> Sejarah Yayasan
                        </a>
                    </div>
                </div>
            </div>

            <a href="{{ route('programs.index') }}"
               class="{{ request()->routeIs('programs.*') ? 'text-amber-600 dark:text-amber-400 border-b-2 border-amber-600 pb-1' : 'text-emerald-800/70 dark:text-emerald-100/70 hover:text-emerald-900 transition-colors duration-300' }}">
                Program
            </a>
            
            <a href="{{ route('fasilitas') }}"
               class="{{ request()->routeIs('fasilitas') ? 'text-amber-600 dark:text-amber-400 border-b-2 border-amber-600 pb-1' : 'text-emerald-800/70 dark:text-emerald-100/70 hover:text-emerald-900 transition-colors duration-300' }}">
                Fasilitas
            </a>
            
            <a href="{{ route('ppdb') }}"
               class="{{ request()->routeIs('ppdb') ? 'text-amber-600 dark:text-amber-400 border-b-2 border-amber-600 pb-1' : 'text-emerald-800/70 dark:text-emerald-100/70 hover:text-emerald-900 transition-colors duration-300' }}">
                PPDB
            </a>

            {{-- Informasi Dropdown --}}
            <div class="relative" x-data="{ open: false }" @click.outside="open = false" @mouseenter="open = true" @mouseleave="open = false">
                <button @click="open = !open"
                        class="{{ request()->routeIs('articles.*') || request()->routeIs('agendas.*') || request()->routeIs('announcements.*') || request()->routeIs('gallery') ? 'text-amber-600 dark:text-amber-400 border-b-2 border-amber-600 pb-1' : 'text-emerald-800/70 dark:text-emerald-100/70 hover:text-emerald-900' }} transition-colors duration-300 flex items-center gap-1 pb-1">
                    Informasi Pendaftaran
                    <span class="material-symbols-outlined text-base transition-transform duration-300" :class="{ 'rotate-180': open }">expand_more</span>
                </button>
                <div x-show="open" x-transition.opacity.duration.200ms
                     class="absolute top-full right-0 pt-2 w-56 z-50">
                     <div class="bg-white rounded-xl shadow-xl border border-outline-variant/30 py-2 overflow-hidden">
                        <a href="{{ route('articles.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-lg text-primary/70">newspaper</span> Berita & Artikel
                        </a>
                        <a href="{{ route('agendas.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-lg text-primary/70">calendar_month</span> Kalender Akademik
                        </a>
                        <a href="{{ route('announcements.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-lg text-primary/70">campaign</span> Pengumuman
                        </a>
                        <a href="{{ route('gallery') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-lg text-primary/70">photo_library</span> Galeri Pesantren
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA + Mobile Toggle --}}
        <div class="flex items-center gap-3">
            <a href="https://wa.me/{{ $settings['whatsapp_number'] ?? '6281510029919' }}?text={{ urlencode($settings['whatsapp_message'] ?? '') }}"
               target="_blank"
               id="click_whatsapp_nav"
               class="bg-primary hover:bg-primary-container text-white px-5 py-2.5 rounded-full text-sm font-bold uppercase tracking-wider scale-95 active:scale-90 transition-all duration-300 shadow-lg shadow-primary/20 hidden sm:flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">chat</span> Hubungi Admin
            </a>

            {{-- Hamburger Mobile --}}
            <button class="lg:hidden p-2 rounded-xl bg-surface-container text-primary"
                    x-data
                    @click="$dispatch('toggle-menu')"
                    aria-label="Toggle Menu">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-data="{ open: false, openProfil: false, openInfo: false }" @toggle-menu.window="open = !open"
         x-show="open" x-transition
         class="lg:hidden border-t border-outline-variant/20 bg-white/95 backdrop-blur-xl max-h-[80vh] overflow-y-auto pb-6 shadow-2xl">
        <div class="px-6 py-4 space-y-1">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'bg-primary text-white' : 'text-on-surface hover:bg-surface-container' }}">Beranda</a>
            
            {{-- Profil Mobile Dropdown --}}
            <button @click="openProfil = !openProfil" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold {{ request()->is('halaman/*') || request()->routeIs('profil') ? 'bg-primary text-white' : 'text-on-surface hover:bg-surface-container' }}">
                <span>Profil</span>
                <span class="material-symbols-outlined transition-transform" :class="{ 'rotate-180': openProfil }">expand_more</span>
            </button>
            <div x-show="openProfil" x-collapse class="px-4 py-2 space-y-1 bg-surface-container-low rounded-xl mb-2 mt-1 border border-outline-variant/10">
                <a href="{{ route('profil') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-on-surface-variant hover:text-primary hover:bg-surface-container">
                    <span class="material-symbols-outlined text-base">account_balance</span> Profil Pesantren
                </a>
                <a href="{{ route('page.show', 'profil-pengajar') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-on-surface-variant hover:text-primary hover:bg-surface-container">
                    <span class="material-symbols-outlined text-base">groups</span> Dewan Asatidz
                </a>
                <a href="{{ route('page.show', 'sejarah-yayasan') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-on-surface-variant hover:text-primary hover:bg-surface-container">
                    <span class="material-symbols-outlined text-base">history_edu</span> Sejarah Yayasan
                </a>
            </div>

            <a href="{{ route('programs.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('programs.*') ? 'bg-primary text-white' : 'text-on-surface hover:bg-surface-container' }}">Program</a>
            <a href="{{ route('fasilitas') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('fasilitas') ? 'bg-primary text-white' : 'text-on-surface hover:bg-surface-container' }}">Fasilitas</a>
            <a href="{{ route('ppdb') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('ppdb') ? 'bg-primary text-white' : 'text-on-surface hover:bg-surface-container' }}">PPDB</a>
            
            {{-- Informasi Mobile Dropdown --}}
            <button @click="openInfo = !openInfo" class="w-full flex items-center justify-between px-4 py-3 mt-2 rounded-xl text-sm font-semibold {{ request()->routeIs('articles.*', 'agendas.*', 'announcements.*', 'gallery') ? 'bg-primary text-white' : 'text-on-surface hover:bg-surface-container' }}">
                <span>Informasi Publik</span>
                <span class="material-symbols-outlined transition-transform" :class="{ 'rotate-180': openInfo }">expand_more</span>
            </button>
            <div x-show="openInfo" x-collapse class="px-4 py-2 space-y-1 bg-surface-container-low rounded-xl mt-1 border border-outline-variant/10">
                <a href="{{ route('articles.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-on-surface-variant hover:text-primary hover:bg-surface-container">
                    <span class="material-symbols-outlined text-base">newspaper</span> Berita & Artikel
                </a>
                <a href="{{ route('agendas.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-on-surface-variant hover:text-primary hover:bg-surface-container">
                    <span class="material-symbols-outlined text-base">calendar_month</span> Agenda
                </a>
                <a href="{{ route('announcements.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-on-surface-variant hover:text-primary hover:bg-surface-container">
                    <span class="material-symbols-outlined text-base">campaign</span> Pengumuman
                </a>
                <a href="{{ route('gallery') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-on-surface-variant hover:text-primary hover:bg-surface-container">
                    <span class="material-symbols-outlined text-base">photo_library</span> Galeri
                </a>
            </div>

            <div class="px-6 py-6 border-t border-outline-variant/30">
                <a href="https://wa.me/{{ $settings['whatsapp_number'] ?? '6281510029919' }}" target="_blank"
                   class="flex items-center justify-center gap-2 w-full px-6 py-4 bg-primary text-white font-bold rounded-2xl">
                    <span class="material-symbols-outlined text-sm">chat</span> Hub Admin (081510029919)
                </a>
            </div>
        </div>
    </div>
</nav>

{{-- ===== PAGE CONTENT ===== --}}
<main>
    @yield('content')
</main>

{{-- ===== FOOTER ===== --}}
<footer class="bg-emerald-900 dark:bg-emerald-950 text-white w-full">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12 px-8 py-16 max-w-7xl mx-auto text-sm leading-relaxed">

        {{-- Kolom 1: Profil Singkat & Sosial Media --}}
        <div class="md:col-span-4">
            <div class="text-xl font-bold text-amber-500 mb-6 font-headline">
                {{ $settings['site_name'] ?? 'Al-Falah Boarding School' }}
            </div>
            <p class="text-emerald-100/80 mb-8 max-w-sm">
                {{ $settings['footer_description'] ?? 'Pesantren modern dengan sistem pendidikan terpadu, mendidik generasi qurani, mandiri, dan berakhlak mulia.' }}
            </p>
            <div class="flex gap-4">
                @if(!empty($settings['instagram_url']))
                <a class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 hover:scale-110 transition-all"
                   href="{{ $settings['instagram_url'] }}" target="_blank" aria-label="Instagram">
                    <span class="material-symbols-outlined text-white text-lg">photo_camera</span>
                </a>
                @endif
                @if(!empty($settings['facebook_url']))
                <a class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 hover:scale-110 transition-all"
                   href="{{ $settings['facebook_url'] }}" target="_blank" aria-label="Facebook">
                    <span class="material-symbols-outlined text-white text-lg">public</span>
                </a>
                @endif
            </div>
        </div>

        {{-- Kolom 2: Menus --}}
        <div class="md:col-span-2">
            <h5 class="text-white font-bold mb-6">Program Unggulan</h5>
            <ul class="space-y-4">
                <li><a class="text-emerald-100/80 hover:text-white hover:translate-x-1 transition-transform inline-block" href="{{ route('programs.index') }}">Tahfidz Al-Qur'an 30 Juz</a></li>
                <li><a class="text-emerald-100/80 hover:text-white hover:translate-x-1 transition-transform inline-block" href="{{ route('programs.index') }}">SMK Pertanian Modern</a></li>
                <li><a class="text-emerald-100/80 hover:text-white hover:translate-x-1 transition-transform inline-block" href="{{ route('programs.index') }}">Pondok Pesantren</a></li>
            </ul>
        </div>

        {{-- Kolom 3: Menus --}}
        <div class="md:col-span-2">
            <h5 class="text-white font-bold mb-6">Informasi</h5>
            <ul class="space-y-4">
                <li><a class="text-emerald-100/80 hover:text-white hover:translate-x-1 transition-transform inline-block" href="{{ route('ppdb') }}">Pendaftaran (PPDB)</a></li>
                <li><a class="text-emerald-100/80 hover:text-white hover:translate-x-1 transition-transform inline-block" href="{{ route('agendas.index') }}">Kalender Akademik</a></li>
                <li><a class="text-emerald-100/80 hover:text-white hover:translate-x-1 transition-transform inline-block" href="{{ route('contact') }}">Hubungi Kami</a></li>
            </ul>
        </div>

        {{-- Kolom 4: Alamat & Map --}}
        <div class="md:col-span-4">
            <h5 class="text-white font-bold mb-6">Lokasi Kami</h5>
            <div class="flex items-start gap-3 text-emerald-100/80 mb-4">
                <span class="material-symbols-outlined text-amber-500 shrink-0 mt-0.5">location_on</span>
                <p>{{ $settings['address'] ?? 'Jl. Irigasi Kp. Galang RT.02 RW.05, Desa Jonggol, Kec. Jonggol, Kab. Bogor, Jawa Barat' }}</p>
            </div>
            <iframe src="https://maps.google.com/maps?q=Masjid+Nurul+Falah,+Jonggol+Utara&t=&z=15&ie=UTF8&iwloc=&output=embed" width="100%" height="160" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded-xl shadow-lg border border-white/10 opacity-90 hover:opacity-100 transition-opacity mt-2"></iframe>
        </div>
    </div>

    <div class="border-t border-white/10 px-8 py-8 max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center text-emerald-100/60 text-xs">
        <p>© {{ date('Y') }} {{ $settings['institution_name'] ?? 'Pondok Pesantren Tahfidz Al-Falah' }} & {{ $settings['site_name'] ?? 'SMK Al-Falah Boarding School' }}</p>
        <div class="flex gap-8 mt-4 md:mt-0">
            <a class="hover:text-white" href="#">Kebijakan Privasi</a>
            <a class="hover:text-white" href="#">Syarat & Ketentuan</a>
        </div>
    </div>
</footer>

{{-- ===== WHATSAPP FAB ===== --}}
<a class="fixed bottom-8 right-8 z-[200] group"
   href="https://wa.me/{{ $settings['whatsapp_number'] ?? '6281510029919' }}?text={{ urlencode($settings['whatsapp_message'] ?? "Assalamu'alaikum, saya ingin bertanya tentang PPDB.") }}"
   target="_blank"
   id="click_whatsapp_fab"
   aria-label="Hubungi Admin via WhatsApp">
    <div class="bg-[#25D366] text-white p-4 rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-transform duration-300">
        <svg viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
        </svg>
    </div>
    <div class="absolute right-full mr-4 top-1/2 -translate-y-1/2 bg-white text-primary px-4 py-2 rounded-lg text-sm font-bold shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none border border-emerald-100">
        Tanya Admin via WhatsApp
    </div>
</a>

{{-- Alpine.js --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@yield('scripts')
</body>
</html>
