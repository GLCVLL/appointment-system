<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {

        $categories = Category::select('id', 'name')->get();

        $services = Service::select('id', 'category_id', 'name', 'duration', 'is_available')->get();

        $bookingServices = [
            'categories' => $categories,
            'services' => $services,
        ];
        // Return the response with the services
        return response($bookingServices, 200);
    }
}
