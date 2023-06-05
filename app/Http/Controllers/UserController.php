<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Session;

class UserController extends Controller
{
    public function index()
    {
        // Controllo accesso
        if (!Session::get('username')) {
            return redirect('login');
        }
        // Mostriamo la view
        return view('profilo');
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

    public function getUserInfo() 
    {
         if (!$username = Session::get('username')) {
            http_response_code(401);
            exit;
        }

        $error = array();
        $user = User::where('username', $username)->first();
        if (!$user) {
            $code = 404;
            $error[] = 'Utente non trovato';
        }

        if (count($error) > 0) {
            isset($code) ?: $code = 500;
            return response()->json($error, $code);
        } else {
            return response()->json($user);
        }
    }

    public function updateUserInfo()
    {
        if (!$username = Session::get('username')) {
            http_response_code(401);
            exit;
        }

        $error = array();

        if (!(empty(request("firstname")) && empty(request("lastname")) && empty(request("birthdate")) && empty(request("email")))) {
            $user = User::where('username', $username)->first();
            if (!empty(request("firstname"))) {
                $user->firstname = request("firstname");
            }
            if (!empty(request("lastname"))) {
                $user->lastname = request("lastname");
            }
            if (!empty(request("birthdate"))) {
                $user->birthdate = request("birthdate");
            }
            if (!empty(request("email"))) {
                $user->email = request("email");
            }
            if (!$user->save()) {
                $error[] = 'Errore durante il salvataggio';
                $code = 500;
            }
        } else {
            $error[] = 'Nessun campo da aggiornare';
            $code = 400;
        }

        if (count($error) > 0) {
            isset($code) ?: $code = 500;
            return response()->json($error, $code);
        } else {
            return response()->json(["message" => "Utente aggiornato correttamente"]);
        }
    }


}