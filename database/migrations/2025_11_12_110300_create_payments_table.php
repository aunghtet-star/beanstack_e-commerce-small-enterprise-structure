<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('order_id')->constrained('orders');
            $table->string('provider', 20);
            $table->string('provider_id', 80)->nullable();
            $table->integer('amount');
            $table->char('currency', 3)->default('USD');
            $table->string('status', 20);
            $table->timestampsTz();
        });

        DB::statement("ALTER TABLE payments ADD CONSTRAINT payments_status_check CHECK (status IN ('authorized','captured','refunded','failed'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
