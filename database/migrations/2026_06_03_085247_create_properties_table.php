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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('address');
            $table->string('geolocation');
            $table->string('rstype');
            $table->enum('transaction_type', ['Bán', 'Cho thuê'])->default('Cho thuê');
            $table->double('price')->default(0);
            $table->string('formated_price')->default('Liên hệ');
            $table->double('total_area')->default(45.0);
            $table->integer('bed')->default(1);
            $table->integer('bath')->default(1);
            $table->integer('floors')->default(1);
            $table->string('direction')->nullable();
            $table->string('feature_img');
            $table->json('images')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
