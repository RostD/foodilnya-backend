<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 27.04.2017
 * Time: 14:53
 */
?>

@extends('control.layout.main')

@section('title','Редактирование заказа №('.$order->id.')')

@section('content')

    <div style="margin-left:10px; margin-right: 10px;">


        <h1 style="display: inline;">Заказ №{{$order->id}}
            <button type="submit" class="btn btn-primary btn-sm pointer"
                    onclick="window.location.reload()">Обновить
            </button>
            <button type="submit" class="btn btn-warning btn-sm pointer"
                    onclick="closeWindow()">Закрыть
            </button>
        </h1>


        <div class="card card-outline-primary mb-3 text-center">
            <div class="card-block">
                <blockquote class="card-blockquote">
                    <table class="table table-sm">
                        <tr>
                            <th>Заказчик</th>
                            <th>Адрес доставки</th>
                            <th>Дата и время доставки</th>
                        </tr>
                        <tr style="text-align: left;">
                            <td>{{$order->client->name}}</td>
                            <td>{{$order->address}}</td>
                            <td>{{$order->date}}</td>
                        </tr>
                    </table>
                </blockquote>
            </div>
        </div>


        <div class="card card-outline-primary mb-3 text-center">
            <div class="card-block">

                <h5 style="margin-bottom: 10px;">Материальные ценности</h5>
                <button type="submit" class="btn btn-secondary btn-sm pointer"
                        onclick="openPopupWindow('{{url('/ctrl/order/order/'.$order->id.'/addMaterialStringDish')}}','Добавить строку',600,500)">
                    Добавить блюдо
                </button>

                <button type="submit" class="btn btn-secondary btn-sm pointer"
                        onclick="openPopupWindow('{{url('/ctrl/order/order/'.$order->id.'/addMaterialStringIngredient')}}','Добавить строку',600,500)">
                    Добавить ингредиент
                </button>

                <button type="submit" class="btn btn-secondary btn-sm pointer"
                        style="margin:10px 0px 10px 10px"
                        onclick="openPopupWindow('{{url('/ctrl/order/order/'.$order->id.'/addMaterialStringAdaptation')}}','Добавить строку',600,500)">
                    Добавить приспособление
                </button>

                <table class="table table-sm">
                    <tr>
                        <th>Наименование</th>
                        <th>Тип</th>
                        <th>Количество</th>
                        <th>Единица измерения</th>
                        <th>Действия</th>
                    </tr>
                    @foreach($order->materialStrings as $materialString)
                        <tr style="text-align: left">
                            <td>{{$materialString->material->name}}</td>
                            <td>{{$materialString->material->typeName}}</td>
                            <td>{{$materialString->quantity}}</td>
                            <td>{{$materialString->material->unitName}}</td>

                            <td>
                                <img src="{{asset("imgs/icons/shock/edit.png")}}"
                                     onclick="openPopupWindow('{{url('/ctrl/nmcl/cfg/dish/'.$order->id.'/ingredient/')}}','Редактирование состава блюда',600,300)"
                                     class="pointer"
                                     width="20"
                                     height="20"
                                >
                                <img src="{{asset("imgs/icons/shock/trash_can.png")}}"
                                     onclick="removeMaterialString('{{$materialString->material->id}}','{{$materialString->material->name}}')"
                                     class="pointer"
                                     width="20"
                                     height="20"
                                >
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>


        <form style="display: inline; margin-left: 20px;" method="POST"
              action="{{url('ctrl/order/order/'.$order->id)}}">
            {{csrf_field()}}
            <input type="hidden" name="_method" value="PUT">
            @can('order-confirm')
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="confirmed"
                           {{$order->confirmed? 'checked':''}} value="true">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Заказ подтвержден и оплачен</span>
                </label>
            @endcan

            @can('order-close')
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="closed"
                           {{$order->done? 'checked':''}} value="true">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Заказ выполнен</span>
                </label>
            @endcan
            <button type="submit" class="btn btn-primary btn-sm pointer">Сохранить</button>
        </form>
    </div>

    <div id="error"></div>
@endsection

@section('script')
    <script>
        function removeMaterialString(id, name) {
            var resp = confirm("Удалить строку с \"" + name + "\"?");

            if (resp) {
                $.ajax({
                    url: '{{url('/ctrl/order/order')}}/{{$order->id}}/material/' + id,
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

        function closeWindow() {
            window.opener.location.reload();
            window.close();
        }
    </script>
@endsection
