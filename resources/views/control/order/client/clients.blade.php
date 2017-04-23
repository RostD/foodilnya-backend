<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 22.04.2017
 * Time: 4:21
 */
?>


@extends('control.layout.main')

@section('title','Клиенты')

@section('content')
    @extends('control.layout.menu')

    @can('client-add')
        <button type="submit" class="btn btn-primary btn-sm pointer"
                style="margin:10px 0px 10px 10px"
                onclick="openPopupWindow('{{url('ctrl/order/client/add')}}','Добавить нового клиента',600,550)">Добавить
        </button>
    @endcan
    <button type="submit" class="btn btn-primary btn-sm pointer"
            style="margin:10px 0px 10px 10px"
            onclick="window.location.reload()">Обновить
    </button>

    <button type="submit" class="btn btn-primary btn-sm pointer"
            style="margin:10px 0px 10px 10px"
            onclick="toggle()">Фильтр
    </button>

    <div id="filter" style="display: none; margin: 10px; padding:5px; border:2px dashed #292B2C; ">
        <form method="GET">

            <div class="form-group row">
                <div class="col-10">
                    <input name="name" value="{{app('request')->input('name')}}" class="form-control" type="text"
                           autocomplete="off"
                           placeholder="ФИО содержит">
                </div>
                <div class="col-10">
                    <input name="login" value="{{app('request')->input('login')}}" class="form-control" type="text"
                           autocomplete="off"
                           placeholder="Логин содержит">
                </div>
            </div>

            <input type="submit" class="btn btn-success pointer" value="Применить фильтр">
            <a href="{{url('/ctrl/order/clients')}}" class="btn btn-danger  pointer">Сбросить фильтр</a>
        </form>


    </div>

    <table class="table table-sm">
        <tr>
            <th>Код</th>
            <th>Логин</th>
            <th>ФИО</th>
            <th>Телефон</th>
            <th>Адрес</th>
            <th>Действия</th>
        </tr>
        @foreach($clients as $client)
            <tr style="{{$client->trashed() ? 'text-decoration:line-through;background-color:#FBEFEF;':''}}">
                <td>{{$client->id}}</td>
                <td>{{$client->login}}</td>
                <td>{{$client->name}}</td>
                <td>{{$client->phone}}</td>
                <td>{{$client->address}}</td>
                <td>
                    @can('client-edit')
                        <img src="{{asset("imgs/icons/shock/edit.png")}}"
                             onclick="openPopupWindow('{{url('/ctrl/order/client/'.$client->id.'/edit')}}','Редактирование клиента',600,450)"
                             class="pointer"
                             width="20"
                             height="20"
                        >
                    @endcan

                    @can('dish-delete')
                        <img src="{{asset("imgs/icons/shock/trash_can.png")}}"
                             onclick="destroyDish('{{$client->id}}','{{$client->name}}')"
                             class="pointer"
                             width="20"
                             height="20"
                        >
                    @endcan

                    @can('dish-edit')
                        <a href="{{url('/ctrl/nmcl/cfg/dish').'/'.$client->id}}" target="_blank">
                            <img src="{{asset("imgs/icons/shock/technical_wrench.png")}}"
                                 class="pointer"
                                 width="20"
                                 height="20"
                            >
                        </a>
                    @endcan
                </td>
            </tr>


        @endforeach
    </table>
    <div id="error"></div>
@endsection

@section('script')
    <script>
        var state = false;
        function destroyDish(id, name) {
            var resp = confirm("Удалить блюдо \"" + name + "\"?");

            if (resp) {
                $.ajax({
                    url: '{{url('/ctrl/nmcl/dish')}}/' + id,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: 'DELETE',
                    success: function (data) {
                        window.location.reload();
                    },
                    error: function () {
                        alert("Ошибка");
                    }


                });
            } else {
                return false;
            }
        }

        function toggle() {
            state = !state;

            if (state)
                $('#filter').show();
            else
                $('#filter').hide();
        }
    </script>
@endsection




