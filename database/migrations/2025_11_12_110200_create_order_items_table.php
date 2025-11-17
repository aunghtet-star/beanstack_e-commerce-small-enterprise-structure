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
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignUlid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->ulid('product_id');
            $table->text('name_snapshot');
            $table->decimal('price_snapshot', 8, 2);
            $table->integer('quantity');
            $table->timestampTz('created_at')->useCurrent();

            $table->index('order_id', 'idx_orderitems_order');
        });

        DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_quantity_check CHECK (quantity > 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
