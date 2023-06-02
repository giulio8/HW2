
@extends('layouts.page')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ url('css/home.css') }}">
    <script src="{{ url('js/home.js') }}" defer="True"></script>
@endsection

@section('content')
    <section id="introduction">
        <div class="overlay">
            <div class="app-logo-box">
                <h1 id="title">FlightBook</h1>
            </div>
            <p id="slogan">Il mondo è un libro, e chi non viaggia ne conosce solo una pagina.</p>
        </div>
    </section>
@endsection
