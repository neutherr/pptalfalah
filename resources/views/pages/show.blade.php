@extends('layouts.app')

@section('meta_title', ($page->meta_title ?? $page->title) . ' | ' . ($settings['site_name'] ?? 'Al-Falah Boarding School'))
@section('meta_description', $page->meta_description ?? '')

@section('content')
<div class="pt-24 lg:pt-32 pb-12 lg:pb-24 bg-surface min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Elegant Inline Header --}}
        <div class="mb-12 border-b border-outline-variant/30 pb-10 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary text-sm font-bold rounded-full mb-6">
                <span class="material-symbols-outlined text-[16px]">info</span>
                Informasi Resmi
            </div>
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-black text-on-surface font-headline tracking-tight leading-tight mb-4">
                {{ $page->title }}
            </h1>
            @if($page->meta_description)
            <p class="text-on-surface-variant text-base lg:text-xl max-w-2xl mx-auto leading-relaxed">
                {{ $page->meta_description }}
            </p>
            @endif
        </div>
        
        {{-- Page Content Context --}}
        <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl transition-shadow duration-500 p-6 md:p-12 lg:p-16 border border-outline-variant/20">
            <div class="prose prose-base md:prose-lg prose-emerald max-w-none text-on-surface-variant font-body leading-relaxed prose-headings:font-headline prose-headings:text-primary prose-a:text-primary hover:prose-a:text-primary-dark prose-img:rounded-2xl prose-img:shadow-lg prose-p:mb-6 prose-li:mb-2">
                {!! $page->content !!}
            </div>
        </div>

    </div>
</div>
@endsection
