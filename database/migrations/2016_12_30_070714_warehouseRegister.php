<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WarehouseRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouseRegister', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('warehouse_id');
            $table->string('material'); // Внешний ключ на таблицу с мат. ценностями
            $table->boolean('coming');
            $table->float('quantity');
            // Внешний ключ на запись регистра
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
        Schema::dropIfExists('warehouseRegister');
    }
}
