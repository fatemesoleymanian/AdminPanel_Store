<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->change();
            $table->string('model');
            $table->text('description');
            $table->integer('likes');
            $table->text('details');
            $table->text('extraProperty');
            $table->integer('guarantee');
            $table->boolean('visible');
            $table->integer('views');
            $table->integer('sales');
            $table->integer('inventory');
//            $table->foreignId('brand_id')->unsigned()->constrained('brands')
//                ->cascadeOnUpdate()
//                ->cascadeOnDelete();
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
        Schema::dropIfExists('products');
    }
}
