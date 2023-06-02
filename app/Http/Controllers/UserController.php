<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Session;

class UserController extends Controller
{
    public function view($collection_id)
    {
        // Controllo accesso
        if (!Session::get('user_id')) {
            return redirect('login');
        }
        // Controllo permessi
        $collection = Collection::find($collection_id);
        if ($collection->user_id != Session::get('user_id')) {
            return redirect('home');
        }
        // Mostriamo la view
        return view('collection')->with('collection', $collection);
    }

    public function existsField($fieldname)
    {
        $q = request('q');
        $status = 200;
        if ($fieldname !== 'username' && $fieldname !== 'email') {
            $response = ['error' => 'Invalid field name'];
            $status = 400;
        } else {
            $user = User::where($fieldname, $q)->first();
            if ($user) {
                $response['exists'] = true;
            } else {
                $response['exists'] = false;
            }

        }
        return response()->json($response, $status);
    }


}