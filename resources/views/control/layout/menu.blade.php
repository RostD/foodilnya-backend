<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 04.04.2017
 * Time: 9:24
 */
?>
<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
            data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <h1 class="navbar-brand"><i><b>Food</b></i>'ильня</h1>
    <div class="collapse navbar-collapse justify-content-center" id="navbarNavDropdown">
        <ul class="navbar-nav ">

            @if( Gate::check('wh-base-seeBalance') || Gate::check('order-see') )
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        Склад
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        @can('wh-base-seeBalance')
                            <a class="dropdown-item" href="{{url('/ctrl/warehouse/base/balance')}}">Остатки</a>
                        @endcan

                        <a class="dropdown-item" href="{{url('/ctrl/warehouse/base/plannedCosts')}}">Планируемые
                            расходы</a>

                        @can('order-see')
                            <a class="dropdown-item"
                               href="{{url('/ctrl/warehouse/ordersForPicking').'?day='.date('Y-m-d')}}">Заказы на
                                комплектацию</a>
                        @endcan
                    </div>
                </li>
            @endif

            @if( Gate::check('order-see') || Gate::check('client-see') )
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false">
                        Заказы
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        @can('client-see')<a class="dropdown-item"
                                             href="{{url('/ctrl/order/clients')}}">Клиенты</a>@endcan
                        @can('order-see')<a class="dropdown-item" href="{{url('/ctrl/order/orders')}}">Заказы</a>@endcan
                    </div>
                </li>
            @endif

            @can('supplier_info-see')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        Закупка
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#">...</a>
                    </div>
                </li>
            @endcan

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    Номенклатура
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{url('/ctrl/nmcl/dishes')}}">Блюда</a>
                    <a class="dropdown-item" href="{{url('/ctrl/nmcl/ingredients')}}">Ингредиенты</a>
                    <a class="dropdown-item" href="{{url('/ctrl/nmcl/products')}}">Товары</a>
                    <a class="dropdown-item" href="{{url('/ctrl/nmcl/adaptations')}}">Приспособления</a>
                </div>
            </li>

            @can('hidden_info-see')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        Система
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="{{url('ctrl/sys/directories')}}">Справочники</a>
                    </div>
                </li>
            @endcan

            <li class="nav-item">
                <a class="nav-link" href="#">Справка</a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" style="color: #c27c77;" href="#" id="navbarDropdownMenuLink"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{Auth::user()->name}}
                </a>
                <div class="dropdown-menu " aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{ url('/logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Выйти
                    </a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                          style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>

