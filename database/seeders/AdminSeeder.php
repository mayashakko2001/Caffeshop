<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Model\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inn=  User::create([
     
            'first_name' =>'John',
           'last_name'=>'Doe',
            'email' =>'johndoe@example.com',
            'password' =>bcrypt('johan$$'),
            'role' =>1,
               
            
        ]);
   
    }
}
