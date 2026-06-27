<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('payment_gateway_id')->unique()->comment('External payment gateway reference');
            $table->enum('status', ['pending', 'successful', 'failed'])->default('pending');
            $table->enum('method', ['credit_card', 'paypal', 'stripe', 'bank_transfer'])->default('credit_card');
            $table->json('gateway_response')->nullable()->comment('Raw response payload from the payment gateway');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
