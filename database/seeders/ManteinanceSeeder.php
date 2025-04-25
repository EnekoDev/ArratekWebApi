<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManteinanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('manteinances')->insert([
            ['name' => 'Web', 'created_at' => now()],
            ['name' => 'Sistemas', 'created_at' => now()],
            ['name' => 'Completo', 'created_at' => now()],
            ['name' => 'Sin mantenimiento', 'created_at' => now()],
        ]);
    }
}
