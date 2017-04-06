<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 04.04.2017
 * Time: 13:13
 */
?>

@extends('control.layout.main')

@section('title','Атрибуты')

@section('content')
    @extends('control.layout.menu')

    <button type="submit" class="btn btn-primary btn-sm pointer"
            style="margin:10px 10px 10px 10px"
            onclick="openPopupWindow('{{url('ctrl/sys/attribute/add')}}','Добавить новый атрибут',600,500)">Добавить
    </button>
    <button type="submit" class="btn btn-primary btn-sm pointer"
            style="margin:10px 10px 10px 0px"
            onclick="window.location.reload()">Обновить
    </button>
    <table class="table">
        <tr>
            <th>Наименование</th>
            <th>Тип</th>
            <th>Единица измерения</th>
            <th>Возможные значения</th>
            <th>Фиксированные значения</th>
            <th>Действия</th>
        </tr>
        @foreach($properties as $property)
            <tr style="{{$property->trashed() ? 'text-decoration:line-through;background-color:#FBEFEF;':''}}">
                <td>{{$property->name}}</td>
                <td>
                    @if($property->typeName)
                        {{$property->typeName}}
                    @else
                        --
                    @endif
                </td>
                <td>{{$property->unitName}}</td>
                <td>
                    @foreach($property->possibleValues as $possibleValue)
                        {{$possibleValue->value}}@if(!$loop->last),@endif
                    @endforeach

                </td>
                <td>@if($property->isFixedValue())
                        Да
                    @else
                        Нет
                    @endif
                </td>
                <td>
                    <img src="{{asset("imgs/icons/shock/edit.png")}}"
                         onclick="openPopupWindow('{{url('/ctrl/sys/attribute/'.$property->id)}}','Редактирование атрибута',600,320)"
                         class="pointer"
                         width="20"
                         height="20"
                    >
                    <img src="{{asset("imgs/icons/shock/trash_can.png")}}"
                         onclick="destroyAttr('{{$property->id}}','{{$property->name}}')"
                         class="pointer"
                         width="20"
                         height="20"
                    >
                </td>
            </tr>


        @endforeach
    </table>
    <div id="error"></div>
@endsection

@section('script')
    <script>
        function destroyAttr(id, name) {
            var resp = confirm("Удалить атрибут \"" + name + "\"?");

            if (resp) {
                $.ajax({
                    url: '{{url('/ctrl/sys/attribute')}}/' + id,
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
    </script>
@endsection

