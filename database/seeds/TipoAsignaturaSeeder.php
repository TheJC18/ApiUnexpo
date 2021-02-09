<?php

use Illuminate\Database\Seeder;
use App\Model\TipoAsignatura;


class TipoAsignaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = TipoAsignatura::create([
            'nombre'     => 'Departamental',
        ]);


        $admin = TipoAsignatura::create([
            'nombre'     => 'Basica',
        ]);

        $admin = TipoAsignatura::create([
            'nombre'     => 'Servicio',
        ]);

    }
}
