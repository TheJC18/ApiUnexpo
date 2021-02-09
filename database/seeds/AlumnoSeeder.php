<?php

use Illuminate\Database\Seeder;
use App\Model\Alumno;


class AlumnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Alumno::class, 8)->create();
    }
}
