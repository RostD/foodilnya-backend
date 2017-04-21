<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 16.04.2017
 * Time: 7:33
 */
?>
@extends('control.layout.main')

@section('title','Конфигуратор товара ('.$product->name.')')

@section('content')
    @extends('control.layout.menu')

    <h3 style="margin-left:10px;">[{{$product->id}}]
        {{$product->name}}
        <img src="{{asset("imgs/icons/shock/edit.png")}}"
             onclick="openPopupWindow('{{url('/ctrl/nmcl/product/'.$product->id.'/edit')}}','Редактирование товара',600,450)"
             class="pointer"
             width="20"
             height="20"
        >
    </h3>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist" id="cfg-nav">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#main" role="tab">Описание</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#dish-component" role="tab">Компонент блюда</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#suppliers" role="tab">Поставщики</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#note" role="tab">Примечания</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#properties" role="tab">Свойства</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tags" role="tab">Теги</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">

        <div class="tab-pane active" id="main" role="tabpanel">
            <table class="table">
                <tr>
                    <td>Единица измерения</td>
                    <td>{{$product->unitName}}</td>
                </tr>
            </table>
        </div>

        <div class="tab-pane" id="dish-component" role="tabpanel">

            @if(!$product->dishComponent)
                <button type="submit" class="btn btn-primary btn-sm pointer"
                        style="margin:10px 0px 10px 10px"
                        onclick="openPopupWindow('{{url('/ctrl/nmcl/cfg/product/'.$product->id.'/addComponent')}}','Задать ингредиент',600,500)">
                    Задать
                </button>
            @else

                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title"
                            style="{{$product->dishComponent->trashed() ? 'text-decoration:line-through;background-color:#FBEFEF;':''}}">{{$product->dishComponent->name}}</h4>
                        <p class="card-text">{{$product->dishComponent->quantity}} {{$product->dishComponent->unitName}}
                            на 1 {{$product->unitName}} товара</p>

                        <button type="submit" class="btn btn-primary btn-sm pointer"
                                style="margin:10px 0px 10px 0px"
                                onclick="openPopupWindow('{{url('/ctrl/nmcl/cfg/dish/'.$product->id.'/addIngredient')}}','Задать ингредиент',600,500)">
                            Редактировать соотношение
                        </button>

                        <button type="submit" class="btn btn-danger btn-sm pointer"
                                style="margin:10px 0px 10px 10px"
                                onclick="openPopupWindow('{{url('/ctrl/nmcl/cfg/dish/'.$product->id.'/addIngredient')}}','Задать ингредиент',600,500)">
                            Удалить компонент
                        </button>
                    </div>
                </div>
            @endif

        </div>

        <div class="tab-pane" id="suppliers" role="tabpanel">
            ...
        </div>

        <div class="tab-pane" id="note" role="tabpanel">
            ...
        </div>

        <div class="tab-pane" id="properties" role="tabpanel">

            <button type="submit" class="btn btn-primary btn-sm pointer"
                    style="margin:10px 0px 10px 10px"
                    onclick="openPopupWindow('{{url('/ctrl/nmcl/cfg/dish/'.$product->id.'/addAttribute')}}','Добавить свойство',600,500)">
                Добавить
            </button>

            <table class="table">
                <tr>
                    <th>Наименование</th>
                    <th>Значение</th>
                    <th>Действия</th>
                </tr>
                @foreach($product->getProperties(true) as $property)
                    <tr style="{{$property->trashed() ? 'text-decoration:line-through;background-color:#FBEFEF;':''}}">
                        <td>{{$property->name}}</td>
                        <td>{{$property->value}} @if($property->unit)({{$property->unit}})@endif</td>
                        <td>
                            <img src="{{asset("imgs/icons/shock/trash_can.png")}}"
                                 onclick="removeAttr('{{$property->id}}','{{$property->name}}')"
                                 class="pointer"
                                 width="20"
                                 height="20"
                            >
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="tab-pane" id="tags" role="tabpanel">
            <button type="submit" class="btn btn-primary btn-sm pointer"
                    style="margin:10px 0px 10px 10px"
                    onclick="openPopupWindow('{{url('/ctrl/nmcl/product/'.$product->id.'/edit')}}','Редактирование товара',600,450)">
                Редактировать
            </button>
            <div style="margin:10px;">
                @foreach($product->tags as $tag)
                    <h5><span class="badge badge-success">{{$tag->name}}</span></h5>
                @endforeach
            </div>
        </div>

    </div>
    <div id="error"></div>
@endsection

@section('script')
    <script src="{{ asset('/js/ckeditor/ckeditor.js') }}" type="text/javascript" charset="utf-8"></script>

    <script>


        function setHash(urlString) {

            var from = urlString.search('#');
            var to = urlString.length;
            var hash = urlString.substring(from, to);

            window.location.hash = hash;
        }


        $(function () {

            var cfg_navigation = $('#cfg-nav');

            cfg_navigation.find('a').each(function () {
                $(this).on("click", function () {
                    setHash(this.href);
                });
            });

            if (window.location.hash !== '') {
                cfg_navigation.find('a[href="' + window.location.hash + '"]').tab('show') // Select tab by name
            }

        });
    </script>
@endsection