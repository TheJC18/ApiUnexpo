<?php

use Illuminate\Database\Seeder;
use App\Model\Asignatura;

class AsignaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Asignatura::class, 5)->create();
    }
}
