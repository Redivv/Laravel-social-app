<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name');
            $table->text('name_slug');
            $table->unsignedBigInteger('author_id')->index();
            $table->unsignedBigInteger('category_id')->index();
            
            $table->longText('description');
            $table->longText('thumbnail');
            $table->timestamps();

            $table->foreign("author_id")->references('id')->on('users')->onDelete('cascade');
            $table->foreign("category_id")->references('id')->on('blog_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
}
