@extends('layouts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ url('css/signup.css') }}">
    <script src="{{ url('js/signup.js') }}" defer="True"></script>
@endsection

@section('body')
    <script>
        let errors = new Set();
        @if (isset($error))
            @foreach ($error as $err)
                errors.add("{{ $err }}");
            @endforeach
        @endif
    </script>
    <div class="content">
        <section class="app-flex-centered">
            <div id="form-box">
                <div id="logo-container">
                    <div class="app-logo-box">
                        <h1 id="title">FlightBook</h1>
                    </div>
                </div>
                <h1>Registrati</h1>
                @include('templates.message-display')
                <form id="signup-form" name='signup' method='post' enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="input-container">
                        <div id="firstname-input" class="app-input-box">
                            <label for='firstname'>Nome</label>
                            <input type='text' name='firstname' value='{{ old('firstname') }}'>
                        </div>
                        <div id="lastname-input" class="app-input-box">
                            <label for='lastname'>Cognome</label>
                            <input type='text' name='lastname' value='{{ old('lastname') }}'>
                        </div>
                        <div id="birthdate-input" class="app-input-box">
                            <label for='birthdate'>Data di nascita</label>
                            <input type='date' name='birthdate' value='{{ old('birthdate') }}'>
                        </div>
                        <div id="email-input" class="app-input-box">
                            <label for='email'>Email</label>
                            <input type='email' name='email' value='{{ old('email') }}'>
                        </div>
                        <div id="username-input" class="app-input-box">
                            <label for='username'>Nome utente</label>
                            <input type='text' name='username' value='{{ old('username') }}'>

                        </div>
                        <div>
                            <div id="password-input" class="app-input-box">
                                <label for='password'>Password</label>
                                <input type='password' name='password' value='{{ old('password') }}'>
                            </div>
                            <div id="confirm-password-input" class="app-input-box">
                                <label for='confirm-password'>Conferma Password</label>
                                <input type='password' name='confirm-password' value='{{ old('confirm-password') }}'>
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="submit" class="app-button">Registrati</button>
                </form>
            </div>
            <div class="signin">Hai un account? <a href="{{ url('login') }}">Accedi</a>
        </section>

    </div>
@endsection
