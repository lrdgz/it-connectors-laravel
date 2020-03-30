<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Models\User;

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
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_logged_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->string('password');
            $table->boolean('verified_email')->default(false);
            $table->boolean('verified_mobile')->default(false);
            $table->string('email_token')->unique()->nullable();
            $table->string('mobile_token')->unique()->nullable();
            $table->string('pin', 6)->nullable();
            $table->enum('access', User::USERS_ROLES);
            $table->boolean('active')->default(false);

            //LOGGER FIELDS
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            //TIMES FIELDS [CREATED_AT, UPDATED_AT, DELETED_AT]
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            //INDEXING
            $table->index('email');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');

            //FOREIGN
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
        Schema::dropIfExists('users');
    }
}
