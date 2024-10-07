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
        Schema::create('orders_prdoucts', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->foreignId('order_id')->constrained('orders')->casecadeOnDelete()->casecadeOnUpdate();
            $table->foreignId('product_id')->constrained('products')->caecadeOnDelete()->casecadeOnUpdate();
            $table->enum('status',['Active','non-Active'])->default('non-Active');
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
        Schema::dropIfExists('orders_prdoucts');
    }
};
