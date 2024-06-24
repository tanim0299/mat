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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('store_name')->nullable();
            $table->string('store_name_bn')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('email')->nullable();
            $table->text('adress')->nullable();
            $table->string('logo')->nullable();
            $table->integer('status')->comment('0-Inactive, 1-Active')->nullable();
            $table->date('deleted_at')->nullable();
            $table->bigInteger('create_by')->nullable()->unsigned();
            $table->foreign('create_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
