<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ledger_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name');
            $table->string('code')->unique();
            $table->unsignedBigInteger('account_group_id');
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->enum('balance_type', ['dr', 'cr'])->default('dr');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('account_group_id')->references('id')->on('account_groups');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ledger_accounts');
    }
};
