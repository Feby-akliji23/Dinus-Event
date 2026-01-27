<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId("event_id")->constrained()->onDelete('cascade');
            $table->foreignId('payment_type_id')->nullable()->constrained('payment_types')->nullOnDelete();
            $table->foreignId('payment_status_id')->nullable()->constrained('payment_statuses')->nullOnDelete();
            $table->foreignId('promo_id')->nullable()->constrained('promos')->nullOnDelete();
            $table->decimal('subtotal_harga', 10, 2)->default(0);
            $table->decimal('diskon', 10, 2)->default(0);
            $table->dateTime("order_date");
            $table->decimal('total_harga', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
