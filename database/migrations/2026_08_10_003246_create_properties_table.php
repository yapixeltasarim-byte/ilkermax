<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('listing_type', ['sale', 'rent']);
            $table->enum('status', ['draft', 'pending', 'published', 'sold', 'rented'])->default('draft');
            $table->decimal('price', 15, 2);
            $table->string('currency', 3)->default('TRY');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->unsignedInteger('area_gross')->nullable();
            $table->unsignedInteger('area_net')->nullable();
            $table->string('rooms')->nullable();
            $table->unsignedTinyInteger('bathrooms')->nullable();
            $table->integer('floor')->nullable();
            $table->unsignedInteger('total_floors')->nullable();
            $table->unsignedInteger('building_age')->nullable();
            $table->string('heating_type')->nullable();
            $table->boolean('furnished')->default(false);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
