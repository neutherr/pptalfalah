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
                'title'         => 'Informatika',
                'slug'          => 'informatika',
                'subtitle'      => 'Program Keahlian SMK',
                'description'   => 'Mencetak talenta digital berakhlak Qur\'ani yang siap bersaing di dunia pengembangan perangkat lunak dan teknologi masa depan berbasis AI.',
                'image'         => null,
                'icon'          => 'computer',
                'icon_bg_color' => 'bg-secondary',
                'bullet_points' => [
                    ['point' => 'Rekayasa Perangkat Lunak (Web & Mobile)'],
                    ['point' => 'Pengenalan Artificial Intelligence (AI)'],
                ],
                'order'     => 3,
                'is_active' => true,
            ],
            [
                'title'         => 'Agribisnis Terpadu',
                'slug'          => 'agribisnis-terpadu',
                'subtitle'      => 'Program Keahlian SMK',
                'description'   => 'Mengintegrasikan teknologi agrikultur modern ke dalam kurikulum untuk mencetak santri mandiri pangan dan agri-preneur handal.',
                'image'         => null,
                'icon'          => 'potted_plant',
                'icon_bg_color' => 'bg-primary',
                'bullet_points' => [
                    ['point' => 'Praktik Pertanian Berbasis Organik & IoT'],
                    ['point' => 'Hidroponik & Greenhouse Modern'],
                ],
                'order'     => 4,
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
