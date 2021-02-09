<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		$this->call(UsersTableSeeder::class);
	    $this->call(CarrerasTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);    
        $this->call(TipoAsignaturaSeeder::class); 
        $this->call(AsignaturaSeeder::class);   
        $this->call(AlumnoSeeder::class);
        $this->call(ProfesorSeeder::class);       
        $this->call(PersonaSeeder::class);     
	} 
}
