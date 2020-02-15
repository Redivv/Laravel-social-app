<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCultureItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('culture_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('name_slug')->index();
            $table->longText('attributes');

            $table->longText('pictures')->nullable()->default(null);
            $table->string('description');
            
            $table->longText('review')->nullable()->default(null);

            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('category_id')->index();

            $table->foreign("user_id")->references('id')->on('users')->onDelete('cascade');
            $table->foreign("category_id")->references('id')->on('culture_categories')->onDelete('cascade');
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
        Schema::dropIfExists('culture_items');
    }
}
