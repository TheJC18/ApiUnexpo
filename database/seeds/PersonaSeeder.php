<?php

use Illuminate\Database\Seeder;
use App\Model\Persona;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Persona::class, 10)->create();
    }
}
