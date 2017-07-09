<?php

use Illuminate\Database\Seeder;
use App\User;
class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            
        */
        $user = new User();
        $user->name = "Agri Kridanto";
        $user->email = "agri.kridanto@gmail.com";
        $user->password = bcrypt('rahasia');
        $user->save();

    }
}
