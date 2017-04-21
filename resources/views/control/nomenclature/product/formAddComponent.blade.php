<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 17.04.2017
 * Time: 8:01
 */

?>

@extends('control.layout.main')

@section('title','Добавление компонента')

@section('content')
    <h4>{{$product->name}}</h4>

    <form id="formProp" style="margin: 10px;" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="product" value="{{$product->id}}">

        <div class="form-group {{$errors->first('component') ? 'has-danger' : ''}}">
            <label for="tagsSelect">Компонент блюда:</label><br/>
            <select class="custom-select" onchange="getUnits(value)" name="component">
                <option selected disabled="disabled">Выберите компонент</option>
                @foreach($components as $component)
                    <option value="{{$component->id}}" {{old('component')==$component->id ? 'selected':''}}>{{$component->name}}</option>
                @endforeach
            </select>

            @if($errors->first('component'))
                <div class="form-control-feedback">{{$errors->first('component')}}</div>@endif
        </div>

        <div class="form-group {{$errors->first('unit') ? 'has-danger' : ''}}">
            <label for="tagsSelect">Единица измерения:</label><br/>
            <select class="custom-select" name="unit" id="unit-select">
                <option disabled>Выберите компонент</option>
            </select>
            @if($errors->first('unit'))
                <div class="form-control-feedback">{{$errors->first('unit')}}</div>@endif
        </div>

        <div class="form-group {{$errors->first('quantity') ? 'has-danger' : ''}}">
            <label for="att-name">Количество компонента на единицу товара ({{$product->unitName}})</label>
            <input type="text" autocomplete="off"
                   class="form-control {{$errors->first('quantity') ? 'form-control-danger' : ''}}"
                   name="quantity"
                   id="att-name" value="{{old('quantity')}}"
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
        function getUnits(componentId) {
            $.ajax({
                url: '{{url('/api/component')}}/' + componentId + '/availableUnits',
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
