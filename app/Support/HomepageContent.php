<?php

namespace App\Support;

class HomepageContent
{
    public const MISSION_COUNT = 8;

    public const MEDIA_DEFAULTS = [
        'about_media_type' => 'image',
        'about_image' => '',
        'about_video_url' => '',
    ];

    public static function clientRevision(): array
    {
        $content = [
            'hero_title' => 'Berkarakter Islami, Berjiwa Kewirausahaan, Berdaya Saing Global, Berbasis Teknologi, Berwawasan Lingkungan',
            'site_tagline' => "Mencetak Generasi Qur'ani, Mandiri, dan Berprestasi",
            'hero_description' => 'SMK Pertanian berbasis Boarding School',
            'vision_headline' => 'Terwujudnya satuan pendidikan vokasi unggul, guna mencetak generasi berkarakter islami, berjiwa kewirausahaan, dan berdaya saing global, yang berbasis teknologi berwawasan lingkungan',
            'school_motto' => 'Ilmu, Iman, Terampil, dan Mandiri.',
        ];

        $missions = [
            'Menerapkan pembelajaran berbasis pembiasaan yang berdasarkan nilai-nilai Al Qur’an dan As Sunah.',
            'Meningkatkan kepedulian siswa terhadap keseimbangan antara teknologi dan kelestarian lingkungan.',
            'Menyediakan sarana dan prasarana pendidikan yang memadai untuk pengembangan ilmu dan keterampilan, serta pembinaan karakter siswa.',
            'Membentuk siswa yang memiliki daya saing tinggi secara intelektual, keterampilan dan teknologi.',
            'Membentuk siswa yang kreatif, inovatif serta adaptif terhadap perkembangan teknologi, kebutuhan dunia kerja dan siap melanjutkan pendidikan ke jenjang yang lebih tinggi.',
            'Mengembangkan jiwa kepemimpinan, kewirausahaan dan kemitraan.',
            'Membangun kerja sama dengan dunia usaha dan industri, perguruan tinggi, pemerintah, dan masyarakat.',
            'Mengembangkan peran sekolah sebagai pusat pengembangan keahlian, riset, inovasi, budaya hidup sehat, dan wawasan lingkungan.',
        ];

        foreach ($missions as $index => $description) {
            $number = $index + 1;
            $content["mission_{$number}_title"] = '';
            $content["mission_{$number}_desc"] = $description;
        }

        return $content;
    }

    /** Preserve the visible legacy copy until an administrator saves the new fields. */
    public static function fromSettings(array $settings): array
    {
        $legacyHeadline = html_entity_decode(strip_tags(preg_replace(
            '/<br\s*\/?\s*>/i',
            "\n",
            $settings['vision_headline'] ?? "Menanam Iman, Menuai Kemandirian\nPesantren Tahfidz, Informatika & Agribisnis Terpadu",
        )), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $content = [
            'hero_title' => $settings['hero_title'] ?? $legacyHeadline,
            'site_tagline' => $settings['site_tagline'] ?? 'SMK Al-Falah Boarding School',
            'hero_description' => $settings['hero_description'] ?? 'SMK Pertanian & IT berbasis Pesantren Tahfidz di Jonggol, Bogor.',
            'vision_headline' => $settings['vision_headline'] ?? "Mencetak Generasi\nQur'ani, Mandiri,\ndan Berprestasi.",
            'school_motto' => $settings['school_motto'] ?? '',
        ];

        for ($i = 1; $i <= self::MISSION_COUNT; $i++) {
            $content["mission_{$i}_title"] = $settings["mission_{$i}_title"] ?? '';
            $content["mission_{$i}_desc"] = $settings["mission_{$i}_desc"] ?? '';
        }

        foreach (self::MEDIA_DEFAULTS as $key => $default) {
            $content[$key] = $settings[$key] ?? $default;
        }

        return $content;
    }
}
