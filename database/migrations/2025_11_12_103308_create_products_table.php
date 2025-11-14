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
        Schema::create('products', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->unsignedInteger('store_id')->default(1);
            $table->text('name');
            $table->text('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->jsonb('meta')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->nullable();

            $table->index('store_id', 'idx_products_store');
        });

        DB::statement(<<<'SQL'
            CREATE INDEX idx_products_name_fts
            ON products USING GIN (to_tsvector('simple', name))
        SQL);

        DB::statement('ALTER TABLE products ADD CONSTRAINT products_stock_check CHECK (stock >= 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
