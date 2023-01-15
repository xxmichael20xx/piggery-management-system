<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ( User::where( 'email', 'admin@admin.com' )->count() < 1 ) {
            $admin = new User;
            $admin->name = 'Admin';
            $admin->email = 'admin@admin.com';
            $admin->password = bcrypt( 'admin123' );
            $admin->role = 'admin';
            $admin->save();
        }
    }
}
