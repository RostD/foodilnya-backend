<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 17.04.2017
 * Time: 8:01
 */

?>

@extends('control.layout.main')

@section('title','Редактирование приспособления блюда')

@section('content')
    <h4>{{$adaptation->name}}</h4>

    <form id="formProp" style="margin: 10px;" method="POST"
          action="{{url('ctrl/nmcl/cfg/dish/'.$dish->id.'/editAdaptation')}}">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="dish" value="{{$dish->id}}">
        <input type="hidden" name="adaptation" value="{{$adaptation->id}}">

        <div class="form-group {{$errors->first('quantity') ? 'has-danger' : ''}}">
            <label for="att-name">Количество (штук)</label>
            <input type="text" autocomplete="off"
                   class="form-control {{$errors->first('quantity') ? 'form-control-danger' : ''}}"
                   name="quantity"
                   id="att-name" value="{{$adaptation->quantity}}"
                   placeholder="Введите количество">
            @if($errors->first('quantity'))
                <div class="form-control-feedback">{{$errors->first('quantity')}}</div>@endif
        </div>

        <input type="button" onclick="closeWindow()" class="btn btn-warning pointer" value="Закрыть">
        <button type="submit" class="btn btn-primary pointer">Сохранить</button>
    </form>
    <div id="error"></div>
@endsection

@section('script')
    <script>

        function closeWindow() {
            window.opener.location.reload();
            window.close();
        }


    </script>
@endsection
