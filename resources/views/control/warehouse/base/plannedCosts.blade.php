<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 03.05.2017
 * Time: 13:26
 */
?>


@extends('control.layout.main')

@section('title','Заказы на комплектацию')

@section('content')
    @extends('control.layout.menu')

    <form class="form-inline">
        <button type="submit" class="btn btn-primary btn-sm pointer"
                style="margin:10px 0px 10px 10px;"
                onclick="window.location.reload()">Обновить
        </button>
        <div class="form-group mx-sm-3">
            <input class="form-control form-control-sm"
                   type="date"
                   value="{{app('request')->input('dayEnd')}}"
                   id="date"
                   name="dayEnd">
        </div>

        <button type="submit" class="btn btn-primary pointer btn-sm">Показать</button>
    </form>
    <div style="margin:10px;">
        <h1>Плановые затраты с {{date('d.m.Y')}} по
            @if(app('request')->input('dayEnd'))
                {{date('d.m.Y',strtotime(app('request')->input('dayEnd')))}}
            @else
                (выберите дату)
            @endif
        </h1>
        <table class="table table-sm">
            <tr>
                <th>Код</th>
                <th>Наименование</th>
                <th>Тип</th>
                <th>Единица измерения</th>
                <th>Плановый расход</th>
                <th>В наличии</th>
                <th>Недостаток</th>

            </tr>
            @foreach($strings as $string)
                <tr>
                    <td>{{$string->material->id}}</td>
                    <td>{{$string->material->name}}</td>
                    <td>{{$string->material->typeName}}</td>
                    <td>{{$string->material->unitName}}</td>
                    <td>{{$string->quantity}}</td>
                    <td>{{$string->fact}}</td>
                    <td style="color:#d9534f;">
                        @if($string->fact - $string->quantity < 0)
                            {{abs($string->fact - $string->quantity)}}
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div id="error"></div>
@endsection

@section('script')
@endsection
