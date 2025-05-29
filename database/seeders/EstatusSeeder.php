<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Estatus;
use Illuminate\Database\Seeder;

class EstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $valores = ['Pendiente', 'Proceso', 'Terminado'];

        foreach ($valores as $item) {

            Estatus::create(
                [
                    'nombre' => $item
                ]
            );
            
        }
        
    }
}
