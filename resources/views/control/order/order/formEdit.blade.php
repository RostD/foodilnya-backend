<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 27.04.2017
 * Time: 14:53
 */
?>

@extends('control.layout.main')

@section('title','Редактирование заказа №('.$order->id.')')

@section('content')
    <div style="margin-left:10px;">
        <h1>Заказ №{{$order->id}}</h1>
        <table class="table table-sm">
            <tr>
                <th>Заказчик</th>
                <th>Адрес доставки</th>
                <th>Дата и время доставки</th>
            </tr>
            <tr class="table-info">
                <td>{{$order->client->name}}</td>
                <td>{{$order->address}}</td>
                <td>{{$order->date}}</td>
            </tr>
        </table>


        <h5 style="display: inline;">Материальные ценности</h5>
        <button type="submit" class="btn btn-primary btn-sm pointer"
                style="margin:10px 0px 10px 10px"
                onclick="openPopupWindow('{{url('/ctrl/nmcl/cfg/dish/'.$order->id.'/addIngredient')}}','Добавить ингредиент',600,500)">
            Добавить
        </button>

        <table class="table table-sm">
            <tr>
                <th>Наименование</th>
                <th>Тип</th>
                <th>Количество</th>
                <th>Единица измерения</th>
                <th>Действия</th>
            </tr>
            @foreach($order->materialStrings as $materialString)
                <tr>
                    <td>{{$materialString->material->name}}</td>
                    <td>{{$materialString->material->typeName}}</td>
                    <td>{{$materialString->quantity}}</td>
                    <td>{{$materialString->material->unitName}}</td>

                    <td>
                        <img src="{{asset("imgs/icons/shock/edit.png")}}"
                             onclick="openPopupWindow('{{url('/ctrl/nmcl/cfg/dish/'.$order->id.'/ingredient/')}}','Редактирование состава блюда',600,300)"
                             class="pointer"
                             width="20"
                             height="20"
                        >
                        <img src="{{asset("imgs/icons/shock/trash_can.png")}}"
                             onclick=""
                             class="pointer"
                             width="20"
                             height="20"
                        >
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div id="error"></div>
@endsection

@section('script')
    <script>

    </script>
@endsection
