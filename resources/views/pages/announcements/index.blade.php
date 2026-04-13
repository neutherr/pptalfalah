@extends('layouts.app')

@section('content')
<div class="pt-32 pb-20 bg-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-black text-on-surface mb-4 font-headline tracking-tight">Pusat Pengumuman</h1>
            <p class="text-on-surface-variant max-w-2xl mx-auto text-lg mb-8">Informasi penting, edaran resmi, dan pemberitahuan dari pengurus pondok.</p>
            <div class="w-16 h-1.5 bg-primary mx-auto rounded-full mt-6"></div>
        </div>

        {{-- PINNED ANNOUNCEMENTS --}}
        @if($pinned->isNotEmpty())
            <div class="mb-16">
                <div class="flex items-center gap-2 mb-6 text-amber-500">
                    <span class="material-symbols-outlined text-2xl">push_pin</span>
                    <h2 class="text-2xl font-black font-headline">Disematkan</h2>
                </div>
                <div class="grid gap-4">
                    @foreach($pinned as $item)
                    <a href="{{ route('announcements.show', $item->slug) }}" class="block bg-amber-50/50 border-2 border-amber-500/20 rounded-2xl p-6 hover:bg-amber-50 transition-colors group">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-bold text-on-surface mb-2 font-headline group-hover:text-amber-600 transition-colors">{{ $item->title }}</h3>
                                <div class="flex items-center gap-2 text-on-surface-variant text-sm">
                                    <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                                    <span>{{ $item->published_at?->format('d M Y') ?? $item->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-amber-500 transform group-hover:translate-x-2 transition-transform">arrow_forward</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ALL ANNOUNCEMENTS --}}
        @if($announcements->isNotEmpty())
            <div class="mb-16">
                <h2 class="text-2xl font-black font-headline text-on-surface mb-6">Semua Pengumuman</h2>
                <div class="bg-surface-container-low rounded-3xl p-4 sm:p-6 shadow-sm border border-outline-variant/30">
                    <div class="divide-y divide-outline-variant/20">
                        @foreach($announcements as $item)
                        <a href="{{ route('announcements.show', $item->slug) }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-6 px-4 hover:bg-surface-container/50 rounded-xl transition-colors group">
                            <div>
                                <h3 class="text-lg font-bold text-on-surface mb-2 group-hover:text-primary transition-colors">{{ $item->title }}</h3>
                                <div class="flex items-center gap-2 text-on-surface-variant text-sm">
                                    <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                                    <span>{{ $item->published_at?->format('d M Y') ?? $item->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-outline group-hover:text-primary transform group-hover:translate-x-2 transition-all">arrow_forward</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                
                {{-- Pagination --}}
                @if($announcements->hasPages())
                <div class="mt-8">
                    {{ $announcements->links() }}
                </div>
                @endif
            </div>
        @endif

        {{-- EMPTY STATE --}}
        @if($pinned->isEmpty() && $announcements->isEmpty())
            <div class="text-center py-24 bg-surface-container-lowest rounded-[3rem] border border-outline-variant/30 border-dashed">
                <span class="material-symbols-outlined text-6xl text-outline mb-4">campaign</span>
                <h3 class="text-2xl font-bold text-on-surface mb-2 font-headline">Belum Ada Pengumuman</h3>
                <p class="text-on-surface-variant max-w-md mx-auto">Saat ini tidak ada pengumuman terbaru dari administrasi pondok.</p>
            </div>
        @endif

    </div>
</div>
@endsection
