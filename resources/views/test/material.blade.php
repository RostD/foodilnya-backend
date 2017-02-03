<style>
    .center {
        text-align: center;
    }

    .bold {
        font-weight: bold;
    }
</style>
@if(!$material)
    <h2>Мтериальная ценность не найдена</h2>
@else
    <h2>Материальная ценность №{{$material->id}}</h2>
    <table>
        <tr>
            <td>Наименование</td>
            <td>{{$material->name}}</td>
        </tr>

        <tr>
            <td>Тип</td>
            <td>{{$material->typeName}}</td>
        </tr>

        @if($material->type == 1 )
            <tr>
                <td>Конкретика</td>
                <td>
                    @if(!empty($material->specifics))
                        <ul>
                            @foreach($material->specifics as $specific)
                                <li><a href="{{url("/material/".$specific->id)}}">{{$specific->name}}</a></li>
                            @endforeach
                        </ul>
                    @else
                        Нет
                    @endif
                </td>
            </tr>
        @endif

        @if($material->type == 2 )
            <tr>
                <td>Абстракция</td>
                <td>
                    @if($material->abstraction)
                        <li>
                            <a href="{{url("/material/".$material->abstraction->id)}}">{{$material->abstraction->name}}</a>
                        </li>
                    @else
                        Нет
                    @endif
                </td>
            </tr>
        @endif

        <tr>
            <td>Единица измерения</td>
            <td>{{$material->unitName}}</td>
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
    </table>
@endif