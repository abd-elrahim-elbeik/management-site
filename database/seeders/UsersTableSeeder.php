<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user= \App\Models\User::create([
            'first_name' => 'super',
            'last_name' => 'admin',
            'email' => 'super_admin@app.cpm',
            'password' => bcrypt('password') ,

        ]);

        $role= Role::where('name','=','super_admin')->first();

        $user->attachRole('super_admin');



    }

}
