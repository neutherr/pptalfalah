<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'title'         => "Tahfidz Al-Qur'an",
                'slug'          => 'tahfidz-al-quran',
                'subtitle'      => 'Program Utama',
                'description'   => "Metode tahfidz intensif dengan target mutqin, dibimbing oleh asatidz bersanad untuk menjaga kualitas hafalan.",
                'image'         => null,
                'icon'          => 'menu_book',
                'icon_bg_color' => 'bg-primary-container',
                'bullet_points' => [
                    ['point' => 'Target 30 Juz Mutqin'],
                    ['point' => "Tasmi' Hafalan 1 Juz & Kelipatan 5 Juz"],
                ],
                'order'     => 1,
                'is_active' => true,
            ],
            [
                'title'         => 'Boarding School System',
                'slug'          => 'boarding-school-system',
                'subtitle'      => 'Sistem Asrama',
                'description'   => 'Ekosistem 24 jam yang membentuk karakter disiplin, adab, dan kemandirian melalui program asrama yang terstruktur.',
                'image'         => null,
                'icon'          => 'domain',
                'icon_bg_color' => 'bg-secondary-container',
                'bullet_points' => [
                    ['point' => 'Lingkungan Berbahasa Arab'],
                    ['point' => 'Pembinaan Adab & Akhlak'],
                ],
                'order'     => 2,
                'is_active' => true,
            ],
            [
                'title'         => 'Agricultural Excellence',
                'slug'          => 'agricultural-excellence',
                'subtitle'      => 'SMK Pertanian',
                'description'   => 'Mengintegrasikan teknologi pertanian modern ke dalam kurikulum SMK untuk mencetak santri mandiri pangan.',
                'image'         => null,
                'icon'          => 'potted_plant',
                'icon_bg_color' => 'bg-primary',
                'bullet_points' => [
                    ['point' => 'Praktik Pertanian Berbasis Organik'],
                    ['point' => 'Hidroponik & Greenhouse'],
                ],
                'order'     => 3,
                'is_active' => true,
            ],
        ];

        foreach ($programs as $program) {
            Program::updateOrCreate(
                ['title' => $program['title']],
                $program
            );
        }
    }
}
