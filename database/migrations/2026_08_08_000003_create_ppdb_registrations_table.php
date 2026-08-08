<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppdb_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ppdb_wave_id')->constrained()->cascadeOnDelete();
            $table->string('registration_number')->unique();
            $table->uuid('public_token')->unique();
            $table->string('status', 20)->default('new')->index();

            $table->string('full_name')->index();
            $table->string('gender', 10);
            $table->text('nik');
            $table->string('nik_hash', 64);
            $table->string('nik_last4', 4);
            $table->text('nisn')->nullable();
            $table->string('birth_place');
            $table->date('birth_date');
            $table->text('address');
            $table->string('province_code', 2);
            $table->string('province_name');
            $table->string('district_city_code', 2);
            $table->string('district_city_name');
            $table->string('subdistrict_code', 3);
            $table->string('subdistrict_name');
            $table->string('village_code', 3);
            $table->string('village_name');
            $table->string('postal_code', 5)->nullable();
            $table->string('student_phone', 20)->nullable();
            $table->string('photo_path');

            $table->string('school_name');
            $table->string('npsn', 8)->nullable();

            $table->string('father_name');
            $table->string('father_education')->nullable();
            $table->string('father_job')->nullable();
            $table->string('mother_name');
            $table->string('mother_education')->nullable();
            $table->string('mother_job')->nullable();
            $table->string('primary_contact_relation', 20);
            $table->string('primary_contact_phone', 20);
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship')->nullable();
            $table->string('guardian_education')->nullable();
            $table->string('guardian_job')->nullable();

            $table->timestamp('accuracy_accepted_at');
            $table->timestamp('privacy_accepted_at');
            $table->timestamp('submitted_at');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['ppdb_period_id', 'nik_hash']);
            $table->index(['ppdb_period_id', 'ppdb_wave_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_registrations');
    }
};
