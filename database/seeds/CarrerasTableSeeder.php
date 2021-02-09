<?php

use Illuminate\Database\Seeder;
use App\Model\Carrera;

class CarrerasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Carrera::create([
            'nombre'     => 'Mecanica',
        ]);

        $admin = Carrera::create([
            'nombre'     => 'Electronica',
        ]);

        $admin = Carrera::create([
            'nombre'     => 'Electrica',
        ]);

        $admin = Carrera::create([
            'nombre'     => 'Quimica',
        ]);

        $admin = Carrera::create([
            'nombre'     => 'Metalurgica',
        ]);

        $admin = Carrera::create([
            'nombre'     => 'Industrial',
        ]);

    }
}
