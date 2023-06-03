@extends('layouts.app')

@section('head')
    @parent
    <link rel="stylesheet" href="{{url('css/navbar.css')}}">
    <link rel="stylesheet" href="{{url('css/footer.css')}}">
    <link rel="stylesheet" href="{{url('css/loader.css')}}">
    <script src="{{url('js/navbar.js')}}" defer="False"></script>
    <script src="{{url('js/loader.js')}}" defer="False"></script>
@endsection

@section('body')
    @include('templates.navbar', ['filename' => $view_name])
    <div class="content">
        @yield('content')
    </div>
    @include('templates.footer')
@endsection