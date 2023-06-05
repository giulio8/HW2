@extends('layouts.page')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ url('css/profilo.css') }}">
    <script src="{{ url('js/profilo.js') }}" defer="True"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ url('js/modal-confirm.js') }}" defer="True"></script>
@endsection

@section('content')
    <header id="introduction">
        <div id="overlay">
            <h1 id="title">Profilo</h1>
            <p>
                Dettagli del tuo profilo.
            </p>
        </div>
    </header>
    <div class="sub-header">
        <h2 class="subtitle">Modifica i tuoi dati</h2>
        <button class="app-dismiss-button" id="logout">Disconnettiti</button>
    </div>
    <section class="app-flex-centered">
        <form name='user' id="user-form" method='post' enctype="multipart/form-data" autocomplete="off">
            <?php $fields = [
                'username' => 'Nome utente', 'firstname' => 'Nome', 'lastname' => 'Cognome', 'birthdate' => 'Data di nascita', 'email' => 'Email'
                ]; ?>
            @foreach ($fields as $field => $label)
                <div id="{{ $field }}-input" class="input">
                    <label for='{{ $field }}'>{{ $label }}</label>
                    @if ($field !== 'username')
                        <div class="buttons-wrapper">
                            <div class="buttons hidden" data-id="{{ $field }}">
                                <img class="icon edit" src="../assets/edit-info.png">
                                <img class="icon cancel hidden" src="../assets/cancel.png">
                                <img class="icon save hidden" src="../assets/save.png">
                            </div>
                        </div>
                    @endif
                    <input type=<?php if ($field === 'birthdate') echo "'date'"; else if ($field == 'email') echo "'email'"; else echo "'text'";?> name='{{ $field }}' disabled>
                </div>
            @endforeach
                <button id="submit-button" class="hidden" type="submit">Salva</button>
        </form>
        @include('templates.message-display')
    </section>
    <section id="post">
        @include('templates.loader')
    </section>
    <section>
        @include('templates.modal-confirm')
    </section>
@endsection
