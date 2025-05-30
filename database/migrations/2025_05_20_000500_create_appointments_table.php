<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('hairdresser_id');
            $table->unsignedBigInteger('service_id');

            $table->date('date');      // Y-M-G formatında tarih
            $table->time('time');      // Saat:dakika:saniye formatında zaman
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');

            $table->timestamps();

            // Eğer foreign key ilişkisi istersen, şöyle ekleyebilirsin:
            // $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            // $table->foreign('hairdresser_id')->references('id')->on('hairdressers')->onDelete('cascade');
            // $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
