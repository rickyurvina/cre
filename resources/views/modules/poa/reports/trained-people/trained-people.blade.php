@extends('layouts.admin')

@section('title', trans('poa.trained_people'))

@push('css')
    <style>
        .subheader {
            margin-bottom: 8px !important;
        }
    </style>
@endpush

@section('subheader')
    <h1 class="subheader-title">
        <i class="fal fa-table text-primary"></i> {{ __('poa.trained_people') }}
    </h1>
    <a href="{{ route('poa.reports.trained_people.export') }}" class="color-success-500"><span
                class="fas fa-file-excel fa-2x"></span> {{ trans('general.excel') }}</a>
@endsection

@section('content')
    <div>
        <div class="card">
            <table class=" m-0" class="border border-dark">
                <thead class="bg-primary-50" class="border border-dark">
                <tr class="border border-dark">
                    <th colspan="{{$quantityYears}}"
                        class="border border-dark text-center">{{trans('general.trained_people_title')}}</th>
                </tr>
                <tr class="border border-dark">
                    <th rowspan="2" class="border border-dark text-center"
                        style="width: 25%">{{trans('general.trained_people_objective')}}</th>
                    @foreach($poas as $poa)
                        <th colspan="2" class="border border-dark text-center">{{$poa->year}}</th>
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
                <thead class="bg-primary-50" class="border border-dark">
                <tr class="border border-dark">
                    <th colspan="{{$quantityYears}}"
                        class="border border-dark text-center">{{trans('general.trained_people_subtitle')}}</th>
                </tr>
                <tr class="border border-dark">
                    <th rowspan="2" class="border border-dark text-center"
                        style="width: 25%">{{trans('general.trained_people_specific_objective')}}</th>
                    @foreach($poas as $poa)
                        <th colspan="2" class="border border-dark text-center">{{$poa->year}}</th>
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
            <br><br>

        </div>
    </div>
@endsection

@push('page_script')
    <script>

    </script>
@endpush