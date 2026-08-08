@extends('layouts.app')

@section('meta_title', 'Pendaftaran PPDB Online | PPT Al-Falah')
@section('meta_description', 'Formulir pendaftaran PPDB online Pondok Pesantren Tahfidz Al-Falah.')
@section('robots', 'noindex,follow')

@section('content')
<div class="min-h-screen bg-surface py-12 lg:py-16">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        @if($openWave)
            <livewire:ppdb.registration-wizard :wave="$openWave" />
        @else
            <section class="mx-auto max-w-2xl rounded-xl border border-outline-variant bg-surface-container-lowest p-6 sm:p-10">
                <p class="mb-2 text-sm font-semibold text-primary">PPDB Online</p>
                <h1 class="mb-4 text-3xl font-bold tracking-tight text-on-surface">Pendaftaran sedang ditutup</h1>
                <p class="mb-8 leading-relaxed text-on-surface-variant">Saat ini belum ada gelombang pendaftaran yang aktif. Informasi jadwal dan persyaratan terbaru tetap tersedia di halaman PPDB.</p>
                <a href="{{ route('ppdb') }}" class="inline-flex min-h-11 items-center justify-center rounded-lg bg-primary px-5 py-3 font-semibold text-white transition-colors hover:bg-primary-container focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">Lihat Info PPDB</a>
            </section>
        @endif
    </div>
</div>
@endsection
