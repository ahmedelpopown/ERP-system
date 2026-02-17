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
        
        Schema::table('materials', function (Blueprint $table) {
               $table->enum('unit',['kg','piece','meter']);
               
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
   Schema::table('materials', function (Blueprint $table) {
        // الرجوع للحالة القديمة (string) لو حبيت تعمل rollback
        $table->string('unit')->change();
    });
    }
};
