@extends('layouts.page')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ url('css/offerte.css') }}">
    <link rel="stylesheet" href="{{ url('css/biglietto.css') }}">
    <link rel="stylesheet" href="{{ url('css/bootstrap-collapse.min.css') }}">
    <script src="{{ url('js/offerte.js') }}" defer="True"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ url('js/biglietto.js') }}" defer="True"></script>
@endsection

@section('content')
    <header id="introduction">
        <div id="overlay">
            <h1 id="title">Offerte di voli
                @if (isset($luogo) && $luogo !== null)
                    per {{ $luogo }}
                @endif
            </h1>
            <p>
                Da qui potrai cercare e selezionare offerte interessanti per volare
                @if (isset($luogo) && $luogo !== null)
                    verso {{ $luogo }} che abbiamo trovato per te!
                @else
                    dove vuoi.
                @endif
            </p>
        </div>
    </header>
    <section>
        <form id="search-form">
            @csrf
            <div id="origin-input" class="app-input-box">
                <label for="origin">Partenza da</label>
                <input type="text" name="origin" id="origin" placeholder="Luogo di partenza">
            </div>
            <div id="destination-input" class="app-input-box">
                <label for="destination">Destinazione</label>
                <input type="text" name="destination" id="ritorno"
                    @if (isset($luogo) && $luogo !== null) value="{{ $luogo }}" @endif
                    placeholder="Luogo di destinazione">
            </div>
            <div id="departureDate-input" class="app-input-box">
                <label for="departureDate">Data di partenza</label>
                <input type="date" name="departureDate" id="departureDate">
            </div>
            <div id="returnDate-input" class="app-input-box">
                <label for="returnDate">Data di ritorno</label>
                <input type="date" name="returnDate" id="returnDate">
            </div>
            <button id="search-button" class="app-button">Cerca</button>
        </form>
        @include('templates.loader')
        @include('templates.message-display')
    </section>
    <button id="back-button" class="app-dismiss-button hidden">Torna indietro</button>
    <section>
        <div id="result" class="hidden">
            <div class="result-content">
            </div>
        </div>
    </section>
    <section>
        <div id="modal-booking" class="app-modal hidden">
            <div class="modal-content">
                <form id="booking-form" name="booking">
                    <div id="luggage-input" class="app-input-box">
                        <label for="bagaglio">Bagagli</label>
                        <input type="number" max="3" value="1" name="bagaglio" id="bagaglio" placeholder="Numero di bagagli">
                    </div>
                    <div id="booking-error" class="hidden">
                    </div>
                    <button id="confirm-button" class="app-button">Prenota</button>
                    <button id="close-button" class="app-dismiss-button">Annulla</button>
                </form>
            </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
@endsection
