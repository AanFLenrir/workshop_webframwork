<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('midtrans_order_id')->unique();
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('va_number')->nullable();
            $table->string('qr_code_url')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'settlement', 'expire', 'cancel'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};