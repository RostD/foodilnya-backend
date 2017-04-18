<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 17.04.2017
 * Time: 8:01
 */

?>

@extends('control.layout.main')

@section('title','Добавление ингредиента')

@section('content')
    <h4>{{$ingredient->name}}</h4>

    <form id="formProp" style="margin: 10px;" method="POST"
          action="{{url('ctrl/nmcl/cfg/dish/'.$dish->id.'/editIngredient')}}">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="dish" value="{{$dish->id}}">
        <input type="hidden" name="ingredient" value="{{$ingredient->id}}">

        <div class="form-group {{$errors->first('quantity') ? 'has-danger' : ''}}">
            <label for="att-name">Количество</label>
            <input type="text" class="form-control {{$errors->first('quantity') ? 'form-control-danger' : ''}}"
                   name="quantity"
                   id="att-name" value="{{$ingredient->quantity}}"
                   placeholder="Введите количество">
            @if($errors->first('quantity'))
                <div class="form-control-feedback">{{$errors->first('quantity')}}</div>@endif
        </div>

        <div class="form-group {{$errors->first('unit') ? 'has-danger' : ''}}">
            <label for="tagsSelect">Единица измерения:</label><br/>
            <select class="custom-select" name="unit" id="unit-select">
                @foreach($ingredient->availableUnits as $availableUnit)
                    <option value="{{$availableUnit->id}}" {{$availableUnit->id==$ingredient->unit ? 'selected':''}}>{{$availableUnit->fullName}}</option>
                @endforeach
            </select>
            @if($errors->first('unit'))
                <div class="form-control-feedback">{{$errors->first('unit')}}</div>@endif
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
