<section>
    <header class="mb-8">
        <p class="mb-2 text-sm font-semibold text-primary">{{ $wave->period->academic_year }} · {{ $wave->name }}</p>
        <h1 class="text-3xl font-bold tracking-tight text-on-surface sm:text-4xl">Formulir Pendaftaran PPDB</h1>
        <p class="mt-3 max-w-2xl leading-relaxed text-on-surface-variant">Isi data secara bertahap. Data baru tersimpan setelah Anda mengirim formulir pada tahap konfirmasi.</p>
        <p class="mt-3 font-semibold text-primary">Gratis biaya pendidikan selama 1 tahun pertama.</p>
    </header>

    @php($steps = [1 => 'Data Pribadi', 2 => 'Sekolah Asal', 3 => 'Orang Tua', 4 => 'Konfirmasi'])
    <div class="mb-8 lg:hidden">
        <div class="mb-2 flex items-center justify-between gap-4"><p class="text-sm font-semibold text-on-surface">Langkah {{ $step }} dari 4</p><p class="text-sm text-on-surface-variant">{{ $steps[$step] }}</p></div>
        <div class="h-2 overflow-hidden rounded-full bg-surface-container-high"><div class="h-full bg-primary transition-all duration-200" style="width: {{ $step * 25 }}%"></div></div>
    </div>

    <ol class="mb-10 hidden grid-cols-4 border-y border-outline-variant lg:grid">
        @foreach($steps as $number => $label)
            <li class="{{ !$loop->last ? 'border-r border-outline-variant' : '' }} py-5">
                <button type="button" wire:click="goToStep({{ $number }})" @disabled($number > $step) class="flex min-h-11 w-full items-center gap-3 px-4 text-left disabled:cursor-default">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full text-sm font-bold {{ $number <= $step ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant' }}">{{ $number }}</span>
                    <span class="text-sm font-semibold {{ $number === $step ? 'text-primary' : 'text-on-surface-variant' }}">{{ $label }}</span>
                </button>
            </li>
        @endforeach
    </ol>

    <form wire:submit="submit" class="rounded-xl border border-outline-variant bg-surface-container-lowest p-5 sm:p-8" novalidate>
        <div class="absolute -left-[10000px]" aria-hidden="true"><label for="website">Website</label><input id="website" type="text" wire:model="website" tabindex="-1" autocomplete="off"></div>
        @error('registration')<div class="mb-6 rounded-lg border border-error/30 bg-error-container p-4 text-sm font-semibold text-on-error-container" role="alert">{{ $message }}</div>@enderror

        @if($step === 1)
            <div>
                <h2 class="text-2xl font-bold text-on-surface">Data Pribadi Calon Santri</h2>
                <p class="mt-2 text-sm text-on-surface-variant">Kolom bertanda * wajib diisi.</p>
                <div class="mt-7 grid gap-5 sm:grid-cols-2">
                    <x-ppdb.input label="Nama lengkap *" name="fullName" wire:model="fullName" autocomplete="name" />
                    <x-ppdb.select label="Jenis kelamin *" name="gender" wire:model="gender"><option value="">Pilih jenis kelamin</option><option value="male">Laki-laki</option><option value="female">Perempuan</option></x-ppdb.select>
                    <x-ppdb.input label="NIK *" name="nik" wire:model="nik" inputmode="numeric" maxlength="16" autocomplete="off" />
                    <x-ppdb.input label="Nomor KK" name="familyCardNumber" wire:model="familyCardNumber" inputmode="numeric" maxlength="16" autocomplete="off" />
                    <x-ppdb.input label="NISN" name="nisn" wire:model="nisn" inputmode="numeric" maxlength="10" />
                    <x-ppdb.input label="Tempat lahir *" name="birthPlace" wire:model="birthPlace" />
                    <x-ppdb.input label="Tanggal lahir *" name="birthDate" wire:model="birthDate" type="date" />
                    <div class="sm:col-span-2"><x-ppdb.textarea label="Alamat lengkap *" name="address" wire:model="address" rows="3" /></div>
                    <x-ppdb.select label="Provinsi *" name="provinceCode" wire:model.live="provinceCode"><option value="">Pilih provinsi</option>@foreach($this->provinces as $province)<option value="{{ $province->code }}">{{ $province->name }}</option>@endforeach</x-ppdb.select>
                    <x-ppdb.select label="Kabupaten/Kota *" name="districtCityCode" wire:model.live="districtCityCode" :disabled="blank($provinceCode)"><option value="">Pilih kabupaten/kota</option>@foreach($this->districts as $district)<option value="{{ $district->code }}">{{ $district->name }}</option>@endforeach</x-ppdb.select>
                    <x-ppdb.select label="Kecamatan *" name="subdistrictCode" wire:model.live="subdistrictCode" :disabled="blank($districtCityCode)"><option value="">Pilih kecamatan</option>@foreach($this->subdistricts as $subdistrict)<option value="{{ $subdistrict->code }}">{{ $subdistrict->name }}</option>@endforeach</x-ppdb.select>
                    <x-ppdb.select label="Kelurahan/Desa *" name="villageCode" wire:model="villageCode" :disabled="blank($subdistrictCode)"><option value="">Pilih kelurahan/desa</option>@foreach($this->villages as $village)<option value="{{ $village->code }}">{{ $village->name }}</option>@endforeach</x-ppdb.select>
                    <x-ppdb.input label="Kode pos" name="postalCode" wire:model="postalCode" inputmode="numeric" maxlength="5" />
                    <x-ppdb.input label="Nomor HP calon santri" name="studentPhone" wire:model="studentPhone" inputmode="tel" autocomplete="tel" />
                    <div class="sm:col-span-2">
                        <label for="photo" class="mb-2 block text-sm font-semibold text-on-surface">Foto formal *</label>
                        <div class="grid gap-4 rounded-lg border border-outline-variant p-4 sm:grid-cols-[8rem_1fr] sm:items-center">
                            <div class="flex aspect-[3/4] w-28 items-center justify-center overflow-hidden rounded-lg bg-surface-container text-center text-xs text-on-surface-variant">
                                @if($photo && $photo->isPreviewable())<img src="{{ $photo->temporaryUrl() }}" alt="Preview foto formal" class="h-full w-full object-cover">@else Preview foto @endif
                            </div>
                            <div>
                                <input id="photo" type="file" wire:model="photo" accept="image/jpeg,image/png,image/webp" class="block min-h-11 w-full rounded-lg border border-outline-variant bg-white px-3 py-2.5 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-primary file:px-3 file:py-2 file:font-semibold file:text-white focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                <p class="mt-2 text-sm text-on-surface-variant">JPG, PNG, atau WebP maksimal 2 MB. Gunakan foto portrait formal dengan wajah terlihat jelas.</p>
                                @error('photo')<p class="mt-2 text-sm font-medium text-error" role="alert">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($step === 2)
            <div>
                <h2 class="text-2xl font-bold text-on-surface">Data Sekolah Asal</h2>
                <p class="mt-2 text-sm text-on-surface-variant">Cukup isi identitas sekolah terakhir.</p>
                <div class="mt-7 grid gap-5 sm:grid-cols-2"><x-ppdb.input label="Nama sekolah asal *" name="schoolName" wire:model="schoolName" /><x-ppdb.input label="NPSN" name="npsn" wire:model="npsn" inputmode="numeric" maxlength="8" /></div>
            </div>
        @elseif($step === 3)
            <div>
                <h2 class="text-2xl font-bold text-on-surface">Data Orang Tua / Wali</h2>
                <p class="mt-2 text-sm text-on-surface-variant">Pilih satu kontak utama yang dapat dihubungi melalui WhatsApp.</p>
                <div class="mt-7 grid gap-5 sm:grid-cols-2">
                    <x-ppdb.input label="Nama ayah *" name="fatherName" wire:model="fatherName" /><x-ppdb.input label="Pendidikan ayah" name="fatherEducation" wire:model="fatherEducation" />
                    <x-ppdb.input label="Pekerjaan ayah" name="fatherJob" wire:model="fatherJob" /><x-ppdb.input label="Nama ibu *" name="motherName" wire:model="motherName" />
                    <x-ppdb.input label="Pendidikan ibu" name="motherEducation" wire:model="motherEducation" /><x-ppdb.input label="Pekerjaan ibu" name="motherJob" wire:model="motherJob" />
                    <x-ppdb.select label="Kontak utama *" name="primaryContactRelation" wire:model.live="primaryContactRelation"><option value="">Pilih kontak utama</option><option value="father">Ayah</option><option value="mother">Ibu</option><option value="guardian">Wali</option></x-ppdb.select>
                    <x-ppdb.input label="Nomor WhatsApp *" name="primaryContactPhone" wire:model="primaryContactPhone" inputmode="tel" autocomplete="tel" />
                    @if($primaryContactRelation === 'guardian')
                        <x-ppdb.input label="Nama wali *" name="guardianName" wire:model="guardianName" /><x-ppdb.input label="Hubungan dengan calon santri *" name="guardianRelationship" wire:model="guardianRelationship" />
                        <x-ppdb.input label="Pendidikan wali" name="guardianEducation" wire:model="guardianEducation" /><x-ppdb.input label="Pekerjaan wali" name="guardianJob" wire:model="guardianJob" />
                    @endif
                </div>
            </div>
        @else
            <div>
                <h2 class="text-2xl font-bold text-on-surface">Konfirmasi Data</h2>
                <p class="mt-2 text-sm text-on-surface-variant">Periksa kembali seluruh data sebelum mengirim pendaftaran.</p>
                <div class="mt-7 divide-y divide-outline-variant border-y border-outline-variant">
                    <section class="py-6">
                        <h3 class="mb-5 text-lg font-bold text-on-surface">Data Pribadi</h3>
                        <div class="grid gap-6 sm:grid-cols-[7rem_1fr]">
                            @if($photo && $photo->isPreviewable())<img src="{{ $photo->temporaryUrl() }}" alt="Foto formal calon santri" class="aspect-[3/4] w-28 rounded-lg object-cover">@endif
                            <dl class="grid gap-x-8 gap-y-5 sm:grid-cols-2">
                                <div><dt class="text-sm text-on-surface-variant">Nama</dt><dd class="mt-1 font-semibold text-on-surface">{{ $fullName }}</dd></div>
                                <div><dt class="text-sm text-on-surface-variant">Jenis kelamin</dt><dd class="mt-1 font-semibold text-on-surface">{{ $gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</dd></div>
                                <div><dt class="text-sm text-on-surface-variant">NIK</dt><dd class="mt-1 font-semibold text-on-surface">•••• •••• •••• {{ substr($nik, -4) }}</dd></div>
                                <div><dt class="text-sm text-on-surface-variant">Nomor KK</dt><dd class="mt-1 font-semibold text-on-surface">{{ $familyCardNumber ? '•••• •••• •••• '.substr($familyCardNumber, -4) : '-' }}</dd></div>
                                <div><dt class="text-sm text-on-surface-variant">NISN</dt><dd class="mt-1 font-semibold text-on-surface">{{ $nisn ?: '-' }}</dd></div>
                                <div><dt class="text-sm text-on-surface-variant">Tempat, tanggal lahir</dt><dd class="mt-1 font-semibold text-on-surface">{{ $birthPlace }}, {{ $birthDate ? \Illuminate\Support\Carbon::parse($birthDate)->format('d/m/Y') : '-' }}</dd></div>
                                <div><dt class="text-sm text-on-surface-variant">Nomor HP</dt><dd class="mt-1 font-semibold text-on-surface">{{ $studentPhone ?: '-' }}</dd></div>
                                <div class="sm:col-span-2"><dt class="text-sm text-on-surface-variant">Alamat</dt><dd class="mt-1 font-semibold text-on-surface">{{ $address }}</dd></div>
                                <div class="sm:col-span-2"><dt class="text-sm text-on-surface-variant">Wilayah</dt><dd class="mt-1 font-semibold text-on-surface">{{ implode(', ', array_filter([$areaNames['village'] ?? null, $areaNames['subdistrict'] ?? null, $areaNames['district'] ?? null, $areaNames['province'] ?? null])) }}{{ $postalCode ? ' · '.$postalCode : '' }}</dd></div>
                            </dl>
                        </div>
                    </section>
                    <section class="py-6">
                        <h3 class="mb-5 text-lg font-bold text-on-surface">Sekolah Asal</h3>
                        <dl class="grid gap-x-8 gap-y-5 sm:grid-cols-2">
                            <div><dt class="text-sm text-on-surface-variant">Nama sekolah</dt><dd class="mt-1 font-semibold text-on-surface">{{ $schoolName }}</dd></div>
                            <div><dt class="text-sm text-on-surface-variant">NPSN</dt><dd class="mt-1 font-semibold text-on-surface">{{ $npsn ?: '-' }}</dd></div>
                        </dl>
                    </section>
                    <section class="py-6">
                        <h3 class="mb-5 text-lg font-bold text-on-surface">Orang Tua / Wali</h3>
                        <dl class="grid gap-x-8 gap-y-5 sm:grid-cols-2">
                            <div><dt class="text-sm text-on-surface-variant">Ayah</dt><dd class="mt-1 font-semibold text-on-surface">{{ $fatherName }}</dd><dd class="text-sm text-on-surface-variant">{{ implode(' · ', array_filter([$fatherEducation, $fatherJob])) ?: '-' }}</dd></div>
                            <div><dt class="text-sm text-on-surface-variant">Ibu</dt><dd class="mt-1 font-semibold text-on-surface">{{ $motherName }}</dd><dd class="text-sm text-on-surface-variant">{{ implode(' · ', array_filter([$motherEducation, $motherJob])) ?: '-' }}</dd></div>
                            @if($primaryContactRelation === 'guardian')<div><dt class="text-sm text-on-surface-variant">Wali</dt><dd class="mt-1 font-semibold text-on-surface">{{ $guardianName }} · {{ $guardianRelationship }}</dd><dd class="text-sm text-on-surface-variant">{{ implode(' · ', array_filter([$guardianEducation, $guardianJob])) ?: '-' }}</dd></div>@endif
                            <div><dt class="text-sm text-on-surface-variant">Kontak utama</dt><dd class="mt-1 font-semibold text-on-surface">{{ ['father' => 'Ayah', 'mother' => 'Ibu', 'guardian' => 'Wali'][$primaryContactRelation] ?? '-' }} · {{ $primaryContactPhone }}</dd></div>
                        </dl>
                    </section>
                </div>
                <div class="mt-6 space-y-4">
                    <label class="flex min-h-11 items-start gap-3"><input type="checkbox" wire:model="accuracyAccepted" class="mt-1 size-5 rounded border-outline-variant text-primary focus:ring-primary"><span class="text-sm leading-relaxed text-on-surface">Saya menyatakan semua data yang diisi benar dan dapat dipertanggungjawabkan.</span></label>
                    @error('accuracyAccepted')<p class="text-sm font-medium text-error" role="alert">{{ $message }}</p>@enderror
                    <label class="flex min-h-11 items-start gap-3"><input type="checkbox" wire:model="privacyAccepted" class="mt-1 size-5 rounded border-outline-variant text-primary focus:ring-primary"><span class="text-sm leading-relaxed text-on-surface">Saya menyetujui <a href="{{ route('page.show', 'kebijakan-privasi-ppdb') }}" target="_blank" class="font-semibold text-primary underline underline-offset-2">Kebijakan Privasi PPDB</a>.</span></label>
                    @error('privacyAccepted')<p class="text-sm font-medium text-error" role="alert">{{ $message }}</p>@enderror
                </div>
            </div>
        @endif

        <div class="mt-8 flex flex-col-reverse gap-3 border-t border-outline-variant pt-6 sm:flex-row sm:justify-between">
            @if($step > 1)<button type="button" wire:click="previousStep" wire:loading.attr="disabled" class="inline-flex min-h-11 items-center justify-center rounded-lg border border-outline-variant px-5 py-3 font-semibold text-on-surface transition-colors hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">Kembali</button>@else<span></span>@endif
            @if($step < 4)
                <button type="button" wire:click="nextStep" wire:loading.attr="disabled" class="inline-flex min-h-11 items-center justify-center rounded-lg bg-primary px-6 py-3 font-semibold text-white transition-colors hover:bg-primary-container focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"><span wire:loading.remove wire:target="nextStep">Lanjut</span><span wire:loading wire:target="nextStep">Memeriksa...</span></button>
            @else
                <button type="submit" wire:loading.attr="disabled" class="inline-flex min-h-11 items-center justify-center rounded-lg bg-primary px-6 py-3 font-semibold text-white transition-colors hover:bg-primary-container focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"><span wire:loading.remove wire:target="submit">Kirim Pendaftaran</span><span wire:loading wire:target="submit">Mengirim...</span></button>
            @endif
        </div>
    </form>
</section>
