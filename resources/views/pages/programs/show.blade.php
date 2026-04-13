@extends('layouts.app')

@section('meta_title', ($program->title) . ' | ' . ($settings['site_name'] ?? 'Al-Falah Boarding School'))

@section('content')
<div class="pt-24 pb-24 bg-surface min-h-screen relative overflow-hidden">

    {{-- Aksen Dekoratif Peta Kiri & Kanan --}}
    <div class="absolute top-20 left-0 w-[400px] h-[400px] bg-primary/5 rounded-full blur-[100px] -translate-x-1/2"></div>
    <div class="absolute bottom-20 right-0 w-[500px] h-[500px] bg-amber-500/5 rounded-full blur-[80px] translate-x-1/3"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {{-- Breadcrumb Navigasi --}}
        <div class="mb-8 flex items-center gap-2 text-sm font-medium text-on-surface-variant">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <a href="{{ route('programs.index') }}" class="hover:text-primary transition-colors">Program Unggulan</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-on-surface">{{ $program->title }}</span>
        </div>

        {{-- Elegant Inline Header --}}
        <div class="mb-12 border-b border-outline-variant/30 pb-10 text-center">
            

            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-on-surface font-headline tracking-tight leading-tight mb-4">
                {{ $program->title }}
            </h1>
            
            @if($program->subtitle)
            <h2 class="text-2xl font-bold font-headline text-primary mb-6">{{ $program->subtitle }}</h2>
            @endif

            <p class="text-on-surface-variant text-lg lg:text-xl max-w-2xl mx-auto leading-relaxed">
                {{ $program->description }}
            </p>
        </div>
        
        {{-- Jika Tidak Ada Konten Tapi Ada Gambar/Bullet Point --}}
        @if(!$program->content)
            <div class="bg-white rounded-3xl p-8 border border-outline-variant/30 shadow-sm mb-12">
                <div class="text-center py-10">
                    <span class="material-symbols-outlined text-6xl text-primary/20 mb-4 block">article</span>
                    <h3 class="text-2xl font-bold font-headline text-on-surface mb-2">Artikel Lengkap Sedang Disusun</h3>
                    <p class="text-on-surface-variant">Detail bacaan mengenai program {{ $program->title }} belum dilengkapi oleh admin.</p>
                </div>
            </div>
        @else
            {{-- Page Content / Artikel --}}
            <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl transition-shadow duration-500 p-8 md:p-12 border border-outline-variant/20 mb-12">
                <div class="prose prose-lg prose-emerald max-w-none text-on-surface-variant font-body leading-relaxed prose-headings:font-headline prose-headings:text-primary prose-a:text-primary hover:prose-a:text-primary-dark prose-img:rounded-3xl prose-img:shadow-lg prose-p:mb-6 prose-li:mb-2">
                    {!! $program->content !!}
                </div>
            </div>
        @endif



    </div>
</div>
@endsection
