<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 19.04.2017
 * Time: 15:02
 */
?>

@extends('control.layout.main')

@section('title','Товары')

@section('content')
    @extends('control.layout.menu')

    @can('product-add')
        <button type="submit" class="btn btn-primary btn-sm pointer"
                style="margin:10px 0px 10px 10px"
                onclick="openPopupWindow('{{url('ctrl/nmcl/product/add')}}','Добавить новый товар',600,500)">Добавить
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
            <a href="{{url('/ctrl/nmcl/products')}}" class="btn btn-danger  pointer">Сбросить фильтр</a>
        </form>


    </div>

    <table class="table table-sm">
        <tr>
            <th>Код</th>
            <th>Наименование</th>
            <th>Свойства</th>

            <th>Теги</th>
            <th>Действия</th>
        </tr>
        @foreach($products as $product)
            <tr style="{{$product->trashed() ? 'text-decoration:line-through;background-color:#FBEFEF;':''}}">
                <td>{{$product->id}}</td>
                <td>{{$product->name}}</td>
                <td>
                    @foreach($product->properties as $property)
                        <span class="badge badge-default">{{$property->name}} - {{$property->value}}
                            ({{$property->unit}})</span>
                    @endforeach
                </td>
                <td>
                    @foreach($product->tags as $tag)
                        <span class="badge badge-info">{{$tag->name}}</span>
                    @endforeach
                </td>
                <td>
                    @can('product-edit')
                        <img src="{{asset("imgs/icons/shock/edit.png")}}"
                             onclick="openPopupWindow('{{url('/ctrl/nmcl/product/'.$product->id.'/edit')}}','Редактирование товара',600,450)"
                             class="pointer"
                             width="20"
                             height="20"
                        >
                    @endcan

                    @can('product-delete')
                        <img src="{{asset("imgs/icons/shock/trash_can.png")}}"
                             onclick="destroyProduct('{{$product->id}}','{{$product->name}}')"
                             class="pointer"
                             width="20"
                             height="20"
                        >
                    @endcan

                    @can('product-edit')
                        <a href="{{url('/ctrl/nmcl/cfg/dish').'/'.$product->id}}" target="_blank">
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
        function destroyProduct(id, name) {
            var resp = confirm("Удалить товар \"" + name + "\"?");

            if (resp) {
                $.ajax({
                    url: '{{url('/ctrl/nmcl/product')}}/' + id,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: 'DELETE',
                    success: function (data) {
                        window.location.reload();
                    },
                    error: function (data) {
                        $('#error').html = data.responseText;
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



