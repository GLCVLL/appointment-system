<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Se l'utente è già autenticato, reindirizza alla dashboard admin
        if (auth()->check()) {
            return redirect()->route('admin.home');
        }

        // Altrimenti reindirizza alla pagina di login
        return redirect()->route('login');
    }
}
