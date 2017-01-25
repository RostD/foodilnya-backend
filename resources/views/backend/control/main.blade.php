@extends('backend.control.includes.master')

@section('title')Главная@endsection

@section('content')
    @include('backend.control.includes.top')
    <h1>Панель управления</h1>
    <p>Добро пожаловать в панель управления!</p>
    @include('backend.control.includes.bot')
@endsection