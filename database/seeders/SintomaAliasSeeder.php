<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SintomaAliasSeeder extends Seeder
{
    public function run(): void
    {
        // Limpieza en dev (como es fresh, esto es solo por seguridad)
        DB::table('sintoma_aliases')->truncate();

        $sintomas = DB::table('sintomas')->select('id', 'organo_id', 'sintoma')->get();

        foreach ($sintomas as $s) {
            $base = $this->slug($s->sintoma);

            // Alias canónico estable: o{organo_id}_{sintoma}
            $alias = 'o' . $s->organo_id . '_' . $base;

            DB::table('sintoma_aliases')->insert([
                'sintoma_id' => $s->id,
                'alias' => $alias,
                'tipo' => 'canonical',
                'nota' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function slug(string $txt): string
    {
        $txt = trim($txt);
        $txt = preg_replace('/\s+/u', ' ', $txt);
        // Str::ascii quita tildes/diacríticos, luego slug
        return Str::slug(Str::ascii($txt), '_');
    }
}
