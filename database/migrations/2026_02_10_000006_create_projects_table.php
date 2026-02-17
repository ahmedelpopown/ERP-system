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
            $table->string('name');

            $table->longText('details');
            $table->longText('address');
           $table->json('files')->nullable();
            $table->integer('duration');
            $table->date('start_at');
            $table->date('end_at');
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('pricing_id')->constrained('pricing')->cascadeOnDelete();
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
