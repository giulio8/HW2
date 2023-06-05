@extends('layouts.page')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ url('css/prenotazioni.css') }}">
    <link rel="stylesheet" href="{{ url('css/biglietto.css') }}">
    <link rel="stylesheet" href="{{ url('css/modal-confirm.css') }}">
    <link rel="stylesheet" href="{{ url('css/bootstrap-collapse.min.css') }}">
    <script src="{{ url('js/prenotazioni.js') }}" defer="True"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ url('js/biglietto.js') }}" defer="True"></script>
    <script src="{{ url('js/modal-confirm.js') }}" defer="True"></script>
@endsection

@section('content')
    <header id="introduction">
        <div id="overlay">
            <h1 id="title">Voli prenotati</h1>
            <p>
                In questa sezione trovi tutti i voli che hai prenotato con noi. Puoi vedere
                i dettagli del volo, e se vuoi puoi anche cancellare la prenotazione.
            </p>
        </div>
    </header>
    <section>
        <div id="result" class="hidden">
            <div class="result-content">
            </div>
        </div>
    </section>
    <section id="post">
        @include('templates.loader')
        @include('templates.message-display')
        @include('templates.modal-confirm')
    </section>
    <section>
        <div id="modal" class="modal hidden">
            <div class="modal-content">
                <span class="close">&times;</span>
            </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
@endsection
