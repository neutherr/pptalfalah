@extends('layouts.app')

@section('content')
{{-- ARTICLE HEADER --}}
<div class="relative pt-32 pb-16 lg:pt-40 lg:pb-24 overflow-hidden bg-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        @if($article->category)
        <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary text-sm font-bold rounded-full mb-6">
            {{ $article->category->name }}
        </span>
        @endif
        
        <h1 class="text-3xl md:text-5xl font-black text-on-surface mb-8 font-headline leading-tight">
            {{ $article->title }}
        </h1>
        
        <div class="flex flex-wrap items-center justify-center gap-6 text-on-surface-variant text-sm">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-outline">calendar_month</span>
                <time datetime="{{ $article->published_at?->format('Y-m-d') }}">
                    {{ $article->published_at?->format('d F Y') ?? $article->created_at->format('d F Y') }}
                </time>
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-outline">person</span>
                <span>Ditulis oleh <strong class="text-on-surface">{{ $article->author?->name ?? 'Admin' }}</strong></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-outline">visibility</span>
                <span>{{ number_format($article->views ?? 0) }}x dibaca</span>
            </div>
        </div>
    </div>
</div>

{{-- FEATURED IMAGE --}}
@if($article->featured_image)
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 mb-16 relative z-10">
    <div class="rounded-3xl overflow-hidden shadow-2xl bg-surface-container-low aspect-w-16 aspect-h-9">
        <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
    </div>
</div>
@else
<div class="mb-12"></div>
@endif

{{-- ARTICLE CONTENT --}}
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">
    <article class="prose prose-lg prose-emerald max-w-none prose-headings:font-headline prose-a:text-primary hover:prose-a:text-primary-dark prose-img:rounded-2xl prose-img:shadow-lg">
        {!! $article->content !!}
    </article>
    
    {{-- Share & Tags area (Optional for future) --}}
    <div class="mt-12 pt-8 border-t border-outline-variant/30 flex justify-between items-center">
        <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary-dark font-bold transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali ke Indeks Berita
        </a>
    </div>
</div>

{{-- RELATED ARTICLES --}}
@if($related->isNotEmpty())
<div class="bg-surface-container-lowest py-20 border-t border-outline-variant/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black text-on-surface font-headline">Baca Juga</h2>
            <div class="w-16 h-1 bg-primary mx-auto rounded-full mt-4"></div>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @foreach($related as $rel)
            <article class="group bg-surface rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-outline-variant/50 cursor-pointer"
                     onclick="window.location.href='{{ route('articles.show', $rel->slug) }}'">
                @if($rel->featured_image)
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ asset('storage/' . $rel->featured_image) }}" alt="{{ $rel->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                </div>
                @endif
                <div class="p-6">
                    <small class="text-primary font-bold block mb-2">{{ $rel->published_at?->format('d M Y') }}</small>
                    <h3 class="font-bold text-lg text-on-surface mb-2 font-headline group-hover:text-primary transition-colors line-clamp-2">{{ $rel->title }}</h3>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
