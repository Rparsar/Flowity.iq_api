<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::create([
            'name' => 'Admin Flowity',
            'email' => 'admin@flowity.iq',
            'password' => bcrypt('password'),
            'rol' => 'admin',
        ]);

        User::create([
            'name' => 'Usuario Flowity',
            'email' => 'user@flowity.iq',
            'password' => bcrypt('password'),
            'rol' => 'user',
        ]);

        $this->call([
            ProveedorSeeder::class,
            ProductoSeeder::class,
            ServicioSeeder::class,
            ReservaSeeder::class,
            EncargoSeeder::class,
            VentaSeeder::class,
        ]);
    }
}
