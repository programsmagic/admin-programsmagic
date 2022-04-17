<?php

use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AddDefaultUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      User::create([
            'name'=>'Admin',
            'email'=>'admin@yopmail.com',
            'password'=>Hash::make('password'),
            'type'=>'admin',
        ]);
      User::create([
            'name'=>'User',
            'email'=>'user@yopmail.com',
            'password'=>Hash::make('password'),
            'type'=>'user',
        ]);
      User::create([
            'name'=>'Author',
            'email'=>'author@yopmail.com',
            'password'=>Hash::make('password'),
            'type'=>'author',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
