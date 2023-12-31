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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('slug')->unique()->index();
            $table->boolean('active')->default(false);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('price');
            $table->integer('image_id')->nullable();
            $table->integer('priority')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
