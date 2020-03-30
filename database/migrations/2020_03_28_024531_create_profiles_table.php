<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('nick_name')->nullable();
            $table->string('avatar')->default('user.jpg');
            $table->json('settings')->nullable();
            $table->text('bio');
            $table->bigInteger('user_id')->unsigned();

            //LOGGER FIELDS
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            //TIMES FIELDS [CREATED_AT, UPDATED_AT, DELETED_AT]
            $table->timestamps();
            $table->softDeletes();

            //INDEXING
            $table->index('first_name');
            $table->index('user_id');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');

            //FOREIGN
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
