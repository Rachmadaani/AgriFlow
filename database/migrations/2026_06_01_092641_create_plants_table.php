<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // cabai, timun, dll
            $table->decimal('default_price', 12, 2)->nullable(); // harga default per kg
            $table->date('planting_date')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // untuk multi-user nanti
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
