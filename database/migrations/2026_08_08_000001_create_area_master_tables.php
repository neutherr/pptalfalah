<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_area_province', function (Blueprint $table) {
            $table->string('code', 2)->primary();
            $table->string('name');
        });

        Schema::create('master_area_district_city', function (Blueprint $table) {
            $table->string('province_code', 2);
            $table->string('code', 2);
            $table->string('name');
            $table->primary(['province_code', 'code']);
            $table->index('province_code');
        });

        Schema::create('master_area_subdistrict', function (Blueprint $table) {
            $table->string('province_code', 2);
            $table->string('district_city_code', 2);
            $table->string('code', 3);
            $table->string('name');
            $table->primary(['province_code', 'district_city_code', 'code'], 'area_subdistrict_primary');
            $table->index(['province_code', 'district_city_code'], 'area_subdistrict_parent_index');
        });

        Schema::create('master_area_village_subdistrict', function (Blueprint $table) {
            $table->string('province_code', 2);
            $table->string('district_city_code', 2);
            $table->string('subdistrict_code', 3);
            $table->string('code', 3);
            $table->string('name');
            $table->primary(
                ['province_code', 'district_city_code', 'subdistrict_code', 'code'],
                'area_village_primary'
            );
            $table->index(
                ['province_code', 'district_city_code', 'subdistrict_code'],
                'area_village_parent_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_area_village_subdistrict');
        Schema::dropIfExists('master_area_subdistrict');
        Schema::dropIfExists('master_area_district_city');
        Schema::dropIfExists('master_area_province');
    }
};
