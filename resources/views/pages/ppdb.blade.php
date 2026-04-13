@extends('layouts.app')

@section('content')
<div class="pt-32 pb-24 bg-surface min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Elegant Split Header --}}
        <div class="flex flex-col lg:flex-row items-center gap-12 mb-20 bg-surface-container-lowest border border-outline-variant/30 rounded-[3rem] p-8 md:p-12 lg:p-16 shadow-2xl shadow-primary/5">
            <div class="flex-1 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500/10 text-amber-600 text-sm font-bold rounded-full mb-6 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-amber-500/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    <span class="material-symbols-outlined text-[18px] relative z-10">school</span>
                    <span class="relative z-10">Penerimaan Santri Baru</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-on-surface mb-6 font-headline tracking-tight leading-[1.1]">
                    Mari Bergabung<br/>
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-emerald-400">Mencetak Generasi</span><br/>
                    Robbani
                </h1>
                
                <p class="text-on-surface-variant text-lg max-w-xl mx-auto lg:mx-0 mb-8 leading-relaxed">
                    {{ $settings['institution_name'] ?? 'Pondok Pesantren Tahfidz Al-Falah' }} mengundang putra-putri terbaik untuk dididik menjadi generasi tangguh, mandiri, dan berakhlakul karimah.
                </p>
            </div>
            
            <div class="shrink-0 flex flex-col sm:flex-row lg:flex-col w-full sm:w-auto gap-4">
                <a href="{{ !empty($settings['active_brochure_url']) ? $settings['active_brochure_url'] : asset('assets/ppdb.pdf') }}" download="Brosur_Pesantren_AlFalah.pdf" target="_blank" class="px-8 py-5 bg-primary hover:bg-primary-dark text-white font-bold rounded-2xl transition-all shadow-xl shadow-primary/30 flex items-center justify-center gap-3 group">
                    <span class="material-symbols-outlined group-hover:-translate-y-1 transition-transform">download</span> 
                    <span>Unduh Brosur Resmi</span>
                </a>
                <a href="https://wa.me/{{ $settings['whatsapp_number'] ?? '6281510029919' }}?text={{ urlencode("Assalamu'alaikum, saya ingin bertanya tentang prosedur pendaftaran PPDB.") }}" target="_blank" class="px-8 py-5 bg-surface hover:bg-surface-container text-on-surface font-bold rounded-2xl transition-all border-2 border-outline-variant/50 flex items-center justify-center gap-3 group">
                    <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[14px] text-primary">chat</span></div>
                    <span>Konsul WA ({{ $settings['whatsapp_number'] ?? '081510029919' }})</span>
                </a>
            </div>
        </div>

        @if($activePeriod)
            <div class="flex flex-col items-center mb-16">
                <div class="px-6 py-2 bg-on-surface text-surface font-bold rounded-full text-sm mb-4">
                    Tahun Ajaran {{ $activePeriod->academic_year }}
                </div>
                <h2 class="text-3xl pr-2 md:text-5xl font-black text-on-surface font-headline text-center">
                    Informasi <span class="text-primary">Pendaftaran</span>
                </h2>
                <div class="w-16 h-1.5 bg-amber-500 rounded-full mt-6"></div>
            </div>

            <div class="grid lg:grid-cols-12 gap-10 items-start">
                
                {{-- KIRI: Jadwal & Syarat (8 Columns) --}}
                <div class="lg:col-span-8 space-y-10">
                    
                    {{-- Gelombang --}}
                    @if($activePeriod->waves->isNotEmpty())
                    <div class="bg-surface-container border border-outline-variant/30 rounded-3xl p-8 lg:p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-white text-primary flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined text-3xl">event_available</span>
                            </div>
                            <h3 class="text-3xl font-bold font-headline text-on-surface">Jadwal Gelombang</h3>
                        </div>
                        
                        <div class="grid sm:grid-cols-2 gap-6">
                            @foreach($activePeriod->waves as $wave)
                            <div class="bg-white border text-left border-outline-variant/20 p-6 rounded-2xl hover:border-primary transition-colors hover:shadow-lg hover:-translate-y-1 duration-300">
                                <h4 class="font-black text-xl text-on-surface mb-4 pb-4 border-b border-outline-variant/20">{{ $wave->name }}</h4>
                                
                                <div class="space-y-4">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex justify-center items-center shrink-0">
                                            <span class="material-symbols-outlined text-[16px]">edit_document</span>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Pendaftaran</div>
                                            <div class="font-medium text-on-surface">{{ $wave->registration_start->format('d M y') }} - {{ $wave->registration_end->format('d M y') }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full bg-amber-500/10 text-amber-600 flex justify-center items-center shrink-0">
                                            <span class="material-symbols-outlined text-[16px]">fact_check</span>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Tes Seleksi</div>
                                            <div class="font-medium text-on-surface">{{ $wave->selection_date ? $wave->selection_date->format('d M y') : 'Menyusul/Selesai Daftar' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Persyaratan --}}
                    @if($activePeriod->requirements->isNotEmpty())
                    <div class="bg-surface-container border border-outline-variant/30 rounded-3xl p-8 lg:p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-white text-secondary flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined text-3xl">task_alt</span>
                            </div>
                            <h3 class="text-3xl font-bold font-headline text-on-surface">Persyaratan Berkas</h3>
                        </div>
                        
                        <div class="bg-white rounded-2xl p-6 md:p-8">
                            <ul class="space-y-5">
                                @foreach($activePeriod->requirements as $index => $req)
                                <li class="flex items-start gap-4 p-4 rounded-xl hover:bg-surface-container-lowest transition-colors group">
                                    <div class="w-8 h-8 rounded-full bg-secondary/10 text-secondary flex items-center justify-center shrink-0 mt-1 pb-0.5 group-hover:bg-secondary group-hover:text-white transition-colors">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-lg text-on-surface">{{ $req->title }}</h4>
                                        @if($req->description)
                                            <p class="text-on-surface-variant mt-1 leading-relaxed">{{ $req->description }}</p>
                                        @endif
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                </div>

                {{-- KANAN: Rincian Biaya (4 Columns - Sticky) --}}
                <div class="lg:col-span-4 lg:sticky lg:top-28">
                    @if($activePeriod->fees->isNotEmpty())
                    <div class="bg-primary rounded-3xl overflow-hidden shadow-2xl shadow-primary/20 flex flex-col">
                        <div class="p-8 text-white relative">
                            <svg class="absolute top-0 right-0 w-32 h-32 text-white/5 transform translate-x-8 -translate-y-8" fill="currentColor" viewBox="0 0 24 24"><path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/></svg>
                            
                            <h3 class="text-2xl font-black font-headline mb-2 relative z-10">Rincian Pembiayaan</h3>
                            <p class="text-primary-100 text-sm relative z-10">Estimasi biaya masuk awal untuk tahun ajaran {{ $activePeriod->academic_year }}.</p>
                        </div>
                        
                        <div class="bg-white p-8 flex-1 rounded-t-[2rem]">
                            <ul class="space-y-4 mb-8">
                                @php $total = 0; @endphp
                                @foreach($activePeriod->fees as $fee)
                                @php $total += $fee->amount; @endphp
                                <li class="flex justify-between items-center text-sm pb-4 border-b border-outline-variant/20 border-dashed">
                                    <span class="text-on-surface-variant max-w-[60%] leading-snug">{{ $fee->name }}</span>
                                    <span class="font-bold text-on-surface tabular-nums">{{ $fee->formatted_amount }}</span>
                                </li>
                                @endforeach
                            </ul>
                            
                            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-primary/20">
                                <div class="text-sm font-bold border-variant uppercase tracking-wider text-primary mb-1">Total Biaya Masuk</div>
                                <div class="text-3xl font-black text-on-surface font-headline tabular-nums">Rp {{ number_format($total, 0, ',', '.') }}</div>
                            </div>
                            
                            <div class="mt-6 flex items-start gap-3 bg-amber-500/10 p-4 rounded-xl text-amber-700 text-xs leading-relaxed">
                                <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5">info</span>
                                <p>Angka di atas merupakan estimasi reguler. Pembiayaan bisa dicicil/disesuaikan dengan ketentuan asrama terkait administrasi.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

            </div>

        @else
            {{-- EMPTY STATE --}}
            <div class="flex flex-col items-center justify-center py-24 bg-surface-container-lowest rounded-[3rem] border border-outline-variant/30 text-center px-4 shadow-sm max-w-4xl mx-auto">
                <div class="w-24 h-24 bg-surface-container rounded-full flex items-center justify-center mb-6 text-on-surface-variant relative overflow-hidden">
                    <span class="material-symbols-outlined text-4xl">inventory_2</span>
                </div>
                <h3 class="text-3xl font-black text-on-surface mb-3 font-headline">Pendaftaran Belum Dibuka</h3>
                <p class="text-on-surface-variant max-w-lg mx-auto text-lg mb-8">Informasi Penerimaan Santri Baru (PPDB) saat ini belum tersedia atau gelombang sebelumnya telah usai. Silakan pantau terus atau hubungi kami.</p>
                <a href="https://wa.me/{{ $settings['whatsapp_number'] ?? '6281510029919' }}" target="_blank" class="inline-flex items-center gap-2 px-8 py-4 bg-primary text-white font-bold rounded-full transition-all hover:bg-primary-dark hover:-translate-y-1 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined">chat</span> Hubungi Panitia (081510029919)
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
