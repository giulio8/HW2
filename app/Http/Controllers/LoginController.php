<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Session;

class LoginController extends Controller
{
    public function login_form()
    {
        if (Session::get('username')) {
            return redirect('home');
        }
        $error = Session::get('error');
        Session::forget('error');
        return view('login')->with('error', $error);
    }

    public function do_login()
    {

        if (Session::get('username')) {
            return redirect('home');
        }

        $error = array();


        // Validate username
        if (empty(trim($_POST["username"]))) {
            $error[] = "Inserisci il tuo username.";
        } else {
            $username = trim($_POST["username"]);
        }

        // Validate password 
        if (empty(trim($_POST["password"]))) {
            $error[] = "Inserisci la tua password.";
        } else {
            $password = trim($_POST["password"]);
        }

        // Check for errors before sending the data to the server
        if (count($error) == 0) {

            // Check if the username exists, if yes then verify the password
            $user = User::where('username', $username)->first();
            if ($user && password_verify($password, $user->password)) {
                // Set session variables
                Session::put('username', $username);
                return redirect("home");
            }
            // If the username doesn't exist or the password is wrong
            $error[] = "Username e/o password errati.";
        }
        return redirect('login')->with('error', $error)->withInput();

    }


}