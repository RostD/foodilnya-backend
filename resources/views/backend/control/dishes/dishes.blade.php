@extends('backend.control.includes.master')

@section('head')
    <style>
        .my-remove-border {
            border: none;
        }

        /* Выравнивание модального окна по центру */
        .modal {
            text-align: center;
        }

        @media screen and (min-width: 768px) {
            .modal:before {
                display: inline-block;
                vertical-align: middle;
                content: " ";
                height: 100%;
            }
        }

        .modal-dialog {
            display: inline-block;
            text-align: left;
            vertical-align: middle;
        }

        /* -------------------------- */
    </style>
@endsection

@section('title')Блюда@endsection


<my-app>Загружаю страницу...</my-app>



@section('scripts')
    @include('backend.libs.angular')
    <script src="{{ url('js/ngcfg/control/dishes.js') }}"></script>
    <script>
        System.import('app').catch(function (err) {
            console.error(err);
        });
    </script>
@endsection




