<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services_data = [
            [
                'category_id' => 1,
                'name' => 'Taglio e styling',
                'duration' => '01:00:00',
                'price' => 15.00,
                'is_available' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Colorazione capelli',
                'duration' => '02:00:00',
                'price' => 20.00,
                'is_available' => true,
            ],
        ];

        foreach ($services_data as $data) {
            $service = new Service();
            $service->fill($data);
            $service->save();
        }
    }
}
