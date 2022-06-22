<table class="border border-dark m-0">
    <thead class="border border-dark bg-primary-50">
    <tr class="border border-dark">
        <th colspan="{{ $quantityYears }}"
            class="border border-dark text-center">{{ trans('general.reached_people_title') }}</th>
    </tr>
    <tr class="border border-dark">
        <th rowspan="2" class="border border-dark text-center"
            style="width: 25%">{{ trans('general.reached_people_objective') }}</th>
        @foreach($poas as $poa)
            <th colspan="2" class="border border-dark text-center">{{ $poa->year }}</th>
        @endforeach
    </tr>
    <tr>
        @foreach($poas as $poa)
            <th class="border border-dark text-center">Hombres</th>
            <th class="border border-dark text-center">Mujeres</th>
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
                {{$item['progressMen']}}
            </td>
            <td class="border border-dark text-center">
                {{$item['progressWomen']}}
            </td>
        </tr>
    @endforeach

    </tbody>
</table>
<br>
<table>
    <thead class="border border-dark bg-primary-50">
    <tr class="border border-dark">
        <th colspan="{{ $quantityYears }}"
            class="border border-dark text-center">{{ trans('general.reached_people_subtitle') }}</th>
    </tr>
    <tr class="border border-dark">
        <th rowspan="2" class="border border-dark text-center"
            style="width: 25%">{{ trans('general.reached_people_specific_objective') }}</th>
        @foreach($poas as $poa)
            <th colspan="2" class="border border-dark text-center">{{ $poa->year }}</th>
        @endforeach
    </tr>
    <tr>
        @foreach($poas as $poa)
            <th class="border border-dark text-center">Hombres</th>
            <th class="border border-dark text-center">Mujeres</th>
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
                {{$item['progressMen']}}
            </td>
            <td class="border border-dark text-center">
                {{$item['progressWomen']}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
