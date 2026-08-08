<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'master_area_province_202602260015.sql',
            'master_area_district_city_202602260015.sql',
            'master_area_subdistrict_202602260015.sql',
            'master_area_village_subdistrict_202602260015.sql',
        ] as $file) {
            DB::unprepared(file_get_contents(database_path('data/areas/'.$file)));
        }
    }

    public function down(): void
    {
        DB::table('master_area_village_subdistrict')->delete();
        DB::table('master_area_subdistrict')->delete();
        DB::table('master_area_district_city')->delete();
        DB::table('master_area_province')->delete();
    }
};
