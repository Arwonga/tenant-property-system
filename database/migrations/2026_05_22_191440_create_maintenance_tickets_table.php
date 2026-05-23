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
        Schema::create('maintenance_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // The Tenant
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete(); // The Unit
            $table->string('category'); // e.g., Plumbing, Electrical, Structural
            $table->string('priority'); // e.g., Low, Medium, High, Emergency
            $table->text('description');
            $table->enum('status', ['Pending', 'Assigned', 'In Progress', 'Resolved'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_tickets');
    }
};
