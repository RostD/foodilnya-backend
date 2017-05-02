<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 02.05.2017
 * Time: 11:49
 */
?>


@extends('control.layout.main')

@section('title','Остатки основного склада')

@section('content')
    @extends('control.layout.menu')

    <button type="submit" class="btn btn-primary btn-sm pointer"
            style="margin:10px 10px 10px 10px"
            onclick="window.location.reload()">Обновить
    </button>
    <table class="table">
        <tr>
            <th>Наименование</th>
            <th>Тип</th>
            <th>Остаток</th>
            <th>Единица измерения</th>
        </tr>
        @foreach($strings as $string)
            <tr style="{{$string->material->trashed() ? 'text-decoration:line-through;background-color:#FBEFEF;':''}}">
                <td>{{$string->material->name}}</td>
                <td>{{$string->material->typeName}}</td>
                <td style="@if($string->quantity < 0) color:#d9534f; @endif">{{$string->quantity}}</td>
                <td>{{$string->material->unitName}}</td>
            </tr>
        @endforeach
    </table>
    <div id="error"></div>
@endsection

@section('script')
@endsection

