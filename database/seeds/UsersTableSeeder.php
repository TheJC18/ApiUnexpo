<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Model\User;
use App\Model\Persona;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Persona::create([
            'dni'      => '27024967',
            'nombre'     => 'Jolber',
            'segundo_nombre'     => 'Ramon',
            'apellido'     => 'Chirinos',
            'segundo_apellido'     => 'Colina',
        ]);
        $admin = User::create([
            'email'     => 'Jolber@gmail.com',
            'password'     => bcrypt('1234567'),
            'persona_id'     => '1',
            'departamento'     => 'Informatica',
            'is_verified' => '1',
        ]);


        $prof = Persona::create([
            'dni'      => '1234567',
            'nombre'     => 'Daniel',
            'segundo_nombre'     => 'Alejandro',
            'apellido'     => 'Pereira',
            'segundo_apellido'     => 'Sierra',
        ]);
        $prof = User::create([
            'email'     => 'Daniel@gmail.com',
            'password'     => bcrypt('1234567'),
            'persona_id'     => '2',
            'is_verified' => '1',
        ]);



        $root = Persona::create([
            'dni'      => '78787878',
            'nombre'     => 'Root',
            'segundo_nombre'     => 'root',
            'apellido'     => 'root',
            'segundo_apellido'     => 'root',
        ]);
        $root = User::create([
            'email'     => 'Root@gmail.com',
            'password'     => bcrypt('1234567'),
            'persona_id'     => '2',
            'departamento'     => 'Root',
            'is_verified' => '1',
        ]);
    }
}
