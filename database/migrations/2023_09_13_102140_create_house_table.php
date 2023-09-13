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
        Schema::create('house', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('numberOfRooms');
            $table->decimal('size', 5, 2)->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 5, 2);
            $table->string('address');
            $table->string('phoneNumber');
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house');
    }
};
