<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Request;
use Session;

class DestinationController extends Controller
{

    public function index()
    {
        if (!$username = Session::get('username')) {
            return redirect('login');
        }
        $title = request('title');
        $description = request('description');
        $image = request('image');
        return view('destinazione')->with('title', $title)->with('description', $description)->with('image', $image);
    }

    public function getDestinazioni()
    {
        if (!$username = Session::get('username')) {
            http_response_code(401);
            exit;
        }
        $destinazioni = Destination::where('utente', $username)->get();

        $array = array();
        $array['count'] = count($destinazioni);
        $array['data'] = $destinazioni;

        return response()->json($array);
    }

    public function caricaDestinazione()
    {
        /* if (!$userId = Session::get('username')) {
            http_response_code(401);
            exit;
        } */$userId = "pippo1";

        $error = array();

        // Check if the request fields are filled
        if (empty(request('titolo')) || empty(request('descrizione'))) {
            $error[] = "Dati insufficienti";
        } else {
            // Set the variables for the query
            $username = $userId;
            $titolo = request('titolo');
            $descrizione = request('descrizione');
            $file = Request::file('image');
            //dd($file);

            // Check if the tuple username-titolo already exists
            if (Destination::where('utente', $username)->where('titolo', $titolo)->count() > 0) {
                $error[] = "Hai già inserito una destinazione con questo titolo";
            }

            // Save the image in the server filesystem
            $img_location = public_path('media');

            if (count($error) === 0) {
                if ($file->getSize() > 0) {
                    $type = exif_imagetype($file->path());
                    $allowedExt = array(IMAGETYPE_PNG => 'png', IMAGETYPE_JPEG => 'jpg');
                    if (isset($allowedExt[$type])) {
                        if ($file->getError() === UPLOAD_ERR_OK) {
                            if ($file->getSize() < 4000000) {
                                $fileNameNew = uniqid('', false) . "." . $allowedExt[$type];
                                try {
                                    $img_location = $file->move($img_location, $fileNameNew);
                                } catch (\Exception $e) {
                                    $error[] = "Errore nel caricamento dell'immagine sul server" + $e->getMessage();
                                }
                                if ($img_location === false) {
                                    $error[] = "Errore nel caricamento dell'immagine sul server";
                                }
                            } else {
                                $error[] = "L'immagine non deve avere dimensioni maggiori di 4MB";
                            }
                        } else {
                            $error[] = "Errore nel carimento del file";
                        }
                    } else {
                        $error[] = "I formati consentiti sono .png, .jpeg e .jpg";
                    }
                } else {
                    $error[] = "Non hai caricato nessuna immagine";
                }
            }

        }



        if (count($error) === 0) {
            // Save the tuple in the database
            $destinazione = new Destination();
            $destinazione->utente = $username;
            $destinazione->titolo = $titolo;
            $destinazione->descrizione = $descrizione;
            $destinazione->immagine = $fileNameNew;

            if ($destinazione->save()) {
                $response = array("message" => "Destinazione inserita con successo");
                return response()->json($response);
            } else {
                $error[] = "Errore di connessione al Database";
            }
        }

        return response()->json($error, 400);

    }

    public function eliminaDestinazione()
    {
        /* if (!$username = Session::get('username')) {
            http_response_code(401);
            exit;
        } */$username = "pippo1";

        $error = array();

        // Check if the request fields are filled
        if (empty(request('titolo'))) {
            $error[] = "Dati insufficienti";
        }

        // Set the variables for the query
        $titolo = request('titolo');


        if (count($error) === 0) {
            // Check if the user is the owner of the destination and retrieve the image name
            $destinazione = Destination::where('utente', $username)->where('titolo', $titolo)->first();
            if ($destinazione) {
                // Delete the image from the server filesystem
                $img_location = public_path('media/');
                unlink($img_location . $destinazione->immagine);

                // Delete the destination from the database
                if (!$destinazione->delete()) {
                    $status = 500;
                    $error[] = "Errore di connessione al Database";
                }

            } else {
                $error[] = "La destinazione non esiste o non hai i permessi per eliminarla";
            }
        }

        if (count($error) > 0) {
            $response = array("message" => $error);
            isset($status) ? $status : $status = 400;
            return response()->json($response, $status);
        } else {
            $response = array("message" => "Immagine eliminata con successo");
            echo json_encode($response);
        }
    }
}