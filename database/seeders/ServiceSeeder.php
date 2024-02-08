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
        $services_data = config('data.services_list');

        foreach ($services_data as $data) {
            $service = new Service();
            $service->fill($data);
            $service->save();
        }
    }
}
