<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Model\Group;
class Groups extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $in=  Group::create([
     
            'name_Group' =>'Privet',
           'description'=>'A',
           
            
        ]);
        
    }
}
