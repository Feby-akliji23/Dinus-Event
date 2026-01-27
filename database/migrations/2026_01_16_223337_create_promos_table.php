<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode')->nullable()->unique();
            $table->string('tipe');
            $table->decimal('nilai', 10, 2);
            $table->string('scope');
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ticket_type_id')->nullable()->constrained('ticket_types')->nullOnDelete();
            $table->foreignId('tiket_id')->nullable()->constrained('tikets')->nullOnDelete();
            $table->dateTime('mulai')->nullable();
            $table->dateTime('akhir')->nullable();
            $table->decimal('min_transaksi', 10, 2)->nullable();
            $table->unsignedInteger('kuota')->nullable();
            $table->unsignedInteger('digunakan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
