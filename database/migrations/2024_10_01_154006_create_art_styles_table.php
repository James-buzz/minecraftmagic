<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('art_styles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('art_type_id')->constrained()->cascadeOnDelete();
            $table->string('name', 32);
            $table->string('description')->nullable();
            $table->string('prompt', 1000)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('art_styles');
    }
};
