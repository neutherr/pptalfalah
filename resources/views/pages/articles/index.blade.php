@extends('layouts.app')

@section('content')
<div class="pt-32 pb-20 bg-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-black text-on-surface mb-4 font-headline tracking-tight">Berita & Artikel Terkini</h1>
            <p class="text-on-surface-variant max-w-2xl mx-auto text-lg">Pantau terus kabar terbaru, prestasi santri, dan artikel islami dari {{ $settings['institution_name'] ?? 'Pondok Pesantren Tahfidz Al-Falah' }}.</p>
            <div class="w-24 h-1.5 bg-primary mx-auto rounded-full mt-8"></div>
        </div>
        
        @if($articles->isNotEmpty())
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($articles as $article)
                <article class="group bg-surface-container-low rounded-[2rem] overflow-hidden border border-outline-variant/30 flex flex-col transition-all duration-300 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-1 cursor-pointer"
                         onclick="window.location.href='{{ route('articles.show', $article->slug) }}'">
                    <div class="relative h-56 overflow-hidden">
                        <img alt="{{ $article->title }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                             src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuCdiKuaWCrTGvdN0rsNO4bYxT4Ioh3XWNHfBwY8qw6M-Zx4XI0jKN9VRGVAxK2GQ8CRzvNXNzDr6Zv9A7MP9pdEs8EE4vNaepc5RgiOwKUhjzAYnyr-8kZMMy96EP0ADeaHSTY0_Ux-WobhLatDmDClwHJg1QZ0sJBUjU0aWKBm6sXmD348k6b7JP22IPTnzBEIOngz4lY3ZoOnSA_6e9Hg9VyfphJG9HI-allm3VXuQtFsdFUos_1O3GVTmUxj-85JxC__RJXS8nA' }}"
                             loading="lazy"/>
                        @if($article->category)
                        <div class="absolute top-4 left-4">
                            <span class="px-4 py-1.5 bg-white/90 backdrop-blur-sm text-primary text-xs font-bold rounded-full shadow-sm">
                                {{ $article->category->name }}
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="p-8 flex flex-col flex-1">
                        <div class="flex items-center gap-4 text-sm text-on-surface-variant mb-4">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                                <time datetime="{{ $article->published_at?->format('Y-m-d') }}">
                                    {{ $article->published_at?->format('d M Y') ?? $article->created_at->format('d M Y') }}
                                </time>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px]">person</span>
                                <span>{{ $article->author?->name ?? 'Admin' }}</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-on-surface mb-3 font-headline group-hover:text-primary transition-colors line-clamp-2">
                            {{ $article->title }}
                        </h3>
                        <p class="text-on-surface-variant line-clamp-3 mb-6 flex-1">
                            {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}
                        </p>
                        <div class="flex items-center text-primary font-bold text-sm mt-auto group-hover:gap-2 transition-all">
                            Baca Selengkapnya
                            <span class="material-symbols-outlined text-sm ml-1 transition-transform group-hover:translate-x-1">arrow_forward</span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($articles->hasPages())
            <div class="mt-16">
                {{ $articles->links() }}
            </div>
            @endif

        @else
            {{-- EMPTY STATE --}}
            <div class="text-center py-24 bg-surface-container-lowest rounded-[3rem] border border-outline-variant/30 border-dashed">
                <span class="material-symbols-outlined text-6xl text-outline mb-4">article</span>
                <h3 class="text-2xl font-bold text-on-surface mb-2 font-headline">Belum Ada Berita</h3>
                <p class="text-on-surface-variant max-w-md mx-auto">Kami belum mempublikasikan berita atau artikel apapun saat ini. Nantikan kabar terbaru dari kami.</p>
            </div>
        @endif

    </div>
</div>
@endsection
