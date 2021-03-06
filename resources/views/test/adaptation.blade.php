<style>
    .center {
        text-align: center;
    }

    .bold {
        font-weight: bold;
    }
</style>
@if(!$material)
    <h2>Приспособление не найдено</h2>
@else
    <h2>{{$material->typeName}} №{{$material->id}}</h2>
    <table>
        <tr>
            <td>Наименование</td>
            <td>{{$material->name}}</td>
        </tr>

        <tr>
            <td>Тип</td>
            <td>{{$material->typeName}}</td>
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
                    <td>{{$properties->value}} @if($properties->unitId){{$properties->unitName}}@endif</td>
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
            <td class="center bold">Блюда</td>
        </tr>
        <tr>
            <td>
                <ul>
                    @foreach($material->dishes as $dish)
                        <li><a href="{{url("/dish/".$dish->id)}}">{{$dish->name}}</a></li>
                    @endforeach
                </ul>
            </td>
        </tr>
    </table>
@endif