@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 bg-surface min-h-screen relative overflow-hidden">
    {{-- Abstract background blurs --}}
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-primary/5 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-amber-500/5 rounded-full blur-[80px] translate-y-1/3 -translate-x-1/3 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {{-- Elegant Minimalist Header --}}
        <div class="flex flex-col items-center text-center mb-24 mt-8 max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary text-sm font-bold rounded-full mb-8">
                <span class="material-symbols-outlined text-[18px]">school</span>
                <span>Penerimaan Santri Baru</span>
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-on-surface mb-6 font-headline tracking-tight leading-[1.1]">
                Mari Bergabung<br/>
                <span class="text-primary">Mencetak Generasi</span><br/>
                Robbani
            </h1>
            
            <p class="text-on-surface-variant text-lg md:text-xl mb-10 leading-relaxed">
                {{ $settings['institution_name'] ?? 'Pondok Pesantren Tahfidz Al-Falah' }} mengundang putra-putri terbaik untuk dididik menjadi generasi tangguh, mandiri, dan berakhlakul karimah.
            </p>
            
            <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-4 justify-center">
                <a href="{{ !empty($settings['active_brochure_url']) ? $settings['active_brochure_url'] : asset('assets/ppdb.pdf') }}" download="Brosur_Pesantren_AlFalah.pdf" target="_blank" class="px-8 py-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-full transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined text-[20px]">download</span> 
                    <span>Unduh Brosur</span>
                </a>
                <a href="https://wa.me/{{ $settings['whatsapp_number'] ?? '6281510029919' }}?text={{ urlencode("Assalamu'alaikum, saya ingin bertanya tentang prosedur pendaftaran PPDB.") }}" target="_blank" class="px-8 py-4 bg-white hover:bg-surface-container-lowest text-on-surface font-bold rounded-full transition-all border border-outline-variant/50 hover:border-primary/50 shadow-sm flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined text-[20px] text-primary">chat</span>
                    <span>Konsul WA ({{ $settings['whatsapp_number'] ?? '081510029919' }})</span>
                </a>
            </div>
        </div>

        @if($activePeriod)
            <div class="grid lg:grid-cols-12 gap-12 items-start">
                
                {{-- KIRI: Jadwal & Syarat (8 Columns) --}}
                <div class="lg:col-span-8 space-y-16">
                    
                    {{-- Gelombang --}}
                    @if($activePeriod->waves->isNotEmpty())
                    <section>
                        <div class="mb-8">
                            <h3 class="text-3xl font-bold font-headline text-on-surface mb-2">Jadwal Pendaftaran</h3>
                            <p class="text-on-surface-variant">Pilih gelombang pendaftaran yang sesuai untuk tahun ajaran {{ $activePeriod->academic_year }}.</p>
                        </div>
                        
                        <div class="grid sm:grid-cols-2 gap-6">
                            @foreach($activePeriod->waves as $wave)
                            <div class="group bg-white border border-outline-variant/30 hover:border-primary/50 p-6 lg:p-8 rounded-3xl transition-all duration-300 shadow-sm hover:shadow-md">
                                <h4 class="font-black text-xl text-on-surface mb-6">{{ $wave->name }}</h4>
                                
                                <div class="space-y-6">
                                    <div class="relative pl-6 border-l-2 border-primary/20">
                                        <div class="absolute w-3 h-3 bg-primary rounded-full -left-[7px] top-1.5 border-[3px] border-white group-hover:scale-125 transition-transform"></div>
                                        <div class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Masa Pendaftaran</div>
                                        <div class="font-medium text-on-surface">{{ $wave->registration_start->format('d M Y') }} - {{ $wave->registration_end->format('d M Y') }}</div>
                                    </div>
                                    <div class="relative pl-6 border-l-2 border-transparent">
                                        <div class="absolute w-3 h-3 bg-amber-500 rounded-full -left-[7px] top-1.5 border-[3px] border-white group-hover:scale-125 transition-transform"></div>
                                        <div class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Tes Seleksi</div>
                                        <div class="font-medium text-on-surface">{{ $wave->selection_date ? $wave->selection_date->format('d M Y') : 'Selesai Mendaftar' }}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </section>
                    @endif

                    {{-- Persyaratan --}}
                    @if($activePeriod->requirements->isNotEmpty())
                    <section>
                        <div class="mb-8">
                            <h3 class="text-3xl font-bold font-headline text-on-surface mb-2">Persyaratan Berkas</h3>
                            <p class="text-on-surface-variant">Dokumen yang perlu dipersiapkan untuk pendaftaran.</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($activePeriod->requirements as $index => $req)
                            <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-outline-variant/20 shadow-sm">
                                <span class="material-symbols-outlined text-primary shrink-0 mt-0.5 text-2xl">check_circle</span>
                                <div>
                                    <h4 class="font-bold text-on-surface">{{ $req->title }}</h4>
                                    @if($req->description)
                                        <p class="text-sm text-on-surface-variant mt-1 leading-relaxed">{{ $req->description }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </section>
                    @endif

                </div>

                {{-- KANAN: Rincian Biaya (4 Columns - Sticky) --}}
                <div class="lg:col-span-4 lg:sticky lg:top-28">
                    @if($activePeriod->fees->isNotEmpty())
                    <div class="bg-white border border-outline-variant/30 rounded-3xl p-8 lg:p-10 flex flex-col relative overflow-hidden shadow-xl shadow-surface-variant/20">
                        {{-- Subtle background decoration --}}
                        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-primary/10 to-transparent rounded-bl-full pointer-events-none"></div>
                        
                        <div class="relative z-10 mb-10">
                            <div class="text-xs font-bold uppercase tracking-widest text-primary mb-2">Estimasi Pembiayaan</div>
                            <h3 class="text-2xl font-black font-headline text-on-surface">Tahun Ajaran {{ $activePeriod->academic_year }}</h3>
                        </div>
                        
                        <ul class="space-y-4 mb-10 relative z-10">
                            @php $total = 0; @endphp
                            @foreach($activePeriod->fees as $fee)
                            @php $total += $fee->amount; @endphp
                            <li class="flex justify-between items-end pb-4 border-b border-outline-variant/30 border-dashed">
                                <span class="text-on-surface-variant text-sm pr-4">{{ $fee->name }}</span>
                                <span class="font-bold text-on-surface shrink-0">{{ $fee->formatted_amount }}</span>
                            </li>
                            @endforeach
                        </ul>
                        
                        <div class="mt-auto relative z-10">
                            <div class="flex flex-col p-5 bg-surface-container-lowest rounded-2xl border border-primary/20 mb-6">
                                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Total Biaya Masuk</span>
                                <span class="text-3xl font-black text-primary font-headline tracking-tight">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            
                            <p class="text-xs text-on-surface-variant leading-relaxed flex items-start gap-2">
                                <span class="material-symbols-outlined text-[16px] text-amber-500 shrink-0">info</span>
                                Angka di atas merupakan estimasi reguler. Pembiayaan bisa dicicil atau disesuaikan dengan ketentuan asrama terkait administrasi.
                            </p>
                        </div>
                    </div>
                    @endif
                </div>

            </div>

        @else
            {{-- EMPTY STATE --}}
            <div class="flex flex-col items-center justify-center py-20 text-center px-4 max-w-2xl mx-auto">
                <div class="w-20 h-20 bg-surface-container rounded-full flex items-center justify-center mb-6 text-on-surface-variant">
                    <span class="material-symbols-outlined text-3xl">inventory_2</span>
                </div>
                <h3 class="text-2xl font-black text-on-surface mb-3 font-headline">Pendaftaran Belum Dibuka</h3>
                <p class="text-on-surface-variant mb-8 leading-relaxed">Informasi Penerimaan Santri Baru (PPDB) saat ini belum tersedia atau gelombang sebelumnya telah usai. Silakan pantau terus atau hubungi kami untuk informasi lebih lanjut.</p>
                <a href="https://wa.me/{{ $settings['whatsapp_number'] ?? '6281510029919' }}" target="_blank" class="inline-flex items-center gap-2 px-8 py-3 bg-white border border-outline-variant/50 text-on-surface font-bold rounded-full transition-all hover:bg-surface-container shadow-sm">
                    <span class="material-symbols-outlined text-[20px] text-primary">chat</span> Hubungi Panitia
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
