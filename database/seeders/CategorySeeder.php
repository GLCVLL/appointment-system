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
        $names = [
            'Taglio e styling',
            'Colorazione capelli',
            'Trattamenti capelli',
            'Servizi di estetica',
            'Manicure e pedicure'
        ];

        foreach ($names as $name) {
            $category = new Category();

            $category->name = $name;

            $category->save();
        }
    }
}
