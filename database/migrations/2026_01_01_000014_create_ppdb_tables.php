<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_periods', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year'); // e.g. 2026/2027
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('ppdb_waves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppdb_period_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g. Gelombang I
            $table->date('registration_start');
            $table->date('registration_end');
            $table->date('test_date')->nullable();
            $table->date('announcement_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('ppdb_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppdb_period_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('ppdb_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppdb_period_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g. Biaya Pendaftaran
            $table->bigInteger('amount');
            $table->text('notes')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_fees');
        Schema::dropIfExists('ppdb_requirements');
        Schema::dropIfExists('ppdb_waves');
        Schema::dropIfExists('ppdb_periods');
    }
};
