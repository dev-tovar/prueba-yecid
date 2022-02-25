<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AutoresTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('autores')->delete();
        
        \DB::table('autores')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre' => 'Gabriel',
                'apellidos' => 'García Márquez',
                'created_at' => '2022-02-25 05:33:21',
                'updated_at' => '2022-02-25 05:33:21',
            ),
            1 => 
            array (
                'id' => 2,
                'nombre' => 'Rafael',
                'apellidos' => 'Pombo',
                'created_at' => '2022-02-25 05:33:21',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'nombre' => 'José',
                'apellidos' => 'Eustasio Rivera',
                'created_at' => '2022-02-25 05:33:21',
                'updated_at' => '2022-02-25 05:33:21',
            ),
        ));
        
        
    }
}