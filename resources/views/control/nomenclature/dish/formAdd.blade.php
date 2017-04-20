<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 08.04.2017
 * Time: 19:42
 */
?>

@extends('control.layout.main')

@section('title','Добавление блюда')

@section('content')

    <form id="formProp" style="margin: 10px;" method="POST">
        {{ csrf_field() }}
        <div class="form-group {{$errors->first('name') ? 'has-danger' : ''}}">
            <label for="att-name">Наименование</label>
            <input type="text" autocomplete="off"
                   class="form-control {{$errors->first('name') ? 'form-control-danger' : ''}}" name="name"
                   id="att-name" value="{{old('name')}}"
                   placeholder="Введите наименование">
            @if($errors->first('name'))
                <div class="form-control-feedback">{{$errors->first('name')}}</div>@endif
        </div>

        <!-- TODO преределать на чекбоксы -->
        <div class="form-group">
            <label for="tagsSelect">Теги:</label>
            <select multiple class="form-control" id="tagsSelect" name="tags[]">
                @foreach($tags as $tag)

                    <option value="{{$tag->id}}"
                    @if(old('tags'))
                        @foreach(old('tags') as $t)
                            {{$t==$tag->id? 'selected':''}}
                                @endforeach
                            @endif
                    >{{mb_substr($tag->name,1)}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="exampleTextarea">Добавить новые теги (через запятую)</label>
            <textarea class="form-control" id="exampleTextarea" name="newTags"
                      rows="3">{{old('newTags')}}</textarea>
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