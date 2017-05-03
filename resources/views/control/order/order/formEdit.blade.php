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

        <div class="row">
            <div class="col-3">
                <h1>Заказ №{{$order->id}}</h1>
            </div>
            <div class="col" style="padding-top:10px;">
                <button type="submit" class="btn btn-primary btn-sm pointer"
                        onclick="window.location.reload()">Обновить
                </button>
                <button type="submit" class="btn btn-warning btn-sm pointer"
                        style="margin-left:15px;"
                        onclick="closeWindow()">Закрыть
                </button>
            </div>
        </div>

        <div class="card card-outline-primary mb-3">
            <div class="card-block">
                <blockquote class="card-blockquote">
                    <table class="table table-sm">
                        <tr>
                            <th>Заказчик</th>
                            <th>Адрес доставки</th>
                            <th>Дата и время доставки</th>
                        </tr>
                        <tr>
                            <td>{{$order->client->name}}</td>
                            <td>{{$order->address}}</td>
                            <td>{{$order->date}}</td>
                        </tr>
                    </table>
                </blockquote>
            </div>
        </div>


        <h5 style="margin-bottom: 10px;">Материальные ценности</h5>
        <div class="card card-outline-primary mb-3">
            <div class="card-block">
                @can('order-editStrings')
                    <div style="margin-bottom: 10px;">
                        @if($order->confirmed || $order->equipped || $order->done)
                            <fieldset disabled>
                                @endif
                                <button type="submit" class="btn btn-secondary btn-sm pointer"
                                        onclick="openPopupWindow('{{url('/ctrl/order/order/'.$order->id.'/addMaterialStringDish')}}','Добавить строку',600,500)">
                                    Добавить блюдо
                                </button>

                                <button type="submit" class="btn btn-secondary btn-sm pointer"
                                        onclick="openPopupWindow('{{url('/ctrl/order/order/'.$order->id.'/addMaterialStringIngredient')}}','Добавить строку',600,500)">
                                    Добавить ингредиент
                                </button>

                                <button type="submit" class="btn btn-secondary btn-sm pointer"
                                        onclick="openPopupWindow('{{url('/ctrl/order/order/'.$order->id.'/addMaterialStringAdaptation')}}','Добавить строку',600,500)">
                                    Добавить приспособление
                                </button>
                            </fieldset>
                    </div>
                @endcan
                <table class="table table-sm">
                    <tr>
                        <th>Наименование</th>
                        <th>Тип</th>
                        <th>Количество</th>
                        <th>Единица измерения</th>
                        @if(!$order->confirmed && !$order->equipped && !$order->done)
                            @can('order-editStrings')
                                <th>Действия</th>
                            @endcan
                        @endif
                    </tr>
                    @foreach($order->materialStrings as $materialString)
                        <tr>
                            <td>{{$materialString->material->name}}</td>
                            <td>{{$materialString->material->typeName}}</td>
                            <td>{{$materialString->quantity}}</td>
                            <td>{{$materialString->material->unitName}}</td>

                            @if(!$order->confirmed && !$order->equipped && !$order->done)
                                @can('order-editStrings')
                                    <td>
                                        <img src="{{asset("imgs/icons/shock/edit.png")}}"
                                             onclick="openPopupWindow('{{url('/ctrl/order/order/'.$order->id.'/material/'.$materialString->material->id.'')}}','Редактирование состава блюда',600,300)"
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
                                @endcan
                            @endif
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>

        <h5 style="margin-bottom: 10px;">Статус заказа</h5>
        <div class="card card-outline-primary mb-3">
            <div class="card-block">
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

                    @can('order-equip')
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="equipped"
                                   {{$order->equipped? 'checked':''}} value="true">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Заказ скомплектован</span>
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
                    <button type="submit" class="btn btn-secondary btn-sm pointer">Сохранить</button>
                </form>
            </div>
        </div>
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
