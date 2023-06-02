<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Console\Migrations\RollbackCommand;
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

    public function signup_form()
    {
        if (Session::get('username')) {
            return redirect('home');
        }
        $error = Session::get('error');
        Session::forget('error');
        return view('signup')->with('error', $error);
    }

    public function do_signup()
    {
        if (Session::get('username')) {
            return redirect('home');
        }
        $error = array();
        // Verifica l'esistenza di dati POST
        if ((!empty(request("username"))) && (!empty(request("password"))) && (!empty(request("confirm-password"))) && (!empty(request("firstname"))) && (!empty(request("lastname"))) && (!empty(request("birthdate"))) && (!empty(request("email")))) {

            $username = request("username");
            $password = request("password");
            $confirm_password = request("confirm-password");
            $firstname = request("firstname");
            $lastname = request("lastname");
            $birthdate = request("birthdate");
            $email = request("email");

            # USERNAME
            // Check if the username is valid
            if (!preg_match('/^[a-zA-Z0-9_]{1,15}$/', $username)) {
                $error[] = "Username non valido";
            } else if (User::where('username', $username)->first()) {
                $error[] = "Username già utilizzato";
            }

            if (strlen($password) < 8) {
                $error[] = "Lunghezza password insufficiente";
            }

            if (strcmp($password, $confirm_password) != 0) {
                $error[] = "Le password non coincidono";
            }

            # EMAIL
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error[] = "Email non valida";
            } else if (User::where('email', $email)->first()) {
                $error[] = "Email già utilizzata";
            }

            # inserimento nel database
            if (count($error) == 0) {
                try {
                    $user = new User();
                    $user->username = $username;
                    $user->firstname = $firstname;
                    $user->lastname = $lastname;
                    $user->password = password_hash($user->password, PASSWORD_BCRYPT);
                    $user->birthdate = $birthdate;
                    $user->email = $email;
                    $user->save();
                } catch (\Exception $e) {
                    $error[] = "Errore di connessione al Database";
                }

            }

        } else {
            $error[] = "Riempi tutti i campi";
        }

        if (count($error) == 0) {
            Session::put('username', $user->username);
            return redirect('home');
        }

        return redirect('signup')->with('error', $error)->withInput();
    }

    public function logout()
    {
        // Elimina dati di sessione
        Session::flush();
        return redirect('login');
    }


}