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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->float('amount', 6, 2);
            $table->float('ves_exchange', 6, 2);
            $table->string('name');
            $table->string('rate');
            $table->string('rate_type');
            $table->enum('currency', ['VES', 'USD'])->default('VES');
            $table->string('description')->nullable();
            $table->float('remaining_amount', 6, 2)->default(0);
            $table->float('amount_returned', 6, 2)->default(0);
            $table->date('estimated_refund_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
