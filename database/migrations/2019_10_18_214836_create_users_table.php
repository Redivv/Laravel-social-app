<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('api_token', 60)->unique()->nullable()->default(null);
            $table->year('birth_year')->unsigned();
            $table->text('description')->nullable()->default(null);
            $table->unsignedBigInteger('city_id')->nullable()->default(null)->index();
            $table->integer('hidden_status')->default(0);
            $table->string('picture');
            $table->rememberToken();
            $table->timestamps();
            $table->string('status')->default('online');

            $table->foreign("city_id")->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
