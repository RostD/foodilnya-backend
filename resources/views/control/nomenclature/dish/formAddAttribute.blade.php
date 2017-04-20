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
    <h4 style="margin:10px;">{{$dish->name}}</h4>

    <form id="formProp" style="margin: 10px;" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="dish" value="{{$dish->id}}">

        <div class="form-group {{$errors->first('attribute') ? 'has-danger' : ''}}">
            <label for="tagsSelect">Свойство:</label><br/>
            <select class="custom-select" onchange="getAttributeData(value)" name="attribute">
                <option selected disabled="disabled">Выберите свойство</option>
                @foreach($attributes as $attribute)
                    <option value="{{$attribute->id}}" {{old('ingredient')==$attribute->id ? 'selected':''}}>{{$attribute->name}}</option>
                @endforeach
            </select>
            @can('attribute-add')
                <input type="button" class="btn btn-primary btn-sm pointer"
                       style="margin:10px 0px 10px 10px"
                       onclick="openPopupWindow('{{url('/ctrl/sys/attribute/add')}}','Создать атрибут',600,500)"
                       value="Добавить">
                </input>
            @endcan
            @if($errors->first('attribute'))
                <div class="form-control-feedback">{{$errors->first('ingredient')}}</div>@endif
        </div>

        <div class="form-group {{$errors->first('value') ? 'has-danger' : ''}}" id="value-input" style="display:none;">
            <label for="att-name">Значение <span id="unitName"
                                                 style="font-style: italic; font-weight: bold;"></span></label>
            <input type="text" autocomplete="off"
                   class="form-control {{$errors->first('value') ? 'form-control-danger' : ''}}"
                   name="value"
                   id="att-name" value="{{old('value')}}"
                   placeholder="Введите значение">
            @if($errors->first('value'))
                <div class="form-control-feedback">{{$errors->first('value')}}</div>@endif
        </div>

        <div class="form-group {{$errors->first('possValue') ? 'has-danger' : ''}}" id="possValue-input"
             style="display:none;">
            <label for="tagsSelect">Возможные значения:</label><br/>
            <select class="custom-select" name="possValue" id="possValue-select">
                <option disabled>Выберите атрибут</option>
            </select>
            @if($errors->first('possValue'))
                <div class="form-control-feedback">{{$errors->first('possValue')}}</div>@endif
        </div>

        <input type="button" onclick="closeWindow()" class="btn btn-warning pointer" value="Закрыть">
        <button type="submit" class="btn btn-primary pointer">Сохранить</button>
    </form>
    <div id="error"></div>
@endsection

@section('script')
    <script>
        function getAttributeData(ingredientId) {
            $('#unitName').empty();
            $('#possValue-input').css('display', 'none');
            $('#value-input').css('display', 'none');
            $.ajax({
                url: '{{url('/api/attribute')}}/' + ingredientId,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: 'GET',
                success: function (data) {
                    console.log(data);
                    if (data.data.unit !== '')
                        $('#unitName').html('(' + data.data.unit + ')');
                    if (data.data.possibleValues.length > 0) {
                        updatePossValueOptions(data.data.possibleValues, data.data.fixedValues)
                    }
                    if (data.data.fixedValues == 0) {
                        $('#value-input').css('display', 'block');
                    }
                    //;
                },
                error: function (data) {
                    alert("Ошибка");
                    console.log(data);
                    $('#error').html(data.responseText);
                }


            });

        }
        function updatePossValueOptions(obj, fixed) {

            $('#possValue-select').empty();
            $('#possValue-input').css('display', 'block');
            if (fixed == 0)
                $('#possValue-select').append('<option value="">Выберите значение</option>')
            obj.forEach(function (value) {
                $('#possValue-select').append('<option value="' + value.value + ' ">' + value.value + '</option>')
            })

        }

        function closeWindow() {
            window.opener.location.reload();
            window.close();
        }


    </script>
@endsection
