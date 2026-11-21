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
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
        $table->string('title', 150);
        $table->text('description');
        $table->string('category', 50);
        $table->unsignedBigInteger('budget');
        $table->dateTime('bid_deadline');
        $table->enum('status', ['open', 'awarded', 'closed'])->default('open');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
