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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            // This single line creates 'model_type' and 'model_id' columns automatically!
            $table->morphs('model'); 
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable(); // e.g., 'image/jpeg', 'application/pdf'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
