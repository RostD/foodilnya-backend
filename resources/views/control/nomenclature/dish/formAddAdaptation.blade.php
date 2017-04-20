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

        <div class="form-group {{$errors->first('adaptation') ? 'has-danger' : ''}}">
            <label for="tagsSelect">Ингредиент:</label><br/>
            <select class="custom-select" name="adaptation">
                <option selected disabled="disabled">Выберите приспособление</option>
                @foreach($adaptations as $adaptation)
                    <option value="{{$adaptation->id}}" {{old('adaptation')==$adaptation->id ? 'selected':''}}>{{$adaptation->name}}</option>
                @endforeach
            </select>
            @can('adaptation-add')
                <input type="button" class="btn btn-primary btn-sm pointer"
                       style="margin:10px 0px 10px 10px"
                       onclick="openPopupWindow('{{url('/ctrl/nmcl/adaptation/add')}}','Создать приспособление',600,500)"
                       value="Добавить">
                </input>
            @endcan
            @if($errors->first('adaptation'))
                <div class="form-control-feedback">{{$errors->first('adaptation')}}</div>@endif
        </div>

        <div class="form-group {{$errors->first('quantity') ? 'has-danger' : ''}}">
            <label for="quantity-input">Количество (Штук)</label>
            <input type="text" autocomplete="off"
                   class="form-control {{$errors->first('quantity') ? 'form-control-danger' : ''}}"
                   name="quantity"
                   id="quantity-input"
                   value="{{old('quantity')}}"
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
