<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('message')->nullable()->default(null);
            $table->unsignedBigInteger('author_id')->index();
            $table->unsignedBigInteger('post_id')->index();
            $table->unsignedBigInteger('parent_id')->nullable()->default(null)->index();
            $table->longText('tagged_users')->nullable()->default(null);
            $table->timestamps();
            
            $table->foreign("author_id")->references('id')->on('users')->onDelete('cascade');
            $table->foreign("post_id")->references('id')->on('blog_posts')->onDelete('cascade');
            $table->foreign("parent_id")->references('id')->on('blog_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_comments');
    }
}
