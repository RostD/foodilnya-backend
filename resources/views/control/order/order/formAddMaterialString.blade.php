<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 28.04.2017
 * Time: 7:22
 */
?>


@extends('control.layout.main')

@section('title','Добавление строки с материальной ценностью')

@section('content')
    <div style="margin: 10px;">
        <h4>К заказу №{{$order->id}}</h4>

        <form id="formProp" method="POST" action="{{url('/ctrl/order/order/'.$order->id.'/addMaterialString')}}">
            {{ csrf_field() }}
            <input type="hidden" name="order" value="{{$order->id}}">

            <div class="form-group {{$errors->first('material') ? 'has-danger' : ''}}">
                <label for="tagsSelect">Материальная ценность:</label><br/>
                <select class="custom-select" onchange="getUnits(value)" name="material">
                    <option selected disabled="disabled">Выберите</option>
                    @foreach($materials as $material)
                        <option value="{{$material->id}}" {{old('material')==$material->id ? 'selected':''}}>{{$material->name}}</option>
                    @endforeach
                </select>
                @if($errors->first('material'))
                    <div class="form-control-feedback">{{$errors->first('material')}}</div>@endif
            </div>

            <div class="form-group {{$errors->first('quantity') ? 'has-danger' : ''}}">
                <label for="att-name">Количество</label>
                <input type="text" autocomplete="off"
                       class="form-control {{$errors->first('quantity') ? 'form-control-danger' : ''}}"
                       name="quantity"
                       id="att-name" value="{{old('quantity')}}"
                       placeholder="Введите количество">
                @if($errors->first('quantity'))
                    <div class="form-control-feedback">{{$errors->first('quantity')}}</div>@endif
            </div>

            <div class="form-group {{$errors->first('unit') ? 'has-danger' : ''}}">
                <label for="tagsSelect">Единица измерения:</label><br/>
                <select class="custom-select" name="unit" id="unit-select">
                    <option disabled>Выберите мат. ценность</option>
                </select>
                @if($errors->first('unit'))
                    <div class="form-control-feedback">{{$errors->first('unit')}}</div>@endif
            </div>

            <input type="button" onclick="closeWindow()" class="btn btn-warning pointer" value="Закрыть">
            <button type="submit" class="btn btn-primary pointer">Сохранить</button>
        </form>
    </div>
    <div id="error"></div>
@endsection

@section('script')
    <script>
        function getUnits(materialId) {
            $.ajax({
                url: '{{url('/api/material')}}/' + materialId + '/availableUnits',
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

