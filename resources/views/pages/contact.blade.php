@extends('layouts.app')

@section('meta_title', 'Hubungi Kami | ' . ($settings['site_name'] ?? 'Al-Falah Boarding School'))

@section('content')
<div class="pt-24 lg:pt-32 pb-12 lg:pb-24 bg-surface min-h-screen relative overflow-hidden">
    
    {{-- Aksen Dekorasi Belakang --}}
    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/5 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-amber-500/5 rounded-full blur-[80px] translate-y-1/3 -translate-x-1/4"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {{-- Header Section --}}
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary text-sm font-bold rounded-full mb-6">
                <span class="material-symbols-outlined text-[16px]">contact_support</span>
                Pusat Bantuan Layanan
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-on-surface font-headline tracking-tight leading-tight mb-4">
                Ada Pertanyaan? <br class="hidden md:block" /> 
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-emerald-400">Kami Siap Membantu.</span>
            </h1>
            <p class="text-on-surface-variant text-lg max-w-2xl mx-auto leading-relaxed mt-4">
                Jangan ragu untuk menghubungi kami melalui media komunikasi yang tersedia atau datang langsung ke lokasi pesantren untuk mendapatkan informasi secara lebih rinci.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-start">
            
            {{-- Bagian Kiri: Kartu Kontak --}}
            <div class="flex flex-col gap-6">
                
                {{-- Kartu WhatsApp (Utama) --}}
                <div class="bg-white p-8 rounded-3xl shadow-lg border-l-4 border-l-primary hover:-translate-y-1 transition-all duration-300">
                    <div class="flex gap-6 items-start">
                        <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-3xl text-primary">forum</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-on-surface mb-1 font-headline">Konsultasi WhatsApp</h3>
                            <p class="text-on-surface-variant text-sm mb-4">Admin kami membalas pesan Anda sesegera mungkin pada jam kerja kerja opsional.</p>
                            <a href="https://wa.me/{{ $settings['whatsapp_number'] ?? '6281510029919' }}?text={{ urlencode($settings['whatsapp_message'] ?? "Assalamu'alaikum.") }}" target="_blank" class="inline-flex items-center gap-2 text-primary font-bold hover:text-primary-dark group">
                                <span class="bg-primary/10 px-4 py-2 rounded-lg group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                                    {{ $settings['whatsapp_number'] ?? '081510029919' }} <span class="ml-2">→</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Kartu Alamat --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-outline-variant/30 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex gap-6 items-start">
                        <div class="w-14 h-14 bg-amber-500/10 rounded-2xl flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-3xl text-amber-600">location_on</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-on-surface mb-2 font-headline">Alamat Kampus</h3>
                            <p class="text-on-surface-variant text-base leading-relaxed">
                                {{ $settings['address'] ?? 'Jl. Irigasi Kp. Galang RT.02 RW.05, Desa Jonggol, Kec. Jonggol, Kab. Bogor, Jawa Barat' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Email & Telepon Konvensional --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-outline-variant/30 text-center hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-blue-500/10 mx-auto rounded-full flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-2xl text-blue-600">mail</span>
                        </div>
                        <h4 class="font-bold text-on-surface mb-1">Surel / Email</h4>
                        <a href="mailto:{{ $settings['email'] ?? 'ppt.alfalah29919@gmail.com' }}" class="text-primary text-sm font-medium hover:underline break-all">
                            {{ $settings['email'] ?? 'ppt.alfalah29919@gmail.com' }}
                        </a>
                    </div>
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-outline-variant/30 text-center hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-emerald-500/10 mx-auto rounded-full flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-2xl text-emerald-600">call</span>
                        </div>
                        <h4 class="font-bold text-on-surface mb-1">Telepon Kantor</h4>
                        <a href="tel:{{ $settings['phone'] ?? '+62 815-1002-9919' }}" class="text-primary text-sm font-medium hover:underline">
                            {{ $settings['phone'] ?? '+62 815-1002-9919' }}
                        </a>
                    </div>
                </div>

            </div>

            {{-- Bagian Kanan: Embed Map & Informasi Tambahan --}}
            <div class="h-full flex flex-col">
                <div class="bg-white p-2 rounded-[2.5rem] shadow-xl shadow-primary/5 border border-outline-variant/30 h-full min-h-[400px] flex flex-col overflow-hidden relative group">
                    @if(!empty($settings['maps_embed_url']))
                        <iframe 
                            src="{{ $settings['maps_embed_url'] }}" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="rounded-[2rem] w-full h-full min-h-[300px] lg:min-h-[500px] flex-grow z-10 transition-transform duration-700">
                        </iframe>
                    @else
                        {{-- Placeholder Premium Jika Map URL Kosong --}}
                        <div class="flex-grow flex flex-col items-center justify-center p-8 bg-surface-container-lowest rounded-[2rem] h-[300px] lg:h-[500px]">
                            <div class="w-24 h-24 bg-primary/5 rounded-full flex items-center justify-center mb-6">
                                <span class="material-symbols-outlined text-5xl text-primary/40">map</span>
                            </div>
                            <h3 class="text-2xl font-bold font-headline text-on-surface mb-2">Lokasi Terhubung</h3>
                            <p class="text-center text-on-surface-variant max-w-sm">
                                Peta digital sedang dipersiapkan dan akan otomatis tampil setelah tautan koordinat diisi oleh Administrator.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
