<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // WF-20231226-001
            
            // Relationships
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // kasir
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            
            // Order details
            $table->decimal('weight', 8, 2); // in kg
            $table->decimal('price_per_kg', 10, 2);
            $table->decimal('total_price', 15, 2);
            
            // Status workflow
            $table->enum('status', [
                'pending',      // Baru masuk
                'cuci',         // Proses cuci
                'kering',       // Proses kering
                'setrika',      // Proses setrika
                'selesai',      // Selesai proses
                'diambil',      // Sudah diambil pelanggan
                'batal'         // Dibatalkan
            ])->default('pending');
            
            // Timing
            $table->timestamp('estimated_finish_at')->nullable();
            $table->timestamp('actual_finish_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            
            // Payment - CASH ONLY
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->string('payment_method')->default('cash'); // selalu cash
            
            // Notes
            $table->text('special_notes')->nullable(); // catatan pelanggan
            $table->text('internal_notes')->nullable(); // catatan internal
            
            $table->timestamps();
            $table->index('status');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};