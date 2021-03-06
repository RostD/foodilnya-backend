<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 16.04.2017
 * Time: 7:33
 */
?>
@extends('control.layout.main')

@section('title','Конфигуратор блюда ('.$dish->name.')')

@section('content')
    @extends('control.layout.menu')

    <h3 style="margin-left:10px;">[{{$dish->id}}]
        {{$dish->name}}
        <img src="{{asset("imgs/icons/shock/edit.png")}}"
             onclick="openPopupWindow('{{url('/ctrl/nmcl/dish/'.$dish->id.'/edit')}}','Редактирование блюда',600,450)"
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
            <a class="nav-link" data-toggle="tab" href="#ingredients" role="tab">Ингредиенты</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#adaptations" role="tab">Приспособления</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#recipe" role="tab">Рецепт</a>
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
                    <td>{{$dish->unitName}}</td>
                </tr>
            </table>
        </div>

        <div class="tab-pane" id="ingredients" role="tabpanel">

            <button type="submit" class="btn btn-primary btn-sm pointer"
                    style="margin:10px 0px 10px 10px"
                    onclick="openPopupWindow('{{url('/ctrl/nmcl/cfg/dish/'.$dish->id.'/addIngredient')}}','Добавить ингредиент',600,500)">
                Добавить
            </button>

            <table class="table table-sm">
                <tr>
                    <th>Код</th>
                    <th>Наименование</th>
                    <th>Количество</th>
                    <th>Единица измерения</th>
                    <th>Действия</th>
                </tr>
                @foreach($dish->getIngredients(true) as $ingredient)
                    <tr style="{{$ingredient->trashed() ? 'text-decoration:line-through;background-color:#FBEFEF;':''}}">
                        <td>{{$ingredient->id}}</td>
                        <td>{{$ingredient->name}}</td>
                        <td>{{$ingredient->quantity}}</td>
                        <td>{{$ingredient->unitName}}</td>
                        <td>
                            <img src="{{asset("imgs/icons/shock/edit.png")}}"
                                 onclick="openPopupWindow('{{url('/ctrl/nmcl/cfg/dish/'.$dish->id.'/ingredient/'.$ingredient->id.'')}}','Редактирование состава блюда',600,300)"
                                 class="pointer"
                                 width="20"
                                 height="20"
                            >
                            <img src="{{asset("imgs/icons/shock/trash_can.png")}}"
                                 onclick="removeIngredient('{{$ingredient->id}}','{{$ingredient->name}}')"
                                 class="pointer"
                                 width="20"
                                 height="20"
                            >
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="tab-pane" id="adaptations" role="tabpanel">
            <button type="submit" class="btn btn-primary btn-sm pointer"
                    style="margin:10px 0px 10px 10px"
                    onclick="openPopupWindow('{{url('/ctrl/nmcl/cfg/dish/'.$dish->id.'/addAdaptation')}}','Добавить приспособление',600,500)">
                Добавить
            </button>

            <table class="table table-sm">
                <tr>
                    <th>Код</th>
                    <th>Наименование</th>
                    <th>Количество</th>
                    <th>Единица измерения</th>
                    <th>Действия</th>
                </tr>
                @foreach($dish->getAdaptations(true) as $adaptation)
                    <tr style="{{$adaptation->trashed() ? 'text-decoration:line-through;background-color:#FBEFEF;':''}}">
                        <td>{{$adaptation->id}}</td>
                        <td>{{$adaptation->name}}</td>
                        <td>{{$adaptation->quantity}}</td>
                        <td>{{$adaptation->unitName}}</td>
                        <td>
                            <img src="{{asset("imgs/icons/shock/edit.png")}}"
                                 onclick="openPopupWindow('{{url('/ctrl/nmcl/cfg/dish/'.$dish->id.'/adaptation/'.$adaptation->id.'')}}','Редактирование состава блюда',600,200)"
                                 class="pointer"
                                 width="20"
                                 height="20"
                            >
                            <img src="{{asset("imgs/icons/shock/trash_can.png")}}"
                                 onclick="removeAdaptation('{{$adaptation->id}}','{{$adaptation->name}}')"
                                 class="pointer"
                                 width="20"
                                 height="20"
                            >
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="tab-pane" id="recipe" role="tabpanel">
            <form method="POST" id="editRecipe" action="{{url('ctrl/nmcl/cfg/dish/'.$dish->id.'/recipe')}}">
                {{ csrf_field() }}
                <textarea id="recipe-txt" name="recipe">{{$dish->description}}</textarea>
            </form>
            <button type="submit" class="btn btn-primary btn-sm pointer"
                    style="margin:10px 0px 10px 10px"
                    onclick="pushRecipe()">
                Сохранить
            </button>

        </div>

        <div class="tab-pane" id="properties" role="tabpanel">
            <button type="submit" class="btn btn-primary btn-sm pointer"
                    style="margin:10px 0px 10px 10px"
                    onclick="openPopupWindow('{{url('/ctrl/nmcl/cfg/dish/'.$dish->id.'/addAttribute')}}','Добавить свойство',600,500)">
                Добавить
            </button>
            <table class="table">
                <tr>
                    <th>Наименование</th>
                    <th>Значение</th>
                    <th>Действия</th>
                </tr>
                @foreach($dish->getProperties(true) as $property)
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
                    onclick="openPopupWindow('{{url('/ctrl/nmcl/dish/'.$dish->id.'/edit')}}','Редактирование блюда',600,450)">
                Редактировать
            </button>
            <div style="margin:10px;">
                @foreach($dish->tags as $tag)
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

        function removeAttr(id, name) {
            var resp = confirm("Стереть свойство \"" + name + "\"?");

            if (resp) {
                $.ajax({
                    url: '{{url('/ctrl/nmcl/cfg/dish')}}/{{$dish->id}}/attribute/' + id,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: 'DELETE',
                    success: function (data) {
                        window.location.reload();
                    },
                    error: function (data) {
                        alert("Ошибка");
                        $('#error').html(data.responseText);
                    }


                });
            } else {
                return false;
            }
        }

        function setHash(urlString) {

            var from = urlString.search('#');
            var to = urlString.length;
            var hash = urlString.substring(from, to);

            window.location.hash = hash;
        }

        function removeIngredient(id, name) {
            var resp = confirm("Убрать ингредиент \"" + name + "\"?");

            if (resp) {
                $.ajax({
                    url: '{{url('/ctrl/nmcl/cfg/dish')}}/{{$dish->id}}/ingredient/' + id,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: 'DELETE',
                    success: function (data) {
                        window.location.reload();
                    },
                    error: function (data) {
                        alert("Ошибка");
                        $('#error').html(data.responseText);
                    }


                });
            } else {
                return false;
            }
        }

        function removeAdaptation(id, name) {
            var resp = confirm("Убрать приспособление \"" + name + "\"?");

            if (resp) {
                $.ajax({
                    url: '{{url('/ctrl/nmcl/cfg/dish')}}/{{$dish->id}}/adaptation/' + id,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: 'DELETE',
                    success: function (data) {
                        window.location.reload();
                    },
                    error: function (data) {
                        alert("Ошибка");
                        $('#error').html(data.responseText);
                    }


                });
            } else {
                return false;
            }
        }

        function pushRecipe() {
            $('#editRecipe').submit();
        }

        $(function () {
            CKEDITOR.replace('recipe-txt');

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