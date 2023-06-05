<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Http\Request;
use Session;

class FlightController extends Controller
{
    private $amadeus_access_token;

    public function index($luogo = null)
    {
        // Controllo accesso
        if (!Session::get('username')) {
            return redirect('login');
        }
        // Mostriamo la view
        return view('offerte')->with('luogo', $luogo);
    }

    private function refreshAccessToken()
    {
        if (isset($this->amadeus_access_token)) {
            return $this->amadeus_access_token;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, env('AMADEUS_HOSTNAME') . "/v1/security/oauth2/token");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "grant_type=client_credentials&client_id=" . env('AMADEUS_CLIENT_ID') . "&client_secret=" . env('AMADEUS_CLIENT_SECRET'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        if ($response['state'] == "approved") {
            return $response['access_token'];
        }
        return null;
    }

    private function requestCoordinates($location)
    {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, env('O_W_HOSTNAME') . "/geo/1.0/direct?q=" . urlencode($location) . "&limit=5&appid=" . env('O_W_API_KEY'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);
        try {
            if ($response) {
                $responseArray = json_decode($response, true); //
                if (count($responseArray) > 0) {
                    return $responseArray[0];
                } else {
                    return null;
                }
            } else {
                throw new \Exception("Errore nella richiesta delle coordinate");
            }
        } catch (\Exception $e) {
            throw new \Exception("Errore nella richiesta delle coordinate");
        }
        /*$responseArray = array(
            array(
                "name" => "Roma",
                "lat" => 41.8919,
                "lon" => 12.5113,
                "country" => "IT",
                "state" => "Lazio"
            ),
            array(
                "name" => "Roma",
                "lat" => 41.8919,
                "lon" => 12.5113,
                "country" => "IT",
                "state" => "Lazio"
            ),
            array(
                "name" => "Roma",
                "lat" => 41.8919,
                "lon" => 12.5113,
                "country" => "IT",
                "state" => "Lazio"
            ),
            array(
                "name" => "Roma",
                "lat" => 41.8919,
                "lon" => 12.5113,
                "country" => "IT",
                "state" => "Lazio"
            ),
            array(
                "name" => "Roma",
                "lat" => 41.8919,
                "lon" => 12.5113,
                "country" => "IT",
                "state" => "Lazio"
            )
        );*/
    }

    private function setHeaders($access_token)
    {
        return array(
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json"
        );
    }

    private function airportRequest($location)
    {
        try {
            $coordinates = $this->requestCoordinates($location);
            $lat = $coordinates["lat"];
            $lon = $coordinates["lon"];
        } catch (\Exception $e) {
            throw new \Exception("Errore nella richiesta delle coordinate");
        }
        //

        $this->amadeus_access_token = $this->refreshAccessToken();
        if ($this->amadeus_access_token == null) {
            throw new \Exception("Errore nella richiesta del token");
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, env('AMADEUS_HOSTNAME') . "/v1/reference-data/locations/airports?latitude=" . $lat . "&longitude=" . $lon);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->setHeaders($this->amadeus_access_token));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);
        try { // In alcuni casi la risposta non ha tutti i campi e solleva un errore
            if ($response && isset(json_decode($response, true)['data'])) {
                $response = json_decode($response, true);
                if ($response['meta']['count'] == 0) {
                    return null;
                }
                return $response['data'][0];
            } else {
                throw new \Exception("Errore nella richiesta degli aeroporti");
            }
        } catch (\Exception $e) {
            throw new \Exception("Errore nella richiesta degli aeroporti");
        }
    }

    private function flightRequest($origin_code, $destination_code, $departureDate, $returnDate)
    {
        $this->amadeus_access_token = $this->refreshAccessToken();
        if ($this->amadeus_access_token == null) {
            throw new \Exception("Errore di autenticazione");
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, env('AMADEUS_HOSTNAME') . "/v2/shopping/flight-offers?originLocationCode=" . $origin_code . "&destinationLocationCode=" . $destination_code . "&departureDate=" . $departureDate . "&returnDate=" . $returnDate . "&adults=1&max=3");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->setHeaders($this->amadeus_access_token));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);
        if ($response == false) {
            throw new \Exception("Errore nella richiesta dei voli da Amadeus");
        }
        $response = json_decode($response, true);
        return $response;
    }


    public function getFlightOffers()
    {
        // Controllo accesso
        /* if (!$username = Session::get('username')) {
            http_response_code(401);
            exit;
        } */

        $error = array();

        if (empty(request('origin')) || empty(request('destination')) || empty(request('departureDate')) || empty(request('returnDate'))) {
            $error[] = "Dati insufficienti";

        }

        // Set the variables for the query
        $origin = request('origin');
        $destination = request('destination');
        $departureDate = request('departureDate');
        $returnDate = request('returnDate');

        // Get the IATA codes for the origin and destination
        try {
            $origin_airport = $this->airportRequest($origin);
            $destination_airport = $this->airportRequest($destination);
            if ($origin_airport == null) {
                $error[] = "Aeroporto di partenza non trovato";
            } else if ($destination_airport == null) {
                $error[] = "Aeroporto di arrivo non trovato";
            } else {
                $origin_code = $this->airportRequest($origin)["iataCode"];
                $destination_code = $this->airportRequest($destination)["iataCode"];
            }
        } catch (\Exception $e) {
            $code = 500;
            $error[] = $e->getMessage();
        }

        $city_map = array();

        if (count($error) === 0) {
            $data = $this->flightRequest($origin_code, $destination_code, $departureDate, $returnDate);
            $flights = $data['data'];
            //$flights = json_decode(file_get_contents("flights.json"), true)['data'];
            if ($flights === null) {
                $code = 500;
                $error[] = "Errore nella richiesta dei voli";
            } else if (count($flights) === 0) {
                $error[] = "Nessun volo trovato";
            } else {
                $dict = $data['dictionaries'];
                //$dict = json_decode(file_get_contents("flights.json"), true)['dictionaries'];
            }
        }

        if (count($error) === 0) {
            $flights_resp = array();
            foreach ($flights as $flight) {
                $comp = $dict['carriers'][$flight['itineraries'][0]['segments'][0]['carrierCode']];
                $flight_resp = new Flight;
                $flight_resp->id = $flight['id'];
                $flight_resp->prezzo = $flight['price']['total'];
                $flight_resp->valuta = $flight['price']['currency'];
                $flight_resp->solaAndata = $flight['oneWay'];
                $flight_resp->postiDisponibili = $flight["numberOfBookableSeats"];
                $flight_resp->compagnia = $comp;
                //["id" => $flight['id'], "prezzo" => $flight['price']['total'], "valuta" => $flight['price']['currency'], "solaAndata" => $flight['oneWay'], "postiDisponibili" => $flight["numberOfBookableSeats"], "compagnia" => $comp];
                foreach ($flight['itineraries'] as $idx => &$itinerary) {
                    $segments = $itinerary['segments'];
                    $itinerary_resp = array();
                    foreach ($segments as $key => $segment) {
                        $segments_resp[$key]['origine'] = $segment['departure'];
                        $segments_resp[$key]['data_partenza'] = $segment['departure']['at'];
                        if (!isset($city_map[$segment['departure']['iataCode']])) {
                            $city_map[$segment['departure']['iataCode']] = Airport::where('iata_code', $segment['departure']['iataCode'])->first();
                        }
                        $segments_resp[$key]['origine']['city'] = $city_map[$segment['departure']['iataCode']]['city'];
                        $segments_resp[$key]['origine']['country'] = $city_map[$segment['departure']['iataCode']]['country'];
                        $segments_resp[$key]['destinazione'] = $segment['arrival'];
                        $segments_resp[$key]['data_arrivo'] = $segment['arrival']['at'];
                        if (!isset($city_map[$segment['arrival']['iataCode']])) {
                            $city_map[$segment['arrival']['iataCode']] = Airport::where('iata_code', $segment['arrival']['iataCode'])->first();
                        }
                        $segments_resp[$key]['destinazione']['city'] = $city_map[$segment['arrival']['iataCode']]['city'];
                        $segments_resp[$key]['destinazione']['country'] = $city_map[$segment['arrival']['iataCode']]['country'];
                    }
                    $itinerary_resp['tratte'] = $segments_resp;
                    if ($idx === 0)
                        $flight_resp->andata = $itinerary_resp;
                    else
                        $flight_resp->ritorno = $itinerary_resp;
                }
                $flights_resp[] = $flight_resp;
            }

            return response()->json($flights_resp);

        } else {
            isset($code) ?: $code = 400;
            return response()->json($error, $code);
        }
    }
}