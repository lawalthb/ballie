<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('account_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('nature', ['assets', 'liabilities', 'income', 'expenses']);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('parent_id')->references('id')->on('account_groups');
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_groups');
    }
};
