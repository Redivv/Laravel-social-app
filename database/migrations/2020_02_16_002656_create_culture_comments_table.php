<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCultureCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('culture_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('message')->nullable()->default(null);
            $table->unsignedBigInteger('author_id')->index();
            $table->unsignedBigInteger('item_id')->index();
            $table->unsignedBigInteger('parent_id')->nullable()->default(null)->index();
            $table->longText('tagged_users')->nullable()->default(null);
            $table->timestamps();
            
            $table->foreign("author_id")->references('id')->on('users')->onDelete('cascade');
            $table->foreign("item_id")->references('id')->on('culture_items')->onDelete('cascade');
            $table->foreign("parent_id")->references('id')->on('culture_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('culture_comments');
    }
}
