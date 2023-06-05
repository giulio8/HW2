@extends('layouts.page')

@section('head')
    @parent
    <link rel="stylesheet" href="{{ url('css/galleria.css') }}">
    <link rel="stylesheet" href="{{ url('css/modal-confirm.css') }}">
    <script src="{{ url('js/galleria.js') }}" defer="True"></script>
    <script src="{{ url('js/loader.js') }}" defer="True"></script>
    <script src="{{ url('js/modal-confirm.js') }}" defer="True"></script>
@endsection

@section('content')
    <header id="introduction">
        <div id="overlay">
            <h1 id="title">Galleria delle destinazioni</h1>
            <p>
                Qui potrai condividere con il mondo le tue avventure, e viaggiare con
                la mente in mondi nuovi dando un'occhiata alle foto postate
                dagli altri.
            </p>
        </div>
    </header>
    <section>
        <div class="sub-header">
            <h2 class="subtitle">Galleria</h2>
            <button class="app-button" id="aggiungi">Carica nuova destinazione</button>
        </div>
        <p id="gallery">
        </p>
    </section>

    @include('templates.loader')
    @include('templates.message-display')
    @include('templates.modal-confirm')
    </section>
    <section>
        <div id="modal-add-dest" class="app-modal hidden">
            <div class="modal-content">
                <form id="post-image-form" name="postImage">
                    <div id="img-input" class="app-input-box">
                        <label for="img">Scegli una foto da postare!</label>
                        <input type="file" name="image" accept="image/png, image/jpeg">
                    </div>
                    <div id="title-input" class="app-input-box">
                        <label for="titolo">Titolo</label>
                        <input type="text" name="titolo" id="titolo" placeholder="Luogo di destinazione">
                    </div>
                    <div id="description-input" class="app-input-box">
                        <label for="description">Inserisci una descrizione</label>
                        <input type="text" name="descrizione">
                    </div>
                    <button id="post-button" class="app-button">Posta</button>
                    <button id="close-button" class="app-dismiss-button">Annulla</button>
                </form>
            </div>
    </section>
@endsection
