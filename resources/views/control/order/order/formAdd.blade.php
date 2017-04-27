<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 27.04.2017
 * Time: 12:49
 */
?>

@extends('control.layout.main')

@section('title','Добавление заказа')

@section('content')

    <form id="formProp" style="margin: 10px;" method="POST">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="tagsSelect">Клиент:</label><br/>
            <select class="custom-select" id="tagsSelect" name="client" onchange="loadClientAddress(value)">
                <option value="" disabled="disabled" selected>Выберите клиента</option>
                @foreach($clients as $client)
                    <option value="{{$client->id}}" {{old('client')==$client->id?'selected':''}}>{{$client->name}}</option>
                @endforeach
            </select>
            @can('client-add')
                <input type="button" class="btn btn-primary btn-sm pointer"
                       style="margin:10px 0px 10px 10px"
                       onclick="openPopupWindow('{{url('/ctrl/order/client/add')}}','Создать клиента',600,500)"
                       value="Добавить">
                </input>
            @endcan
        </div>

        <div class="form-group {{$errors->first('date') ? 'has-danger' : ''}}">
            <label for="date">Ориентирововные дата и время доставки</label>
            <div>
                <input class="form-control {{$errors->first('date') ? 'form-control-danger' : ''}}"
                       type="datetime-local"
                       value="{{old('date')}}"
                       id="date"
                       name="date">
                @if($errors->first('date'))
                    <div class="form-control-feedback">{{$errors->first('date')}}</div>@endif
            </div>
        </div>

        <div class="form-group">
            <label for="exampleTextarea">Адрес доставки</label>
            <textarea class="form-control" id="exampleTextarea" name="address"
                      rows="3">{{old('address')}}</textarea>
        </div>

        <input type="button" onclick="closeWindow()" class="btn btn-warning pointer" value="Закрыть">
        <button type="submit" class="btn btn-primary pointer">Сохранить</button>
    </form>
    <div id="error"></div>
@endsection

@section('script')
    <script>
        function loadClientAddress(clientId) {
            $.ajax({
                url: '{{url('/api/client')}}/' + clientId + '/address',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: 'GET',
                success: function (data) {
                    $('#exampleTextarea').val(data.data.address);
                },
                error: function (data) {
                    alert("Ошибка");
                    console.log(data);
                    $('#error').html(data.responseText);
                }


            });
        }
        function closeWindow() {
            window.opener.location.reload();
            window.close();
        }
    </script>
@endsection
