<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ===================== KONTAK =====================
            ['key' => 'whatsapp_number',   'value' => '6281510029919',                   'group' => 'contact'],
            ['key' => 'whatsapp_message',  'value' => "Assalamu'alaikum, saya ingin bertanya tentang PPDB Al-Falah Boarding School.", 'group' => 'contact'],
            ['key' => 'email',             'value' => 'ppt.alfalah29919@gmail.com',      'group' => 'contact'],
            ['key' => 'phone',             'value' => '+62 815-1002-9919',               'group' => 'contact'],
            ['key' => 'address',           'value' => 'Jl. Irigasi Kp. Galang RT.02 RW.05, Desa Jonggol, Kec. Jonggol, Kab. Bogor, Jawa Barat', 'group' => 'contact'],
            ['key' => 'maps_embed_url',    'value' => '',                                'group' => 'contact'],

            // ===================== UMUM =====================
            ['key' => 'site_name',         'value' => 'Al-Falah Boarding School',        'group' => 'general'],
            ['key' => 'institution_name',  'value' => 'Pondok Pesantren Tahfidz Al-Falah', 'group' => 'general'],
            ['key' => 'site_tagline',      'value' => "Mencetak Generasi Qur'ani, Mandiri, dan Berprestasi", 'group' => 'general'],
            ['key' => 'logo',              'value' => '',                                'group' => 'general'],
            ['key' => 'favicon',           'value' => '',                                'group' => 'general'],
            ['key' => 'active_brochure_url','value' => '',                               'group' => 'general'],
            ['key' => 'footer_description','value' => "Mewujudkan generasi qur'ani yang memiliki kecakapan hidup dan kemandirian melalui integrasi ilmu agama dan teknologi pertanian modern.", 'group' => 'general'],

            // ===================== VISI =====================
            ['key' => 'vision_headline',   'value' => "Mencetak Generasi Qur'ani, Mandiri, dan Berprestasi.", 'group' => 'vision'],
            ['key' => 'vision_description','value' => "Sebuah dedikasi untuk membangun harmoni antara kedalaman spiritual, ketajaman intelektual, dan kemandirian praktis bagi masa depan umat.", 'group' => 'vision'],

            // ===================== MISI =====================
            ['key' => 'mission_1_title',   'value' => 'Integritas Iman',        'group' => 'vision'],
            ['key' => 'mission_1_desc',    'value' => 'Membentuk karakter santri yang kokoh dalam akidah dan bertaqwa sepenuhnya kepada Allah SWT sebagai landasan hidup.', 'group' => 'vision'],
            ['key' => 'mission_2_title',   'value' => "Kedekatan Qur'ani",      'group' => 'vision'],
            ['key' => 'mission_2_desc',    'value' => "Menanamkan kecintaan mendalam agar Al-Qur'an senantiasa menjadi pedoman dan inspirasi dalam setiap langkah kehidupan.", 'group' => 'vision'],
            ['key' => 'mission_3_title',   'value' => 'Keseimbangan Ilmu',      'group' => 'vision'],
            ['key' => 'mission_3_desc',    'value' => 'Menguasai integrasi ilmu pengetahuan umum dan teknologi masa depan yang selaras dengan nilai-nilai luhur berakhlakul karimah.', 'group' => 'vision'],
            ['key' => 'mission_4_title',   'value' => 'Kemandirian Pangan',     'group' => 'vision'],
            ['key' => 'mission_4_desc',    'value' => 'Membekali santri dengan kompetensi inovatif untuk mengembangkan sektor agrikultur modern secara mandiri dan produktif.', 'group' => 'vision'],
            ['key' => 'mission_5_title',   'value' => 'Daya Saing Prestasi',    'group' => 'vision'],
            ['key' => 'mission_5_desc',    'value' => 'Menumbuhkan jiwa kompetitif yang sehat demi mencapai prestasi unggul di kancah nasional maupun internasional.', 'group' => 'vision'],

            // ===================== PPDB STEPS =====================
            ['key' => 'ppdb_step_1_title', 'value' => 'Registrasi Online / Offline', 'group' => 'ppdb'],
            ['key' => 'ppdb_step_1_desc',  'value' => 'Mengisi formulir pendaftaran dan melengkapi berkas administrasi (Akta, KK, Raport).', 'group' => 'ppdb'],
            ['key' => 'ppdb_step_2_title', 'value' => 'Tes Seleksi Akademik',   'group' => 'ppdb'],
            ['key' => 'ppdb_step_2_desc',  'value' => 'Pelaksanaan tes masuk sesuai jadwal gelombang yang dipilih.', 'group' => 'ppdb'],
            ['key' => 'ppdb_step_3_title', 'value' => 'Pengumuman & Daftar Ulang','group' => 'ppdb'],
            ['key' => 'ppdb_step_3_desc',  'value' => 'Penetapan kelulusan dan penyelesaian administrasi biaya pendidikan.', 'group' => 'ppdb'],

            // ===================== MEDIA SOSIAL =====================
            ['key' => 'instagram_url',     'value' => '',  'group' => 'social'],
            ['key' => 'youtube_url',       'value' => '',  'group' => 'social'],
            ['key' => 'facebook_url',      'value' => '',  'group' => 'social'],

            // ===================== SEO =====================
            ['key' => 'meta_title',        'value' => 'Pondok Pesantren Tahfidz Al-Falah | SMK Al-Falah Boarding School', 'group' => 'seo'],
            ['key' => 'meta_description',  'value' => "Website resmi Pondok Pesantren Tahfidz Al-Falah dan SMK Al-Falah Boarding School. Pendidikan Tahfidz Al-Qur'an, Boarding School, dan Pertanian Modern.", 'group' => 'seo'],
            ['key' => 'og_image',          'value' => '',  'group' => 'seo'],
            ['key' => 'google_analytics_id','value' => '', 'group' => 'seo'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
