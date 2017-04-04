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

    <form action="{{url('/ctrl/sys/attribute/'.$property->id)}}" style="margin: 10px;" method="POST">
        <input type="hidden" name="_method" value="PUT">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="att-name">Наименование</label>
            <input type="text" class="form-control" name="name" id="att-name" value="{{$property->name}}"
                   placeholder="Введите наименование">
        </div>

        <div class="form-group">
            <label for="att-type">Для типа товара:</label>
            <select class="form-control" id="att-type" name="type">
                <option value="false" selected>Не важно</option>
                @foreach($types as $type)
                    <option value="{{$type->id}}" {{$property->type === $type->id ? 'selected' : ''}}>{{$type->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="exampleTextarea">Возможные значения (через запятую)</label>
            <textarea class="form-control" id="exampleTextarea"
                      rows="3">@foreach($property->getPossibleValues() as $possibleValue){{$possibleValue->value.","}}@endforeach</textarea>

        </div>

        <div class="form-check">
            <label class="form-check-label">
                <input name="fixedValues" {{$property->isFixedValue() ? 'checked' : ''}} value="true" type="checkbox"
                       class="form-check-input">
                Фиксированные значения
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Сохранить</button>

    </form>

@endsection
