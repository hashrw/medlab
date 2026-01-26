<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $email = 'admin@medlab.local'; 
            $password = '4dmIn135!';      

            // Importante: tu User tiene guarded(['email']), así que NO uses create() con email.
            $user = User::query()->where('email', $email)->first();

            if (!$user) {
                $user = new User();
                $user->email = $email; // asignación directa -> NO mass assignment
            }

            $user->name = 'Administrador';
            $user->apellidos = $user->apellidos ?? 'Sistema';
            $user->telefono = $user->telefono ?? null;
            $user->tipo_usuario_id = 3;
            $user->password = Hash::make($password);
            $user->email_verified_at = now();
            $user->save();

            Admin::query()->updateOrCreate(
                ['user_id' => $user->id],
                ['rol' => 'superadmin']
            );
        });

        //
    }
}
