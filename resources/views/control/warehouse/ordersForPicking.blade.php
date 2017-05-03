<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 03.05.2017
 * Time: 9:30
 */
?>

<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 02.05.2017
 * Time: 11:49
 */
?>


@extends('control.layout.main')

@section('title','Заказы на комплектацию')

@section('content')
    @extends('control.layout.menu')

    <form class="form-inline">
        <button type="submit" class="btn btn-primary btn-sm pointer"
                style="margin:10px 0px 10px 10px;"
                onclick="window.location.reload()">Обновить
        </button>
        <div class="form-group mx-sm-3">
            <input class="form-control form-control-sm"
                   type="date"
                   value="{{app('request')->input('day')}}"
                   id="date"
                   name="day">
        </div>

        <button type="submit" class="btn btn-primary pointer btn-sm">Показать</button>
    </form>
    <div style="margin:10px;">
        <h1>Заказы на комплектацию
            @if(app('request')->input('day'))
                на {{date('d.m.Y',strtotime(app('request')->input('day')))}}
                @if(app('request')->input('day') == date('Y-m-d'))
                    (сегодня)
                @endif
            @endif
        </h1>

        <table class="table">

            @foreach($orders as $order)
                <table class="table table-sm">
                    <tr class="table-info">
                        <th>
                            <h3 style="display: inline;">Заказ №{{$order->id}}</h3>
                            @can('order-edit')
                                <img src="{{asset("imgs/icons/shock/edit.png")}}"
                                     style="margin-bottom: 8px;"
                                     onclick="openPopupWindow('{{url('/ctrl/order/order/'.$order->id.'/edit')}}','Редактирование заказа',1000,600)"
                                     class="pointer"
                                     width="20"
                                     height="20"
                                >
                            @endcan
                        </th>
                        <th colspan="3"><h3>Время доставки: {{date('G:i',strtotime($order->date))}}</h3></th>
                    </tr>
                    <tr>
                        <th>Наименование</th>
                        <th>Тип</th>
                        <th>Количество</th>
                        <th>Единица измерения</th>
                    </tr>
                    @foreach($order->getMaterialStrings() as $item)
                        @if($item->material->type == \App\MaterialValue\Dish::type_id)
                            <tr>
                                <th colspan="4"><h5>{{$item->material->name}}</h5></th>
                            </tr>
                            @foreach($item->material->getIngredients($item->quantity) as $ingredient)
                                <tr>
                                    <td style="padding-left:20px;">{{$ingredient->name}}</td>
                                    <td>{{$ingredient->typeName}}</td>
                                    <td>{{$ingredient->quantity}}</td>
                                    <td>{{$ingredient->unitName}}</td>
                                </tr>
                            @endforeach

                            @foreach($item->material->getAdaptations($item->quantity) as $adaptation)
                                <tr>
                                    <td style="padding-left:20px;">{{$adaptation->name}}</td>
                                    <td>{{$adaptation->typeName}}</td>
                                    <td>{{$adaptation->quantity}}</td>
                                    <td>{{$adaptation->unitName}}</td>
                                </tr>
                            @endforeach
                        @else

                        @endif
                    @endforeach
                </table>
            @endforeach
        </table>
    </div>
    <div id="error"></div>
@endsection

@section('script')
@endsection


