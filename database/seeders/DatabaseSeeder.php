<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        //Creating two user roles as admin and client.
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'client']);

        // create default admin use with a role called "admin".
        $admin = User::create([
            'name' => "admin user",
            'email' => "admin@mail.com",
            'email_verified_at' => now(),
            'password' => Hash::make('password')
        ]);

        $admin->assignRole('admin');

    }
}
