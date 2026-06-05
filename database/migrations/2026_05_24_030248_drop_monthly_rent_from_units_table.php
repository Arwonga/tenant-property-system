<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            // This cleanly removes the ghost column
            $table->dropColumn('monthly_rent');
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->decimal('monthly_rent', 10, 2)->default(0);
        });
    }
};