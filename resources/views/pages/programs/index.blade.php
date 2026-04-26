@extends('layouts.app')

@section('meta_title', 'Program Unggulan | ' . ($settings['site_name'] ?? 'Al-Falah Boarding School'))

@section('content')
<div class="pt-32 pb-24 bg-surface min-h-screen relative overflow-hidden">
    {{-- Aksen Latar --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/3 z-0"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Header Section --}}
        <div class="text-center mb-16">

            <h1 class="text-4xl md:text-5xl font-black text-on-surface font-headline tracking-tight mb-4">
                Program Pilihan <span class="text-primary">Terbaik</span>
            </h1>
            <p class="text-on-surface-variant text-lg max-w-2xl mx-auto">
                Eksplorasi ekosistem pendidikan kami yang dirancang eksklusif untuk membentuk karakter insan Qur'ani berdaya saing global.
            </p>
        </div>

        {{-- Grid Program --}}
        @if($programs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($programs as $program)
                <a href="{{ $program->slug ? route('programs.show', $program->slug) : '#' }}" class="bg-surface-container-lowest rounded-[2.5rem] shadow-sm hover:shadow-2xl border border-outline-variant/30 hover:border-primary/30 transition-all duration-500 group overflow-hidden flex flex-col hover:-translate-y-2 h-full">
                    {{-- Bagian Foto --}}
                    <div class="h-48 overflow-hidden relative">
                        <img alt="{{ $program->title }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                             src="{{ $program->image ? asset('storage/' . $program->image) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuCdiKuaWCrTGvdN0rsNO4bYxT4Ioh3XWNHfBwY8qw6M-Zx4XI0jKN9VRGVAxK2GQ8CRzvNXNzDr6Zv9A7MP9pdEs8EE4vNaepc5RgiOwKUhjzAYnyr-8kZMMy96EP0ADeaHSTY0_Ux-WobhLatDmDClwHJg1QZ0sJBUjU0aWKBm6sXmD348k6b7JP22IPTnzBEIOngz4lY3ZoOnSA_6e9Hg9VyfphJG9HI-allm3VXuQtFsdFUos_1O3GVTmUxj-85JxC__RJXS8nA' }}"
                             loading="lazy"/>
                        <div class="absolute inset-0 bg-primary/20"></div>
                    </div>
                    
                    {{-- Bagian Teks & Konten --}}
                    <div class="p-8 flex flex-col flex-grow relative">
                        <h3 class="text-2xl font-bold text-on-surface font-headline leading-tight group-hover:text-primary transition-colors mb-2">
                            {{ $program->title }}
                        </h3>
                        @if($program->subtitle)
                            <p class="text-sm font-medium text-amber-600 mb-4">{{ $program->subtitle }}</p>
                        @endif
                        
                        <p class="text-on-surface-variant mb-6 line-clamp-3 leading-relaxed">
                            {{ $program->description }}
                        </p>

                        {{-- Bullet Points --}}
                        @if(is_array($program->bullet_points) && count($program->bullet_points) > 0)
                        <div class="mt-auto mb-6">
                            <ul class="space-y-3">
                                @foreach(array_slice($program->bullet_points, 0, 3) as $point)
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-primary text-[20px] shrink-0 mt-0.5">check_circle</span>
                                    <span class="text-sm font-medium text-on-surface/80">{{ is_array($point) ? ($point['point'] ?? '') : $point }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @else
                        <div class="mt-auto mb-6"></div>
                        @endif

                        {{-- Tombol Klik --}}
                        <div class="pt-4 border-t border-outline-variant/30 flex items-center justify-between text-primary font-bold group-hover:px-2 transition-all duration-300">
                            <span>Pelajari Lebih Dalam</span>
                            <span class="material-symbols-outlined group-hover:translate-x-2 transition-transform">arrow_forward</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-20 bg-white rounded-3xl border border-outline-variant/30 shadow-sm">
                <span class="material-symbols-outlined text-6xl text-on-surface-variant/30 mb-4 inline-block">inventory_2</span>
                <h3 class="text-2xl font-bold font-headline text-on-surface mb-2">Belum Ada Program</h3>
                <p class="text-on-surface-variant max-w-md mx-auto">Silakan tambahkan data Program Unggulan melalui dashboard Filament admin.</p>
            </div>
        @endif
    </div>
</div>
@endsection
