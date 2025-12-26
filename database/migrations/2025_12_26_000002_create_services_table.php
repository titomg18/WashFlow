<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price_per_kg', 10, 2);
            $table->integer('estimated_hours');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Insert default services directly in migration
        \Illuminate\Support\Facades\DB::table('services')->insert([
            [
                'name' => 'Cuci Reguler',
                'slug' => 'cuci-reguler',
                'price_per_kg' => 10000,
                'estimated_hours' => 24,
                'description' => 'Cuci biasa tanpa setrika',
                'is_active' => true,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cuci + Kering',
                'slug' => 'cuci-kering',
                'price_per_kg' => 15000,
                'estimated_hours' => 12,
                'description' => 'Cuci dan kering',
                'is_active' => true,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cuci + Setrika',
                'slug' => 'cuci-setrika',
                'price_per_kg' => 20000,
                'estimated_hours' => 24,
                'description' => 'Cuci, kering, dan setrika',
                'is_active' => true,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Express 6 Jam',
                'slug' => 'express-6-jam',
                'price_per_kg' => 25000,
                'estimated_hours' => 6,
                'description' => 'Selesai dalam 6 jam',
                'is_active' => true,
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};