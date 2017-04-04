<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 16.03.2017
 * Time: 16:04
 */
?>
@extends('control.layout.main')

@section('title','Главная')

@section('content')
    @extends('control.layout.menu')
    <h1>Hello, {{Auth::user()->name}}</h1>
@endsection
