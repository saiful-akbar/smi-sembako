<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            "BUMBU",
            "TOPING",
            "MAKANAN POKOK",
            "ALAT KEBERSIHAN",
            "SAOS",
            "TEPUNG",
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
