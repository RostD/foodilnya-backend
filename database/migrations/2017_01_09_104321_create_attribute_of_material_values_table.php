<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->boolean('fixed_value')->default(false);
            $table->integer('unit_id')->nullable();
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
