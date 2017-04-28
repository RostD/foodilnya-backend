<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 28.04.2017
 * Time: 14:45
 */
?>


@extends('control.layout.main')

@section('title','Редактирование строкки материальной ценности заказа')

@section('content')
    <div style="margin: 10px;">
        <h4>{{$materialString->material->name}}</h4>

        <form id="formProp" method="POST"
              action="{{url('ctrl/order/order/'.$order->id.'/editMaterialString')}}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="order" value="{{$order->id}}">
            <input type="hidden" name="material" value="{{$materialString->material->id}}">

            <div class="form-group {{$errors->first('quantity') ? 'has-danger' : ''}}">
                <label for="att-name">Количество</label>
                <input type="text" autocomplete="off"
                       class="form-control {{$errors->first('quantity') ? 'form-control-danger' : ''}}"
                       name="quantity"
                       id="att-name" value="{{$materialString->quantity}}"
                       placeholder="Введите количество">
                @if($errors->first('quantity'))
                    <div class="form-control-feedback">{{$errors->first('quantity')}}</div>@endif
            </div>

            <div class="form-group {{$errors->first('unit') ? 'has-danger' : ''}}">
                <label for="tagsSelect">Единица измерения:</label><br/>
                <select class="custom-select" name="unit" id="unit-select">
                    @foreach($materialString->material->availableUnits as $availableUnit)
                        <option value="{{$availableUnit->id}}" {{$availableUnit->id==$materialString->material->unit ? 'selected':''}}>{{$availableUnit->fullName}}</option>
                    @endforeach
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

        function closeWindow() {
            window.opener.location.reload();
            window.close();
        }


    </script>
@endsection

