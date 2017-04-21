<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 21.04.2017
 * Time: 11:48
 */
?>
@extends('control.layout.main')

@section('content')
    <input type="button" style="margin:10px;" onclick="closeWindow()" class="btn btn-warning pointer" value="Закрыть">
@endsection

@section('script')
    <script>
        function closeWindow() {
            window.opener.location.reload();
            window.close();
        }
    </script>
@endsection
