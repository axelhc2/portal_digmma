<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_laravel', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('domain');
            $table->foreignUuid('license_id')->constrained('licenses')->onDelete('cascade');
            $table->string('site_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_laravel');
    }
}; 