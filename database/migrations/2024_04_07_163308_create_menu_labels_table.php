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
        Schema::create('menu_labels', function (Blueprint $table) {
            $table->id();
            $table->string('label_name')->nullable();
            $table->string('label_name_bn')->nullable();
            $table->integer('status')->comment(' 0 - Inactive , 1 - Active');
            $table->string('type')->nullable();
            $table->date('deleted_at')->nullable();
            $table->bigInteger('create_by')->unsigned();
            $table->foreign('create_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_labels');
    }
};
