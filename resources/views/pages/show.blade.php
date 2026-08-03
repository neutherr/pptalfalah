@extends('layouts.app')

@php
    $siteName = $settings['site_name'] ?? 'Al-Falah Boarding School';
    $pageDescription = $page->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($page->content ?? ''), 160);
    $pageImage = $page->og_image
        ? ((str_starts_with($page->og_image, 'http://') || str_starts_with($page->og_image, 'https://'))
            ? $page->og_image
            : asset('storage/'.ltrim($page->og_image, '/')))
        : asset('assets/LOGO1.jpeg');
    $pageUrl = $page->slug === 'profil' ? route('profil') : route('page.show', $page->slug);
    $pageBreadcrumb = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => $page->title, 'item' => $pageUrl],
        ],
    ];
@endphp

@section('meta_title', ($page->meta_title ?: $page->title).' | '.$siteName)
@section('meta_description', $pageDescription)
@section('og_image', $pageImage)
@section('canonical', $pageUrl)

@push('structured_data')
<script type="application/ld+json">{!! json_encode($pageBreadcrumb, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@endpush

@section('content')
<div class="pt-24 lg:pt-32 pb-12 lg:pb-24 bg-surface min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Elegant Inline Header --}}
        <div class="mb-12 border-b border-outline-variant/30 pb-10 text-center">
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
