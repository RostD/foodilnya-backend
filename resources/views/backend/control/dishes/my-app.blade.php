<modal></modal>
@include('backend.control.includes.top')

<h1 class="page-header">Блюда</h1>
<dishes [dishes]="dishes" (OnClickDish)="loadDishWindow($event)">Загрузка блюд...</dishes>

@include('backend.control.includes.bot')

