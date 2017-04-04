<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 04.04.2017
 * Time: 13:13
 */
?>

@extends('control.layout.main')

@section('title','Атрибуты')

@section('content')
    @extends('control.layout.menu')

    <table class="table">
        <tr>
            <th>Наименование</th>
            <th>Тип</th>
            <th>Единица измерения</th>
            <th>Возможные значения</th>
            <th>Фиксированные значения</th>
            <th>Действия</th>
        </tr>
        @foreach($properties as $property)
            <tr>
                <td>{{$property->name}}</td>
                <td>
                    @if($property->typeName)
                        {{$property->typeName}}
                    @else
                        --
                    @endif
                </td>
                <td>{{$property->unitName}}</td>
                <td>
                    @foreach($property->possibleValues as $possibleValue)
                        {{$possibleValue->value}},
                    @endforeach

                </td>
                <td>@if($property->isFixedValue())
                        Да
                    @else
                        Нет
                    @endif
                </td>
                <td>
                    <img src="{{asset("imgs/icons/shock/edit.png")}}"
                         onclick="openPopupWindow('{{url('/ctrl/sys/attribute/'.$property->id)}}','Редактирование атрибута',600,400)"
                         class="pointer"
                         width="20"
                         height="20"
                    >
                    <img src="{{asset("imgs/icons/shock/trash_can.png")}}"
                         class="pointer"
                         width="20"
                         height="20"
                    >
                </td>
            </tr>


        @endforeach
    </table>
@endsection

