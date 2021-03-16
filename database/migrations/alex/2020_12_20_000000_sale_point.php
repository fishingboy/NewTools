<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SalePoint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_points', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('展示中心名稱');
            $table->string('address', 300)->comment('地址');
            $table->char('enable', 1)->comment('是否啟用');
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
        Schema::dropIfExists('sale_points');
    }
}
