<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('package_name');
            $table->string('image');
            $table->integer('package_category_id')->nullable();
           // $table->integer('sub_category_id')->nullable();
            $table->float('package_price')->default(0);
            $table->float('discount_price')->default(0);
            $table->longText('short_description')->nullable();
            $table->longText('long_description')->nullable();
            
            $table->integer('currency_id')->nullable();
            $table->integer('status')->default(1);
            $table->integer('is_deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
};