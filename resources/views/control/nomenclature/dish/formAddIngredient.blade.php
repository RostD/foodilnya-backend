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
    <h4>{{$dish->name}}</h4>

    <form id="formProp" style="margin: 10px;" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="dish" value="{{$dish->id}}">

        <div class="form-group {{$errors->first('ingredient') ? 'has-danger' : ''}}">
            <label for="tagsSelect">Ингредиент:</label><br/>
            <select class="custom-select" onchange="getUnits(value)" name="ingredient">
                <option selected disabled="disabled">Выберите ингредиент</option>
                @foreach($ingredients as $ingredient)
                    <option value="{{$ingredient->id}}" {{old('ingredient')==$ingredient->id ? 'selected':''}}>{{$ingredient->name}}</option>
                @endforeach
            </select>
            @can('ingredient-add')
                <input type="button" class="btn btn-primary btn-sm pointer"
                       style="margin:10px 0px 10px 10px"
                       onclick="openPopupWindow('{{url('/ctrl/nmcl/ingredient/add')}}','Создать ингредиент',600,500)"
                       value="Добавить">
                </input>
            @endcan
            @if($errors->first('ingredient'))
                <div class="form-control-feedback">{{$errors->first('ingredient')}}</div>@endif
        </div>

        <div class="form-group {{$errors->first('quantity') ? 'has-danger' : ''}}">
            <label for="att-name">Количество</label>
            <input type="text" class="form-control {{$errors->first('quantity') ? 'form-control-danger' : ''}}"
                   name="quantity"
                   id="att-name" value="{{old('quantity')}}"
                   placeholder="Введите количество">
            @if($errors->first('quantity'))
                <div class="form-control-feedback">{{$errors->first('quantity')}}</div>@endif
        </div>

        <div class="form-group {{$errors->first('unit') ? 'has-danger' : ''}}">
            <label for="tagsSelect">Единица измерения:</label><br/>
            <select class="custom-select" name="unit" id="unit-select">
                <option disabled>Выберите ингредиент</option>
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
        function getUnits(ingredientId) {
            $.ajax({
                url: '{{url('/api/ingredient')}}/' + ingredientId + '/availableUnits',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: 'GET',
                success: function (data) {
                    updateUnitOptions(data.data);
                },
                error: function (data) {
                    alert("Ошибка");
                    console.log(data);
                    $('#error').html(data.responseText);
                }


            });

        }
        function updateUnitOptions(obj) {

            $('#unit-select').empty();
            obj.forEach(function (unit) {
                $('#unit-select').append('<option value="' + unit.id + ' ">' + unit.name + '</option>')
            })

        }

        function closeWindow() {
            window.opener.location.reload();
            window.close();
        }


    </script>
@endsection
