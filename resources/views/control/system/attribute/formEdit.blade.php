<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 04.04.2017
 * Time: 14:46
 */
?>
@extends('control.layout.main')

@section('title','Изменение атрибута')

@section('content')

    <form id="formProp" style="margin: 10px;" method="POST">
        <input type="hidden" name="_method" value="PUT">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="att-name">Наименование</label> <span style="color:red;"><i>{{$errors->first('name')}}</i></span>
            <input type="text" class="form-control" name="name" id="att-name" value="{{$property->name}}"
                   placeholder="Введите наименование">
        </div>

        <div class="form-group">
            <label for="exampleTextarea">Возможные значения (через запятую)</label>
            <textarea class="form-control" id="exampleTextarea" name="possibleValues"
                      rows="3">@foreach($property->getPossibleValues() as $possibleValue){{$possibleValue->value}}@if(!$loop->last)
                    ,@endif @endforeach</textarea>

        </div>

        <div class="form-check">
            <label class="form-check-label">
                <input name="fixedValues" {{$property->isFixedValue() ? 'checked' : ''}} value="true" type="checkbox"
                       class="form-check-input">
                Фиксированные значения
            </label>
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