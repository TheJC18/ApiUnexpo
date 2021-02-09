<?php

use Illuminate\Database\Seeder;
use App\Model\Profesor;


class ProfesorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Profesor::class, 8)->create();
    }
}
