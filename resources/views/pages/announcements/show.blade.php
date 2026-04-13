@extends('layouts.app')

@section('content')
{{-- HEADER --}}
<div class="relative pt-32 pb-16 lg:pt-40 lg:pb-24 overflow-hidden bg-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <a href="{{ route('announcements.index') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary-dark font-bold transition-colors mb-8">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali ke Pengumuman
        </a>

        @if($announcement->is_pinned)
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-amber-500/10 text-amber-600 text-sm font-bold rounded-full mb-6">
            <span class="material-symbols-outlined text-[16px]">push_pin</span>
            Disematkan
        </div>
        @endif
        
        <h1 class="text-3xl md:text-5xl font-black text-on-surface mb-8 font-headline leading-tight">
            {{ $announcement->title }}
        </h1>
        
        <div class="flex items-center gap-2 text-on-surface-variant text-sm">
            <span class="material-symbols-outlined text-outline">calendar_month</span>
            <time datetime="{{ $announcement->published_at?->format('Y-m-d') }}">
                Dipublikasikan pada: <strong class="text-on-surface">{{ $announcement->published_at?->format('d F Y') ?? $announcement->created_at->format('d F Y') }}</strong>
            </time>
        </div>
    </div>
</div>

{{-- KONTEN PENGUMUMAN --}}
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">
    <div class="bg-white rounded-3xl p-8 sm:p-12 shadow-xl shadow-primary/5 border border-outline-variant/30">
        
        <article class="prose prose-lg prose-emerald max-w-none prose-headings:font-headline prose-a:text-primary hover:prose-a:text-primary-dark">
            {!! $announcement->content !!}
        </article>

        @if($announcement->attachment)
        <div class="mt-12 pt-8 border-t border-outline-variant/30">
            <h3 class="text-lg font-bold text-on-surface mb-4">Lampiran Dokumen</h3>
            <a href="{{ asset('storage/' . $announcement->attachment) }}" target="_blank" download
               class="inline-flex items-center justify-between gap-4 p-4 rounded-xl bg-surface-container hover:bg-primary/5 border border-outline-variant/50 transition-colors w-full sm:w-auto group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-white shadow-sm flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined">description</span>
                    </div>
                    <div>
                        <div class="font-bold text-on-surface group-hover:text-primary transition-colors line-clamp-1">Unduh Lampiran Resmi</div>
                        <div class="text-xs text-on-surface-variant">Klik untuk mengunduh</div>
                    </div>
                </div>
                <span class="material-symbols-outlined text-outline group-hover:text-primary">download</span>
            </a>
        </div>
        @endif

    </div>
</div>
@endsection
