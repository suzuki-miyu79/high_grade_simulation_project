<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            '寿司',
            '焼肉',
            '居酒屋',
            'イタリアン',
            'ラーメン',
        ];

        // ジャンルデータをテーブルに挿入
        foreach ($genres as $genre) {
            DB::table('genres')->insert([
                'genre_name' => $genre,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
