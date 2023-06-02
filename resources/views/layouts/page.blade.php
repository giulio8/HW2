@extends('layouts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{url('css/navbar.css')}}">
    <link rel="stylesheet" href="{{url('css/footer.css')}}">
    <script src="{{url('js/navbar.js')}}" defer="False"></script>
@endsection

@section('body')
    @include('templates.navbar', ['filename' => basename($_SERVER['PHP_SELF'], ".php")])
    <div class="content">
        @yield('content')
    </div>
    @include('templates.footer')
@endsection