<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pays', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('user_id')->nullable(); // Puede ser null si no es registrado
            $table->timestamps();  // Agrega created_at y updated_at
        });
    }

    public function down(): void {
        Schema::dropIfExists('pays');
    }
};
