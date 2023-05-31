<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function view($collection_id)
    {
        // Controllo accesso
        if(!Session::get('user_id'))
        {
            return redirect('login');
        }
        // Controllo permessi
        $collection = Collection::find($collection_id);
        if($collection->user_id != Session::get('user_id'))
        {
            return redirect('home');
        }
        // Mostriamo la view
        return view('collection')->with('collection', $collection);
    }
}
