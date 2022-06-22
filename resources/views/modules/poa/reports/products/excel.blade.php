<table class=" m-0 border border-dark">
    <thead class="bg-primary-50 border border-dark">
    <tr class="border border-dark">
        <th colspan="{{$quantityYears}}"
            class="border border-dark text-center">{{trans('general.products_title')}}</th>
    </tr>
    <tr class="border border-dark">
        <th class="border border-dark text-center"
            style="width: 25%">{{trans('general.products_objective')}}</th>
        @foreach($poas as $poa)
            <th class="border border-dark text-center">{{$poa->year}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($data['header'] as $item)
        <tr class="border border-dark">
            <td class="border border-dark text-center">
                {{$item['name']}}
            </td>
            <td class="border border-dark text-center">
                {{$item['documentProgress']}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<br>
<table>
    <thead class="bg-primary-50 border border-dark">
    <tr class="border border-dark">
        <th colspan="{{$quantityYears}}"
            class="border border-dark text-center">{{trans('general.products_subtitle')}}</th>
    </tr>
    <tr class="border border-dark">
        <th class="border border-dark text-center"
            style="width: 25%">{{trans('general.products_specific_objective')}}</th>
        @foreach($poas as $poa)
            <th class="border border-dark text-center">{{$poa->year}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($data['detail'] as $item)
        <tr class="border border-dark">
            <td class="border border-dark text-center">
                {{$item['name']}}
            </td>
            <td class="border border-dark text-center">
                {{$item['documentProgress']}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>