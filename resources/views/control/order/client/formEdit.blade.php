<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 23.04.2017
 * Time: 07:31
 */
?>

@extends('control.layout.main')

@section('title','Добавление клиента')

@section('content')

    <form id="formProp" style="margin: 10px;" method="POST" action="{{url('ctrl/order/client/'.$client->id)}}">
        <input type="hidden" name="_method" value="PUT">
        {{ csrf_field() }}

        <div class="form-group {{$errors->first('login') ? 'has-danger' : ''}}">
            <label for="att-name">Логин</label>
            <input type="text" autocomplete="off"
                   class="form-control {{$errors->first('login') ? 'form-control-danger' : ''}}" name="login"
                   id="att-name" value="{{$client->login}}"
                   placeholder="Номер телефона без 8">
            @if($errors->first('login'))
                <div class="form-control-feedback">{{$errors->first('login')}}</div>@endif
        </div>

        <div class="form-group {{$errors->first('name') ? 'has-danger' : ''}}">
            <label for="att-name">ФИО клиента</label>
            <input type="text" autocomplete="off"
                   class="form-control {{$errors->first('name') ? 'form-control-danger' : ''}}" name="name"
                   id="att-name" value="{{$client->name}}"
                   placeholder="Введите ФИО">
            @if($errors->first('name'))
                <div class="form-control-feedback">{{$errors->first('name')}}</div>@endif
        </div>

        <div class="form-group">
            <label for="exampleTextarea">Адрес доставки</label>
            <textarea class="form-control" id="exampleTextarea" name="address"
                      rows="3">{{$client->address}}</textarea>
        </div>

        <div class="form-group {{$errors->first('phone') ? 'has-danger' : ''}}">
            <label for="att-name">Контактные телефоны</label>
            <input type="text" autocomplete="off"
                   class="form-control {{$errors->first('phone') ? 'form-control-danger' : ''}}" name="phone"
                   id="att-name" value="{{$client->phone}}"
                   placeholder="Введите контактые телефоны">
            @if($errors->first('phone'))
                <div class="form-control-feedback">{{$errors->first('phone')}}</div>@endif
        </div>


        <input type="button" onclick="closeWindow()" class="btn btn-warning pointer" value="Закрыть">
        <button type="submit" class="btn btn-primary pointer">Сохранить</button>
    </form>

@endsection

@section('script')
    <script>

        function closeWindow() {
            window.opener.location.reload();
            window.close();
        }
    </script>
@endsection