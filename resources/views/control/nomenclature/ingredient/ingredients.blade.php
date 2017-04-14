<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 08.04.2017
 * Time: 11:43
 */
?>

@extends('control.layout.main')

@section('title','Ингредиенты')

@section('content')
    @extends('control.layout.menu')

    @can('ingredient-add')
        <button type="submit" class="btn btn-primary btn-sm pointer"
                style="margin:10px 0px 10px 10px"
                onclick="openPopupWindow('{{url('/ctrl/nmcl/ingredient/add')}}','Добавить ингредиент',600,500)">Добавить
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
                           placeholder="Наименование содержит">
                </div>
            </div>

            <div class="form-group">
                <label for="tagsSelect">Теги:</label>
                <select multiple class="form-control" id="tagsSelect" name="tags[]">
                    @foreach($tags as $tag)

                        <option value="{{$tag->id}}"
                        @if(app('request')->input('tags'))
                            @foreach(app('request')->input('tags') as $t)
                                {{$t==$tag->id? 'selected':''}}
                                    @endforeach
                                @endif
                        >{{mb_substr($tag->name,1)}}</option>

                    @endforeach
                </select>
            </div>
            <input type="submit" class="btn btn-success pointer" value="Применить фильтр">
            <a href="{{url('/ctrl/nmcl/ingredients')}}" class="btn btn-danger  pointer">Сбросить фильтр</a>
        </form>


    </div>

    <table class="table">
        <tr>
            <th>Код</th>
            <th>Наименование</th>
            <th>Единица измерения</th>
            <th>Свойства</th>
            <th>Теги</th>
            <th>Действия</th>
        </tr>
        @foreach($ingredients as $ingredient)
            <tr style="{{$ingredient->trashed() ? 'text-decoration:line-through;background-color:#FBEFEF;':''}}">
                <td>{{$ingredient->id}}</td>
                <td>{{$ingredient->name}}</td>
                <td>{{$ingredient->unitName}}</td>
                <td>
                    @foreach($ingredient->properties as $property)
                        <span class="badge badge-default">{{$property->name}} - {{$property->value}}
                            ({{$property->unit}})</span>
                    @endforeach
                </td>
                <td>
                    @foreach($ingredient->tags as $tag)
                        <span class="badge badge-info">{{$tag->name}}</span>
                    @endforeach
                </td>
                <td>
                    @can('ingredient-edit')
                        <img src="{{asset("imgs/icons/shock/edit.png")}}"
                             onclick="openPopupWindow('{{url('/ctrl/nmcl/dish/'.$ingredient->id.'/edit')}}','Редактирование ингредиента',600,450)"
                             class="pointer"
                             width="20"
                             height="20"
                        >
                    @endcan

                    @can('ingredient-delete')
                        <img src="{{asset("imgs/icons/shock/trash_can.png")}}"
                             onclick="destroyDish('{{$ingredient->id}}','{{$ingredient->name}}')"
                             class="pointer"
                             width="20"
                             height="20"
                        >
                    @endcan

                    @can('ingredient-edit')
                        <img src="{{asset("imgs/icons/shock/technical_wrench.png")}}"
                             onclick="openPopupWindow('{{url('#')}}','Конструктор ингредиента',600,450)"
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
        function destroyDish(id, name) {
            var resp = confirm("Удалить ингредиент \"" + name + "\"?");

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


