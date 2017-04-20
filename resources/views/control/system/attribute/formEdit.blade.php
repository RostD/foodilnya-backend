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

    <form id="formProp" style="margin: 10px;" method="POST" action="{{url('/ctrl/sys/attribute/'.$property->id)}}}">
        <input type="hidden" name="_method" value="PUT">
        {{ csrf_field() }}
        <div class="form-group {{$errors->first('name') ? 'has-danger' : ''}}">
            <label for="att-name">Наименование</label>
            <input type="text" autocomplete="off"
                   class="form-control {{$errors->first('name') ? 'form-control-danger' : ''}}" name="name"
                   id="att-name" value="{{$property->name}}"
                   placeholder="Введите наименование">
            @if($errors->first('name'))
                <div class="form-control-feedback">{{$errors->first('name')}}</div>@endif
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