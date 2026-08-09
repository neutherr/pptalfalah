@extends('layouts.app')

@section('meta_title', 'Bukti Pendaftaran '.$registration->registration_number.' | PPT Al-Falah')
@section('meta_description', 'Bukti pendaftaran PPDB online PPT Al-Falah.')
@section('robots', 'noindex,nofollow')

@section('content')
@php
    $message = urlencode("Assalamu'alaikum Panitia PPDB PPT Al-Falah.\n\nSaya sudah mengisi pendaftaran online atas nama {$registration->full_name} dengan nomor pendaftaran {$registration->registration_number}. Mohon ditinjau. Terima kasih.");
    $whatsapp = 'https://wa.me/'.($settings['whatsapp_number'] ?? '6281510029919').'?text='.$message;
@endphp
<div class="min-h-screen bg-surface py-12 lg:py-16">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <article class="rounded-xl border border-outline-variant bg-surface-container-lowest p-6 sm:p-10 print:border-0 print:p-0">
            <p class="mb-2 text-sm font-semibold text-primary">Data Berhasil Diterima</p>
            <h1 class="mb-4 text-3xl font-bold tracking-tight text-on-surface">Bukti Pendaftaran PPDB</h1>
            <p class="mb-8 max-w-2xl leading-relaxed text-on-surface-variant"><strong class="text-on-surface">Data pendaftaran berhasil diterima</strong> dan masih menunggu peninjauan panitia. Beri tahu panitia melalui WhatsApp agar pendaftaran lebih cepat diketahui.</p>
            <dl class="grid gap-5 border-y border-outline-variant py-6 sm:grid-cols-2">
                <div><dt class="text-sm text-on-surface-variant">Nomor pendaftaran</dt><dd class="mt-1 text-lg font-bold text-on-surface">{{ $registration->registration_number }}</dd></div>
                <div><dt class="text-sm text-on-surface-variant">Nama calon santri</dt><dd class="mt-1 font-semibold text-on-surface">{{ $registration->full_name }}</dd></div>
                <div><dt class="text-sm text-on-surface-variant">Tahun ajaran</dt><dd class="mt-1 font-semibold text-on-surface">{{ $registration->period->academic_year }}</dd></div>
                <div><dt class="text-sm text-on-surface-variant">Gelombang</dt><dd class="mt-1 font-semibold text-on-surface">{{ $registration->wave->name }}</dd></div>
                <div><dt class="text-sm text-on-surface-variant">Tanggal daftar</dt><dd class="mt-1 font-semibold text-on-surface">{{ $registration->submitted_at->translatedFormat('d F Y, H:i') }} WIB</dd></div>
            </dl>
            <div class="mt-8 print:hidden">
                <a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center justify-center rounded-lg bg-primary px-5 py-3 font-semibold text-white transition-colors hover:bg-primary-container focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">Beri Tahu Panitia via WhatsApp</a>
            </div>
        </article>
    </div>
</div>
@endsection
