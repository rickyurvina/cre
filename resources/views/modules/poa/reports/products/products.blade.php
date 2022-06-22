@extends('layouts.admin')

@section('title', trans('poa.products'))

@push('css')
    <style>
        .subheader {
            margin-bottom: 8px !important;
        }
    </style>
@endpush

@section('subheader')
    <h1 class="subheader-title">
        <i class="fal fa-table text-primary"></i> {{ __('poa.products') }}
    </h1>
    <a href="{{ route('poa.reports.products.export') }}" class="color-success-500"><span
                class="fas fa-file-excel fa-2x"></span> {{ trans('general.excel') }}</a>
@endsection

@section('content')
    <div>
        <div class="card">
            <table class=" m-0" class="border border-dark">
                <thead class="bg-primary-50" class="border border-dark">
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
                <thead class="bg-primary-50" class="border border-dark">
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
                <tr class="border border-dark">
                    @foreach($data['detail'] as $item)
                        <td class="border border-dark text-center">
                            {{$item['name']}}
                        </td>
                        <td class="border border-dark text-center">
                            {{$item['documentProgress']}}
                        </td>
                    @endforeach
                </tr>
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