@extends('layouts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ url('css/login.css') }}">
    <script src="{{ url('js/login.js') }}" defer="True"></script>
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
                <h1>Accedi</h1>
                @include('templates.message-display')
                <form id="login-form" name='login' method='post' enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="input-container">
                        <div id="username-input" class="input">
                            <label for='username'>Nome utente</label>
                            <input type='text' name='username' value='{{ old('username') }}'>
                        </div>
                        <div id="password-input" class="input">
                            <label for='password'>Password</label>
                            <input type='password' name='password' value='{{ old('password') }}'>
                        </div>
                    </div>
                    <button type="submit" id="submit" class="app-button">Accedi</button>
                </form>
            </div>
            <div class="signup">Non hai un account? <a href="{{ url('signup') }}">Registrati</a>
        </section>

    </div>
@endsection
