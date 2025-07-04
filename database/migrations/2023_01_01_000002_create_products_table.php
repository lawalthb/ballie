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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');

            // Basic Information
            $table->enum('type', ['item', 'service'])->default('item');
            $table->string('name');
            $table->string('sku')->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('hsn_code')->nullable();

            // Pricing
            $table->decimal('purchase_rate', 15, 2)->default(0);
            $table->decimal('sales_rate', 15, 2)->default(0);
            $table->decimal('mrp', 15, 2)->nullable();
            $table->decimal('minimum_selling_price', 15, 2)->nullable();

            // Units
            $table->string('primary_unit')->default('Nos');
            $table->string('secondary_unit')->nullable();

            // Stock Management
            $table->decimal('opening_stock', 15, 2)->default(0);
            $table->decimal('current_stock', 15, 2)->default(0);
            $table->decimal('available_stock', 15, 2)->default(0);
            $table->decimal('allocated_stock', 15, 2)->default(0);
            $table->decimal('reorder_level', 15, 2)->nullable();
            $table->decimal('maximum_stock', 15, 2)->nullable();

            // Taxation
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->string('tax_type')->nullable();
            $table->boolean('tax_inclusive')->default(false);

            // Physical Properties
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->decimal('weight', 10, 3)->nullable();
            $table->string('weight_unit')->nullable();
            $table->string('barcode')->nullable();
            $table->string('image_path')->nullable();

            // Options
            $table->boolean('maintain_stock')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_saleable')->default(true);
            $table->boolean('is_purchasable')->default(true);
            $table->boolean('track_serial_numbers')->default(false);
            $table->boolean('track_batch_numbers')->default(false);
            $table->boolean('perishable')->default(false);

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('tenant_id');
            $table->index('type');
            $table->index('category');
            $table->index('is_active');
            $table->index('is_saleable');
            $table->index('is_purchasable');
            $table->index(['tenant_id', 'type']);
            $table->index(['tenant_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};