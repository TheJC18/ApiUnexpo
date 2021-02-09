<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Model\User;
use App\Model\Persona;


class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //creamos roles
        $admin = Role::create(['name' => 'admin']);
        $root = Role::create(['name' => 'root']);
        $prof = Role::create(['name' => 'prof']);
        $null = Role::create(['name' => 'null']);

        $admin = User::find(1); 
        $admin->assignRole('admin');

        $prof = User::find(2); 
        $prof->assignRole('prof');

        $prof = User::find(3); 
        $prof->assignRole('root');
    
    }
}