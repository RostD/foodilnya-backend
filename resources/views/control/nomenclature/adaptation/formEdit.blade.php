<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 08.04.2017
 * Time: 19:42
 */
?>

@extends('control.layout.main')

@section('title','Редактирование приспособления')

@section('content')

    <form id="formProp" style="margin: 10px;" method="POST" action="{{url('ctrl/nmcl/adaptation/'.$adaptation->id)}}">
        <input type="hidden" name="_method" value="PUT">
        {{ csrf_field() }}
        <div class="form-group {{$errors->first('name') ? 'has-danger' : ''}}">
            <label for="att-name">Наименование</label>
            <input type="text" autocomplete="off"
                   class="form-control {{$errors->first('name') ? 'form-control-danger' : ''}}" name="name"
                   id="att-name" value="{{$adaptation->name}}"
                   placeholder="Введите наименование">
            @if($errors->first('name'))
                <div class="form-control-feedback">{{$errors->first('name')}}</div>@endif
        </div>

        <div class="form-group">
            <label for="tagsSelect">Теги:</label>
            <select multiple class="form-control" id="tagsSelect" name="tags[]">
                @foreach($tags as $tag)

                    <option value="{{$tag->id}}"
                    @if($tag)
                        @foreach($adaptation->tags as $t)
                            {{$t->id == $tag->id? 'selected':''}}
                                @endforeach
                            @endif
                    >{{mb_substr($tag->name,1)}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="exampleTextarea">Добавить новые теги (через запятую)</label>
            <textarea class="form-control" id="exampleTextarea" name="newTags"
                      rows="3"></textarea>
        </div>

        <input type="button" onclick="closeWindow()" class="btn btn-warning pointer" value="Закрыть">
        <button type="submit" class="btn btn-primary pointer">Сохранить</button>
    </form>

@endsection

@section('script')
    <script>

        function closeWindow() {
            window.opener.location.reload();
            window.close();
        }
    </script>
@endsection