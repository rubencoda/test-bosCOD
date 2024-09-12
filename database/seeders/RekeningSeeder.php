<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RekeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rekenings')->insert(
            [
                'bank_id' => 1,
                'no_rekening' => '11111111'
            ],
            [
                'bank_id' => 2,
                'no_rekening' => '22222222'
            ],
            [
                'bank_id' => 3,
                'no_rekening' => '33333333'
            ],
        );
    }
}
