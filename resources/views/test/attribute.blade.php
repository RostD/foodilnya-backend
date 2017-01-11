@if(!$attribute)
    <h2>Аттрибут не найден</h2>
@else
    <h2>{{$attribute->name}} (id:{{$attribute->id}})</h2>
    <ol>
        <li><b>Для материалов:</b>
            @if($attribute->type){{$attribute->typeName}}@elseвсех@endif</li>

        <li><b>Фиксированные значения:</b>
            @if($attribute->fixedValue)да@elseнет@endif</li>

        @if($attribute->unit)
            <li><b>Единица измерения:</b> {{$attribute->unitName}}</li>
        @endif

        @if($attribute->possibleValues)
            <li><b>Возможные значения:</b>
                <ul>
                    @foreach($attribute->possibleValues as $value)
                        <li>{{$value->getValue()}}</li>
                    @endforeach
                </ul>
            </li>
        @endif
    </ol>

@endif
