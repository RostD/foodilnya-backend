<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 06.04.2017
 * Time: 10:47
 */
?>

@extends('control.layout.main')

@section('title','Единицы измерения')

@section('content')
    @extends('control.layout.menu')

    <button type="submit" class="btn btn-primary btn-sm pointer"
            style="margin:10px 10px 10px 10px"
            onclick="openPopupWindow('{{url('ctrl/sys/unit/add')}}','Добавить новую единицу измерения',600,250)">
        Добавить
    </button>
    <button type="submit" class="btn btn-primary btn-sm pointer"
            style="margin:10px 10px 10px 0px"
            onclick="window.location.reload()">Обновить
    </button>
    <table class="table">
        <tr>
            <th>Наименование</th>
            <th>Сокращённо</th>
            <th>Действия</th>
        </tr>
        @foreach($units as $unit)
            <tr style="{{$unit->trashed()? 'text-decoration:line-through;background-color:#FBEFEF;':''}}">
                <td>{{$unit->fullName}}</td>
                <td>{{$unit->name}}</td>
                <td>
                    <img src="{{asset("imgs/icons/shock/edit.png")}}"
                         onclick="openPopupWindow('{{url('/ctrl/sys/unit/'.$unit->id.'/edit')}}','Редактирование единицы измерения',600,320)"
                         class="pointer"
                         width="20"
                         height="20"
                    >
                    <img src="{{asset("imgs/icons/shock/trash_can.png")}}"
                         onclick="destroyUnit('{{$unit->id}}','{{$unit->fullName}}')"
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
        function destroyUnit(id, name) {
            var resp = confirm("Удалить единицу измерения \"" + name + "\"?");

            if (resp) {
                $.ajax({
                    url: '{{url('/ctrl/sys/unit')}}/' + id,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: 'DELETE',
                    success: function (data) {
                        window.location.reload();
                    },
                    error: function (data) {
                        $('#error').html(data.responseText);
                        alert("Ошибка");
                    }
                });
            } else {
                return false;
            }
        }
    </script>
@endsection


