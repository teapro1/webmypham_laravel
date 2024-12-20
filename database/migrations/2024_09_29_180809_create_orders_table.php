<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('cart_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('province');
            $table->string('district');
            $table->string('ward');
            $table->decimal('total', 10, 2);
            $table->string('payment_method');
            $table->timestamps();

            // Thêm khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
