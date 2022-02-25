<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EditorialesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('editoriales')->delete();
        
        \DB::table('editoriales')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre' => 'Babel Libros',
                'sede' => 'Colombia',
                'created_at' => '2022-02-25 05:33:21',
                'updated_at' => '2022-02-25 05:33:21',
            ),
            1 => 
            array (
                'id' => 2,
                'nombre' => 'Calixta Editores',
                'sede' => 'Colombia',
                'created_at' => '2022-02-25 05:33:21',
                'updated_at' => '2022-02-25 05:33:21',
            ),
            2 => 
            array (
                'id' => 3,
                'nombre' => 'Editorial Atenea',
                'sede' => 'Colombia',
                'created_at' => '2022-02-25 05:33:21',
                'updated_at' => '2022-02-25 05:33:21',
            ),
            3 => 
            array (
                'id' => 4,
                'nombre' => 'Editorial Monigote',
                'sede' => 'Colombia',
                'created_at' => '2022-02-25 05:33:21',
                'updated_at' => '2022-02-25 05:33:21',
            ),
        ));
        
        
    }
}