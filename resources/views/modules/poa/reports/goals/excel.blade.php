@php ($aux = "")
@php ($auxProgramName = "")
@if (count($data)==0)
    <table class="border border-dark m-0">
        <thead class="border border-dark">
        <tr class="border border-dark">
            <th colspan="39" class="border border-dark text-center">{{trans('general.goals_title')}}</th>
        </tr>
        </thead>
    </table>
@else
    @for($i=0;$i<count($data);$i++)
        @if ($aux!=$data[$i]['specificGoal'])
            <table class="border border-dark m-0">
                <thead class="border border-dark">
                <tr class="border border-dark">
                    <th colspan="39"
                        class="border border-dark text-center">{{trans('general.goals_title')}}</th>
                </tr>
                <tr class="border border-dark">
                    <th class="border border-dark w-20">Objetivo Específico</th>
                    <th colspan="2" class="border border-dark w-20">{{$data[$i]['specificGoal']}}</th>
                    @foreach (range(1, 12) as $month)
                        <th class="border border-dark text-center">
                            {{\App\Models\Indicators\Indicator\Indicator::FREQUENCIES[12][$month]}}</th>
                    @endforeach
                    <th class="border border-dark text-center">Meta</th>
                    @foreach (range(1, 12) as $month)
                        <th colspan="2" class="border border-dark text-center bg-success-50 w-5">
                            {{\App\Models\Indicators\Indicator\Indicator::FREQUENCIES[12][$month]}}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @endif
                @if ($auxProgramName!=$data[$i]['programName'])
                    <tr class="bg-gray-400 border border-dark">
                        <td colspan="2"
                            class="border border-dark font-weight-bold w-20">{{$data[$i]['programName']}}</td>
                        <td class="border border-dark font-weight-bold w-20">{{$data[$i]['indicatorName']}}</td>
                        @php ($meta = 0)
                        @foreach (range(1, 12) as $month)
                            @php ($meta = $meta + $dataTotal[$data[$i]['programId']][$month]['goal'])
                            <td class="border border-dark text-center font-weight-bold">
                                {{ $dataTotal[$data[$i]['programId']][$month]['goal'] != 0 ? $dataTotal[$data[$i]['programId']][$month]['goal']:'' }}
                            </td>
                        @endforeach
                        <td class="border border-dark text-center font-weight-bold">
                            {{$meta}}
                        </td>
                        @if ($data[$i]['menWomenSeparate'] == 'Si')
                            @foreach (range(1, 12) as $month)
                                <td class="border border-dark text-center font-weight-bold">
                                    {{ $dataTotal[$data[$i]['programId']][$month]['menProgress'] != 0 ? $dataTotal[$data[$i]['programId']][$month]['menProgress']:'' }}
                                </td>
                                <td class="border border-dark text-center font-weight-bold">
                                    {{ $dataTotal[$data[$i]['programId']][$month]['womenProgress'] != 0 ? $dataTotal[$data[$i]['programId']][$month]['womenProgress']:''}}
                                </td>
                            @endforeach
                        @else
                            @foreach (range(1, 12) as $month)
                                <td colspan="2" class="border border-dark text-center font-weight-bold">
                                    {{ $dataTotal[$data[$i]['programId']][$month]['progress'] != 0 ? $dataTotal[$data[$i]['programId']][$month]['progress']:'' }}
                                </td>
                            @endforeach
                        @endif
                    </tr>
                @endif
                @php($months = array("jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"))
                <tr class="border border-dark">
                    <td colspan="3"
                        class="border border-dark text-center">{{$data[$i]['activityIndicatorName']}}</td>
                    @php ($meta = 0)
                    @foreach ($months as $month)
                        @php ($meta = $meta + $data[$i][$month]['planned'])
                        <td class="border border-dark text-center">
                            {{ $data[$i][$month]['planned'] != 0 ? $data[$i][$month]['planned']:'' }}
                        </td>
                    @endforeach
                    <td class="border border-dark text-center font-weight-bold">
                        {{$meta}}
                    </td>
                    @if ($data[$i]['menWomenSeparate'] == 'Si')
                        @foreach ($months as $month)
                            <td class="border border-dark text-center">
                                {{ $data[$i][$month]['menProgress'] !=0 ? $data[$i][$month]['menProgress']:'' }}
                            </td>
                            <td class="border border-dark text-center">
                                {{ $data[$i][$month]['womenProgress'] !=0 ? $data[$i][$month]['womenProgress']:''}}
                            </td>
                        @endforeach
                    @else
                        @foreach ($months as $month)
                            <td colspan="2" class="border border-dark text-center">
                                {{ $data[$i][$month]['progress'] != 0 ? $data[$i][$month]['progress']:'' }}
                            </td>
                        @endforeach
                    @endif
                </tr>
                @php ($auxProgramName = $data[$i]['programName'])
                @php ($aux = $data[$i]['specificGoal'])
                @if ($i==count($data)-1)
                </tbody>
            </table>
            @else
            @if ($aux!=$data[$i+1]['specificGoal'])
            </tbody>
            </table>
        @endif
        @endif
    @endfor
@endif
