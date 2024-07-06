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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('position')->nullable();
            $table->bigInteger('label_id')->unsigned()->nullable();
            $table->foreign('label_id')->references('id')->on('menu_labels');
            $table->integer('parent_id')->nullable();
            $table->string('name')->nullable();
            $table->string('name_bn')->nullable();
            $table->string('system_name')->nullable();
            $table->string('route')->nullable();
            $table->string('slug')->nullable();
            $table->string('icon')->nullable();
            $table->integer('status')->comment(' 0 - Inactive & 1 - Active');
            $table->integer('type')->comment('1 - Parent | 2 - Module | 3 - Single');
            $table->integer('order_by')->nullable();
            $table->bigInteger('create_by')->unsigned();
            $table->foreign('create_by')->references('id')->on('users');
            $table->date('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
