<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

       User::insert([
           [
               'id' => 1,
               'name' => 'SYSTEMA',
               'email' => 'systema@systema.com',
               'password' => bcrypt(167167)
           ],
           [
               'id' => 2,
               'name' => 'COMERCIAL',
               'email' => 'comercial@comercial.com',
               'password' => bcrypt(123456)
           ],
           [
               'id' => 3,
               'name' => 'DIMERSON DANIEL',
               'email' => 'dimerson.daniel@gmail.com',
               'password' => bcrypt(167167)
           ]
       ]);
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
