@extends('layouts.app')

@section('content')
<div class="pt-32 pb-20 bg-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-black text-on-surface mb-4 font-headline tracking-tight">Agenda Kegiatan</h1>
            <p class="text-on-surface-variant max-w-2xl mx-auto text-lg mb-8">Kalender acara, kajian rutin, dan jadawal kegiatan santri di lingkungan pondok.</p>
            <div class="w-16 h-1.5 bg-primary mx-auto rounded-full mt-6"></div>
        </div>

        @if($agendas->isNotEmpty())
            <div class="grid gap-6">
                @foreach($agendas as $agenda)
                <a href="{{ route('agendas.show', $agenda->slug) }}" class="group block bg-surface-container-lowest border border-outline-variant/30 rounded-3xl p-4 sm:p-6 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                        
                        {{-- CALENDAR DATE BLOCK --}}
                        <div class="bg-primary/5 border border-primary/20 rounded-2xl p-4 text-center min-w-[100px] shrink-0 group-hover:bg-primary group-hover:text-white transition-colors">
                            <span class="block text-primary group-hover:text-amber-300 font-bold text-sm uppercase tracking-wider mb-1">{{ $agenda->start_datetime->format('M') }}</span>
                            <span class="block text-4xl font-black font-headline text-on-surface group-hover:text-white leading-none mb-1">{{ $agenda->start_datetime->format('d') }}</span>
                            <span class="block text-on-surface-variant group-hover:text-emerald-100 text-xs font-bold">{{ $agenda->start_datetime->format('Y') }}</span>
                        </div>

                        {{-- DETAILS --}}
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-on-surface mb-3 font-headline group-hover:text-primary transition-colors">{{ $agenda->title }}</h3>
                            
                            <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-on-surface-variant">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">schedule</span>
                                    <span>
                                        {{ $agenda->start_datetime->format('H:i') }}
                                        @if($agenda->end_datetime)
                                            - {{ $agenda->end_datetime->format('H:i') }}
                                        @else
                                            - Selesai
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">location_on</span>
                                    <span>{{ $agenda->location ?? 'Lingkungan Pesantren' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- ARROW --}}
                        <div class="hidden sm:flex w-12 h-12 rounded-full bg-surface-container items-center justify-center text-outline group-hover:bg-primary group-hover:text-white transition-colors shrink-0">
                            <span class="material-symbols-outlined transform group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </div>

                    </div>
                </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($agendas->hasPages())
            <div class="mt-12">
                {{ $agendas->links() }}
            </div>
            @endif

        @else
            {{-- EMPTY STATE --}}
            <div class="text-center py-24 bg-surface-container-lowest rounded-[3rem] border border-outline-variant/30 border-dashed">
                <span class="material-symbols-outlined text-6xl text-outline mb-4">event_busy</span>
                <h3 class="text-2xl font-bold text-on-surface mb-2 font-headline">Belum Ada Agenda</h3>
                <p class="text-on-surface-variant max-w-md mx-auto">Masih belum ada agenda kegiatan yang dijadwalkan dalam waktu dekat ini.</p>
            </div>
        @endif

    </div>
</div>
@endsection
