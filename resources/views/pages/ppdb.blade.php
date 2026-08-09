@extends('layouts.app')

@section('meta_title', 'PPDB SMK Pesantren Tahfidz Jonggol | PPT Al-Falah')
@section('meta_description', 'Informasi PPDB PPT Al-Falah, SMK Pertanian dan IT berbasis Pesantren Tahfidz di Jonggol, Bogor dengan program gratis biaya pendidikan selama satu tahun pertama.')

@section('content')
@php
    $today = today();
    $waves = $activePeriod?->waves->where('is_active', true) ?? collect();
    $openWave = $waves->first(fn ($wave) => $wave->registration_start->lte($today) && $wave->registration_end->gte($today));
    $upcomingWave = $waves->first(fn ($wave) => $wave->registration_start->gt($today));
    $registrationWave = $openWave ?? $upcomingWave ?? $waves->sortByDesc('registration_end')->first();
    $registrationState = $openWave ? 'open' : ($upcomingWave ? 'upcoming' : ($registrationWave ? 'closed' : 'unavailable'));
    $whatsappUrl = 'https://wa.me/'.($settings['whatsapp_number'] ?? '6281510029919').'?text='.urlencode("Assalamu'alaikum, saya ingin bertanya tentang PPDB SMK Al-Falah.");
@endphp

<div class="bg-surface min-h-screen py-14 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($activePeriod)
            <header class="pb-12 lg:pb-16 border-b border-outline-variant/40">
                <div class="grid lg:grid-cols-[minmax(0,1fr)_22rem] gap-10 lg:gap-16 items-end">
                    <div>
                        <p class="text-sm font-semibold text-primary mb-3">Tahun Ajaran {{ $activePeriod->academic_year }}</p>
                        <h1 class="text-4xl md:text-5xl font-bold text-on-surface font-headline tracking-tight leading-tight mb-5">
                            PPDB SMK Al-Falah {{ $activePeriod->academic_year }}
                        </h1>
                        <p class="text-on-surface-variant text-lg leading-relaxed max-w-3xl mb-8">
                            Pendaftaran SMK Pertanian & IT berbasis Pesantren Tahfidz di Jonggol, Bogor. Gratis biaya pendidikan selama 1 tahun pertama untuk seluruh santri baru.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            @if($openWave)
                                <a data-ppdb-cta="main" href="{{ route('ppdb.register') }}" class="inline-flex justify-center px-6 py-3.5 bg-primary hover:bg-primary-container text-white font-bold rounded-lg transition-colors">
                                    Daftar Sekarang
                                </a>
                            @endif
                            <a href="{{ $whatsappUrl }}" target="_blank" id="click_whatsapp_ppdb" class="inline-flex justify-center px-6 py-3.5 bg-primary hover:bg-primary-container text-white font-bold rounded-lg transition-colors">
                                Tanya Panitia PPDB
                            </a>
                            <a href="{{ !empty($settings['active_brochure_url']) ? $settings['active_brochure_url'] : asset('assets/ppdb.pdf') }}" download="Brosur_Pesantren_AlFalah.pdf" target="_blank" class="inline-flex justify-center px-6 py-3.5 border border-outline-variant hover:border-primary text-on-surface font-bold rounded-lg transition-colors">
                                Unduh Brosur
                            </a>
                        </div>
                    </div>

                    <div class="border-l-4 {{ $registrationState === 'open' ? 'border-primary' : 'border-amber-500' }} pl-6 py-1">
                        <p class="text-sm font-semibold text-on-surface-variant mb-2">Status pendaftaran</p>
                        <h2 class="text-2xl font-bold font-headline text-on-surface mb-2">
                            {{ match($registrationState) {
                                'open' => 'Pendaftaran Dibuka',
                                'upcoming' => 'Pendaftaran Segera Dibuka',
                                'closed' => 'Pendaftaran Ditutup',
                                default => 'Jadwal Belum Tersedia',
                            } }}
                        </h2>
                        <p class="text-sm text-on-surface-variant leading-relaxed">
                            @if($registrationState === 'open')
                                {{ $registrationWave->name }} dibuka sampai {{ $registrationWave->registration_end->format('d M Y') }}.
                            @elseif($registrationState === 'upcoming')
                                {{ $registrationWave->name }} dibuka mulai {{ $registrationWave->registration_start->format('d M Y') }}.
                            @elseif($registrationState === 'closed')
                                {{ $registrationWave->name }} berakhir {{ $registrationWave->registration_end->format('d M Y') }}. Hubungi panitia untuk informasi gelombang berikutnya.
                            @else
                                Hubungi panitia untuk mendapatkan informasi jadwal terbaru.
                            @endif
                        </p>
                    </div>
                </div>

                <dl class="grid sm:grid-cols-3 mt-10 border-y border-outline-variant/40 divide-y sm:divide-y-0 sm:divide-x divide-outline-variant/40">
                    <div class="py-5 sm:pr-6">
                        <dt class="text-sm text-on-surface-variant mb-1">Gelombang acuan</dt>
                        <dd class="font-bold text-on-surface">{{ $registrationWave?->name ?? 'Belum tersedia' }}</dd>
                    </div>
                    <div class="py-5 sm:px-6">
                        <dt class="text-sm text-on-surface-variant mb-1">Masa pendaftaran</dt>
                        <dd class="font-bold text-on-surface">
                            {{ $registrationWave ? $registrationWave->registration_start->format('d M').' – '.$registrationWave->registration_end->format('d M Y') : 'Belum tersedia' }}
                        </dd>
                    </div>
                    <div class="py-5 sm:pl-6">
                        <dt class="text-sm text-on-surface-variant mb-1">Program biaya pendidikan</dt>
                        <dd class="font-bold text-on-surface">Gratis 1 tahun pertama</dd>
                    </div>
                </dl>
            </header>

            <section class="py-12 lg:py-16 border-b border-outline-variant/40" aria-labelledby="alur-ppdb">
                <div class="max-w-3xl mb-8">
                    <h2 id="alur-ppdb" class="text-3xl font-bold font-headline text-on-surface mb-2">Alur Pendaftaran</h2>
                    <p class="text-on-surface-variant">Tiga langkah utama dari registrasi sampai daftar ulang.</p>
                </div>
                <ol class="grid md:grid-cols-3 border-y border-outline-variant/40 divide-y md:divide-y-0 md:divide-x divide-outline-variant/40">
                    @foreach([
                        ['title' => $settings['ppdb_step_1_title'] ?? 'Registrasi Online / Offline', 'desc' => $settings['ppdb_step_1_desc'] ?? 'Isi formulir dan lengkapi berkas administrasi.'],
                        ['title' => $settings['ppdb_step_2_title'] ?? 'Tes Seleksi Akademik', 'desc' => $settings['ppdb_step_2_desc'] ?? 'Ikuti tes masuk sesuai jadwal gelombang.'],
                        ['title' => $settings['ppdb_step_3_title'] ?? 'Pengumuman & Daftar Ulang', 'desc' => 'Panitia menghubungi calon santri untuk proses berikutnya.'],
                    ] as $index => $step)
                        <li class="py-6 md:px-6 first:pl-0 last:pr-0">
                            <span class="text-sm font-bold text-primary">0{{ $index + 1 }}</span>
                            <h3 class="font-bold text-on-surface mt-3 mb-2">{{ $step['title'] }}</h3>
                            <p class="text-sm text-on-surface-variant leading-relaxed">{{ $step['desc'] }}</p>
                        </li>
                    @endforeach
                </ol>
            </section>

            <div class="grid lg:grid-cols-[minmax(0,1fr)_22rem] gap-12 lg:gap-16 pt-12 lg:pt-16 items-start">
                <div class="space-y-14">
                    @if($waves->isNotEmpty())
                        <section aria-labelledby="jadwal-ppdb">
                            <div class="mb-7">
                                <h2 id="jadwal-ppdb" class="text-3xl font-bold font-headline text-on-surface mb-2">Jadwal Pendaftaran</h2>
                                <p class="text-on-surface-variant">Jadwal setiap gelombang untuk tahun ajaran {{ $activePeriod->academic_year }}.</p>
                            </div>

                            <div class="border-y border-outline-variant/40 divide-y divide-outline-variant/40">
                                @foreach($waves as $wave)
                                    <article class="py-6">
                                        <h3 class="font-bold text-xl text-on-surface mb-1">{{ $wave->name }}</h3>
                                        <p class="text-sm text-on-surface-variant">{{ $wave->registration_start->format('d M Y') }} – {{ $wave->registration_end->format('d M Y') }}</p>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    <section aria-labelledby="syarat-ppdb">
                        <div class="mb-7">
                            <h2 id="syarat-ppdb" class="text-3xl font-bold font-headline text-on-surface mb-2">Persyaratan Setelah Daftar</h2>
                            <p class="text-on-surface-variant">Setelah pendaftaran online berhasil, bawa dokumen berikut saat verifikasi dan tes di pondok.</p>
                        </div>
                        <ol class="border-y border-outline-variant/40 divide-y divide-outline-variant/40">
                            @foreach([
                                'Fotokopi Akta Kelahiran dan Kartu Keluarga',
                                'Pas foto 3×4 sebanyak empat lembar',
                                'Fotokopi rapor terakhir',
                                'Mengikuti tes akademik di pondok',
                            ] as $index => $requirement)
                                <li class="py-5 flex gap-5 items-start"><span class="text-sm font-bold text-primary pt-0.5">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span><p class="font-semibold text-on-surface">{{ $requirement }}</p></li>
                            @endforeach
                        </ol>
                        <p class="mt-4 text-sm font-semibold text-on-surface">Akta, KK, dan rapor tidak perlu diunggah ke website.</p>
                    </section>
                </div>

                <aside class="lg:sticky lg:top-28 border border-outline-variant/50 bg-white rounded-xl p-6 lg:p-8" aria-labelledby="program-gratis-ppdb">
                    <p class="text-sm font-semibold text-primary mb-2">Program Seluruh Santri Baru</p>
                    <h2 id="program-gratis-ppdb" class="text-2xl font-bold font-headline text-on-surface mb-3">Gratis Biaya Pendidikan</h2>
                    <p class="text-4xl font-bold text-primary font-headline mb-4">1 Tahun Pertama</p>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-7">
                        Seluruh santri baru mendapat pembebasan biaya pendidikan selama satu tahun pertama sejak resmi menjadi santri.
                    </p>

                    @if($openWave)
                        <a data-ppdb-cta="fee" href="{{ route('ppdb.register') }}" class="mb-3 flex justify-center w-full px-5 py-3.5 bg-primary hover:bg-primary-container text-white font-bold rounded-lg transition-colors">
                            Daftar Sekarang
                        </a>
                    @endif
                    <a href="{{ $whatsappUrl }}" target="_blank" class="flex justify-center w-full px-5 py-3.5 border border-outline-variant hover:border-primary text-on-surface font-bold rounded-lg transition-colors">
                        Tanya Panitia PPDB
                    </a>
                </aside>
            </div>
        @else
            <div class="max-w-2xl py-16">
                <p class="text-sm font-semibold text-primary mb-3">PPDB SMK Al-Falah</p>
                <h1 class="text-4xl md:text-5xl font-bold text-on-surface font-headline tracking-tight mb-5">Jadwal pendaftaran belum tersedia</h1>
                <p class="text-on-surface-variant text-lg leading-relaxed mb-8">Pendaftaran SMK Pertanian & IT berbasis Pesantren Tahfidz di Jonggol, Bogor. Hubungi panitia untuk mendapatkan informasi tahun ajaran dan jadwal terbaru.</p>
                <a href="{{ $whatsappUrl }}" target="_blank" class="inline-flex justify-center px-6 py-3.5 bg-primary hover:bg-primary-container text-white font-bold rounded-lg transition-colors">
                    Tanya Panitia PPDB
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
