<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 06.04.2017
 * Time: 11:43
 */
?>
@extends('control.layout.main')

@section('title','Изменение атрибута')

@section('content')

    <form id="formProp" style="margin: 10px;" method="POST">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="unit-full_name">Наименование</label> <span
                    style="color:red;"><i>{{$errors->first('full_name')}}</i></span>
            <input type="text" class="form-control" name="full_name" id="unit-full_name" value="{{old('full_name')}}"
                   placeholder="Введите полное наименование">
        </div>

        <div class="form-group">
            <label for="unit-name">Сокращённо</label> <span style="color:red;"><i>{{$errors->first('name')}}</i></span>
            <input type="text" class="form-control" name="name" id="unit-name" value="{{old('name')}}"
                   placeholder="Введите сокращённое наименование">
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
