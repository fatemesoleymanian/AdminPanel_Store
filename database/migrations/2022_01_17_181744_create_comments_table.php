<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
//            $table->foreignId('product_id')->unsigned()->constrained('products')
//                ->cascadeOnDelete()
//                ->cascadeOnUpdate();
//            $table->foreignId('user_id')->unsigned()->constrained('store_users')
//                ->cascadeOnDelete()
//                ->cascadeOnUpdate();
            $table->text('content');
            $table->integer('likes');
            $table->integer('unlikes');
            //vase inke bbini user ye commento like krde ya ne ke ejaze bedi dobare bkne ye table bzn ke
            //commentlikes ro negahdare ba user hash
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
        Schema::dropIfExists('comments');
    }
}
