<?php

use Illuminate\Database\Seeder;

class ACPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(\App\User::all() as $u)
        {
            $u->delete();
        }

        \App\User::create([
            'name'          =>  'Root',
            'surname'       =>  'Fornace',
            'email'         =>  'root@fornace.io',
            'password'      => \Illuminate\Support\Facades\Hash::make('furnax3b'),

            'role'          => 'root',
            'activated'          => true
        ]);

        \App\User::create([
            'name'          =>  'Admin',
            'surname'       =>  '',
            'email'         =>  'admin@fornace.io',
            'password'      => \Illuminate\Support\Facades\Hash::make('admin'),

            'role'          => 'admin',
            'activated'          => true
        ]);
    }
}
