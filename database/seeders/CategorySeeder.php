<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Kemeja Pria',
            'Celana Pria',
            'Celana Wanita',
        ];

        foreach ($categories as $category) {
            Categorie::updateOrCreate([
                'slug' => Str::slug($category),
            ], [
                'name' => $category,
            ]);
        }
    }
}
