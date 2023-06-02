<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class HomeController extends Controller
{
    public function index()
    {
        // Controllo accesso
        if (!Session::get('username')) {
            return redirect('login');
        }
        // Mostriamo la view
        return view('home');
    }
}
