<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = config('data.categories_list');

        foreach ($categories as $category) {
            $new_category = new Category();

            $new_category->name = $category;

            $new_category->save();
        }
    }
}
