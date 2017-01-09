<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeOfMaterialValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_of_material_values', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('material_type_id')->nullable();
            $table->integer('value_type_id');
            $table->string('default_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_of_material_values');
    }
}
