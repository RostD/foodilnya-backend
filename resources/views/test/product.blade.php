<style>
    .center {
        text-align: center;
    }

    .bold {
        font-weight: bold;
    }
</style>
@if(!$material)
    <h2>Товар не найден</h2>
@else
    <h2>{{$material->typeName}} №{{$material->id}}</h2>
    <table>
        <tr>
            <td>Наименование</td>
            <td>{{$material->name}}</td>
        </tr>

        <tr>
            <td>Единица измерения</td>
            <td>{{$material->unitShortName}}</td>
        </tr>


        @if(count($material->properties) > 0)
            <tr>
                <td colspan="2" class="center bold">Свойства</td>
            </tr>
            @foreach($material->properties as $properties)
                <tr>
                    <td>{{$properties->name}}</td>
                    <td>{{$properties->value}} @if($properties->unitId){{$properties->getUnitName('short')}}@endif</td>
                </tr>
            @endforeach
        @endif

        @if(count($material->tags) > 0)
            <tr>
                <td colspan="2" class="center bold">Теги</td>
            </tr>
            @foreach($material->tags as $tag)
                <tr>
                    <td>{{$tag->name}}</td>
                </tr>
            @endforeach
        @endif

        <tr>
            <td class="center bold">Ингредиент:</td>
        </tr>
        <tr>
            <td>

                @if($material->ingredient)
                    <a href="{{url("/ingredient/".$material->ingredient->id)}}">{{$material->ingredient->name}}</a>
                @endif

            </td>
        </tr>

    </table>
@endif