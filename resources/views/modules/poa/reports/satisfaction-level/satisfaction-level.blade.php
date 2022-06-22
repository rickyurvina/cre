@extends('layouts.admin')

@section('title', trans('poa.satisfaction_level'))

@push('css')
    <style>
        .subheader {
            margin-bottom: 8px !important;
        }
    </style>
@endpush

@section('subheader')
    <h1 class="subheader-title">
        <i class="fal fa-table text-primary"></i> {{ __('poa.satisfaction_level') }}
    </h1>
    <a href="{{ route('poa.reports.satisfaction_level.export') }}" class="color-success-500"><span
                class="fas fa-file-excel fa-2x"></span> {{ trans('general.excel') }}</a>
@endsection

@section('content')
    <div>
        <div class="card">
            <table class=" m-0" class="border border-dark">
                <thead class="bg-primary-50" class="border border-dark">
                <tr class="border border-dark">
                    <th colspan="{{$quantityYears}}"
                        class="border border-dark text-center">{{trans('general.satisfaction_level_title')}}</th>
                </tr>
                <tr class="border border-dark">
                    <th class="border border-dark text-center"
                        style="width: 25%">{{trans('general.satisfaction_level_objective')}}</th>
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
                            {{number_format($item['progress']*100,2)}}%
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
                        class="border border-dark text-center">{{trans('general.satisfaction_level_subtitle')}}</th>
                </tr>
                <tr class="border border-dark">
                    <th class="border border-dark text-center"
                        style="width: 25%">{{trans('general.satisfaction_level_specific_objective')}}</th>
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
                            {{number_format($item['progress']*100,2)}}%
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <br><br>

        </div>
    </div>
@endsection