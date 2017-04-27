<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 23.04.2017
 * Time: 9:22
 */
?>


@extends('control.layout.main')

@section('title','Заказы')

@section('content')
    @extends('control.layout.menu')

    @can('client-add')
        <button type="submit" class="btn btn-primary btn-sm pointer"
                style="margin:10px 0px 10px 10px"
                onclick="openPopupWindow('{{url('ctrl/order/order/add')}}','Добавить нового клиента',600,550)">Добавить
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
            <th>Заказчик</th>
            <th>Дата доставки</th>
            <th>Подтвержден</th>
            <th>Выполнен</th>
            <th>Действия</th>
        </tr>
        @foreach($orders as $order)
            <tr style="{{$order->trashed() ? 'text-decoration:line-through;background-color:#FBEFEF;':''}}">
                <td>{{$order->id}}</td>
                <td>{{$order->client->name}}</td>
                <td>{{$order->date}}</td>
                <td>
                    @if($order->confirmed)
                        +
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($order->done)
                        +
                    @else
                        -
                    @endif
                </td>

                <td>
                    @can('client-edit')
                        <img src="{{asset("imgs/icons/shock/edit.png")}}"
                             onclick="openPopupWindow('{{url('/ctrl/order/client/'.$order->id.'/edit')}}','Редактирование клиента',600,450)"
                             class="pointer"
                             width="20"
                             height="20"
                        >
                    @endcan

                    @can('client-delete')
                        <img src="{{asset("imgs/icons/shock/trash_can.png")}}"
                             onclick="destroyClient('{{$order->id}}','{{$order->client->name}}')"
                             class="pointer"
                             width="20"
                             height="20"
                        >
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
        function destroyClient(id, name) {
            var resp = confirm("Удалить клиента \"" + name + "\"?");

            if (resp) {
                $.ajax({
                    url: '{{url('/ctrl/order/client')}}/' + id,
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





