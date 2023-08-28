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
        Schema::create('merch_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('streamer_id');
            $table->string('name');
            $table->unsignedInteger('amount');
            $table->decimal('unit_price', 10, 2);
            $table->boolean('read_status')->default(false);
            $table->timestamps();

            $table->foreign('streamer_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merch_sales');
    }
};
