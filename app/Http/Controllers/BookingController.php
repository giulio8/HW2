<?php

namespace App\Http\Controllers;

use App\Models\Segment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use App\Models\Flight;

class BookingController extends Controller
{
    public function index()
    {
        // Controllo accesso
        if (!Session::get('username')) {
            return redirect('login');
        }
        // Mostriamo la view
        return view('prenotazioni');
    }

    public function ticket()
    {
        // Controllo accesso
        if (!Session::get('username')) {
            return redirect('login');
        }
        // Mostriamo la view
        return view('templates.biglietto')->with('volo', json_decode(request('flight')));
    }

    public function getPrenotazioni()
    {
        // Controllo accesso
        if (!$username = Session::get('username')) {
            return redirect('login');
        }

        $prenotazioni = Flight::where('utente', $username)->with('andata', 'ritorno')->get();
        $p_arr = [];

        // Adattamento al formato di Amadeus
        foreach ($prenotazioni as $prenotazione) {
            $p = $prenotazione->toArray();
            $a = $p['andata'];
            unset($p['andata']);
            $p['andata']['tratte'] = $a;
            $r = $p['ritorno'];
            unset($p['ritorno']);
            $p['ritorno']['tratte'] = $r;
            $p_arr[] = $p;
        }

        return response()->json($p_arr);

    }

    public function bookFlight()
    {
        // Controllo accesso
        if (!$username = Session::get('username')) {
            http_response_code(401);
            exit;
        }
        $error = array();

        // Controllo che l'utente sia maggiorenne
        $user = User::where('username', $username)->first();
        if ($user->birthdate <= date('Y-m-d', strtotime('-18 years'))) {
            $code = 400;
            $error[] = "Utente minorenne";
        }

        // Check if the request fields are filled
        if (empty(request('flight'))) {
            $code = 400;
            $error[] = "Dati insufficienti";
        }


        if (count($error) === 0) {
            // Set the variables for the query
            $flight = json_decode(request('flight'), true);
            $data_prenotazione = date("Y-m-d");
            $origine = $flight["andata"]["tratte"][0]["origine"]["iataCode"];
            $destinazione = $flight["andata"]["tratte"][count($flight["andata"]["tratte"]) - 1]["destinazione"]["iataCode"];
            $prezzo = $flight["prezzo"];
            $valuta = $flight["valuta"];
            $data_partenza = $flight["andata"]["tratte"][0]["data_partenza"];
            $data_arrivo = $flight["andata"]["tratte"][count($flight["andata"]["tratte"]) - 1]["data_arrivo"];
            $compagnia = $flight["compagnia"];


            DB::beginTransaction();
            try {
                $prenotazione = new Flight;
                $prenotazione->data_prenotazione = $data_prenotazione;
                $prenotazione->origine = $origine;
                $prenotazione->destinazione = $destinazione;
                $prenotazione->prezzo = $prezzo;
                $prenotazione->valuta = $valuta;
                $prenotazione->data_partenza = $data_partenza;
                $prenotazione->data_arrivo = $data_arrivo;
                $prenotazione->compagnia = $compagnia;
                $prenotazione->utente = $username;

                $tratte = array();

                foreach ($flight["andata"]["tratte"] as $i => $t) {
                    $tratta = new Segment;
                    $tratta->origine = $t["origine"]["iataCode"];
                    $tratta->destinazione = $t["destinazione"]["iataCode"];
                    $tratta->data_partenza = $t["origine"]["at"];
                    $tratta->data_arrivo = $t["destinazione"]["at"];
                    $tratta->progressivo = $i;
                    $tratta->direzione = "andata";

                    $tratte[] = $tratta;

                }
                if (isset($flight["ritorno"])) {
                    foreach ($flight["ritorno"]["tratte"] as $i => $t) {
                        $tratta = new Segment;
                        $tratta->origine = $t["origine"]["iataCode"];
                        $tratta->destinazione = $t["destinazione"]["iataCode"];
                        $tratta->data_partenza = $t["origine"]["at"];
                        $tratta->data_arrivo = $t["destinazione"]["at"];
                        $tratta->progressivo = $i;
                        $tratta->direzione = "ritorno";

                        $tratte[] = $tratta;
                    }
                }
                $prenotazione->save();
                $prenotazione->tratte()->saveMany($tratte);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $error[] = "Errore nella query: " . $e->getMessage();
            }
        }

        if (count($error) > 0) {
            isset($code) ?: $code = 500;
            return response()->json($error, $code);
        } else {
            return response()->json(array("message" => "Prenotazione effettuata con successo!"));
        }

    }

    public function deleteFlight()
    {
        // Controllo accesso
        if (!$username = Session::get('username')) {
            http_response_code(401);
            exit;
        }
        $error = array();

        // Check if the request fields are filled
        if (empty(request('id'))) {
            $code = 400;
            $error[] = "Dati insufficienti";
        }

        // Check if the user is the owner of the booking
        $prenotazione = Flight::find(request('id'));
        if ($prenotazione) {
            if ($prenotazione->utente != $username) {
                $code = 403;
                $error[] = "Non sei autorizzato a cancellare questa prenotazione";
            } else {
                // l'ON DELETE CASCADE si occupa di cancellare anche le tratte
                if ($prenotazione->delete()) {
                    $code = 200;
                    $message = "Prenotazione cancellata con successo";
                } else {
                    $code = 500;
                    $error[] = "Errore nella cancellazione";
                }
            }
        } else {
            $code = 404;
            $error[] = "Prenotazione non trovata";
        }

        if (count($error) > 0) {
            return response()->json($error, $code);
        } else {
            return response()->json(array("message" => $message));
        }
    }
}