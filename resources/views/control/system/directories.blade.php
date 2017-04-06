<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 04.04.2017
 * Time: 10:58
 */
?>
@extends('control.layout.main')

@section('title','Системные справочники')

@section ('content')
    @extends('control.layout.menu')
    <h1>Системные справочники</h1>
    <ul>
        <li><a href="{{url('/ctrl/sys/attributes')}}">Атрибуты</a></li>
        <li><a href="{{url('/ctrl/sys/units')}}">Единицы измерения</a></li>
    </ul>
@endsection

