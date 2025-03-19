<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Eliminar registros previos para evitar duplicados
        DB::table('oauth_personal_access_clients')->delete();
        DB::table('oauth_clients')->delete();
        
        // Reiniciar los IDs autoincrementales
        DB::statement('ALTER TABLE oauth_personal_access_clients AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE oauth_clients AUTO_INCREMENT = 1');
        
        // Insertar datos de oauth_clients
        DB::table('oauth_clients')->insert([
            [
                'id'                     => 1,
                'user_id'                => null,
                'name'                   => 'Backend Personal Access Client',
                'secret'                 => 'x5GpQwcc3RoaUNpsa244wtJHMoRiSLHdV6BWy4rb',
                'provider'               => null,
                'redirect'               => 'http://localhost',
                'personal_access_client' => 1,
                'password_client'        => 0,
                'revoked'                => 0,
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
            [
                'id'                     => 2,
                'user_id'                => null,
                'name'                   => 'Backend Password Grant Client',
                'secret'                 => 'Ayen0NgyB5bQHrZjFMBgFc7VTbfhulGnx9m4SDaZ',
                'provider'               => 'users',
                'redirect'               => 'http://localhost',
                'personal_access_client' => 0,
                'password_client'        => 1,
                'revoked'                => 0,
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
        ]);

// Insertar datos de oauth_personal_access_clients
        DB::table('oauth_personal_access_clients')->insert([
            'id'         => 1,
            'client_id'  => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
