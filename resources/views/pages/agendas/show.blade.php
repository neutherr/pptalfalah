@extends('layouts.app')

@section('content')
{{-- HEADER --}}
<div class="relative pt-32 pb-16 lg:pt-40 lg:pb-24 overflow-hidden bg-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <a href="{{ route('agendas.index') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary-dark font-bold transition-colors mb-8">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali ke Indeks Agenda
        </a>
        
        <h1 class="text-3xl md:text-5xl font-black text-on-surface mb-10 font-headline leading-tight">
            {{ $agenda->title }}
        </h1>
        
        <div class="grid sm:grid-cols-3 gap-6">
            <div class="bg-surface-container-low border border-outline-variant/30 rounded-2xl p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]">schedule</span>
                </div>
                <div>
                    <div class="text-xs text-on-surface-variant font-bold mb-1 uppercase tracking-wider">Waktu</div>
                    <div class="font-bold text-on-surface text-sm">
                        {{ $agenda->start_datetime->format('d M Y, H:i') }}<br>
                        <span class="text-on-surface-variant font-normal">s/d</span><br>
                        {{ $agenda->end_datetime ? $agenda->end_datetime->format('d M Y, H:i') : 'Selesai' }}
                    </div>
                </div>
            </div>

            <div class="bg-surface-container-low border border-outline-variant/30 rounded-2xl p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]">location_on</span>
                </div>
                <div>
                    <div class="text-xs text-on-surface-variant font-bold mb-1 uppercase tracking-wider">Lokasi</div>
                    <div class="font-bold text-on-surface text-sm">
                        {{ $agenda->location ?? 'Lingkungan Pondok' }}
                    </div>
                </div>
            </div>

            <div class="bg-surface-container-low border border-outline-variant/30 rounded-2xl p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-secondary/10 text-secondary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]">group</span>
                </div>
                <div>
                    <div class="text-xs text-on-surface-variant font-bold mb-1 uppercase tracking-wider">Penyelenggara</div>
                    <div class="font-bold text-on-surface text-sm">
                        {{ $agenda->organizer ?? 'Pengurus Pesantren' }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- KONTEN AGENDA --}}
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">
    <div class="bg-white rounded-3xl p-8 sm:p-12 shadow-xl shadow-primary/5 border border-outline-variant/30">
        @if(strip_tags($agenda->description))
            <article class="prose prose-lg prose-emerald max-w-none prose-headings:font-headline prose-a:text-primary hover:prose-a:text-primary-dark">
                {!! $agenda->description !!}
            </article>
        @else
            <div class="text-center py-12">
                <p class="text-on-surface-variant">Tidak ada deskripsi rinci untuk agenda ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection
