@extends('layouts.admin')

@section('title', trans('poa.activity_status'))

@section('subheader')
    <h1 class="subheader-title">
        <i class="fal fa-table text-primary"></i> {{ __('poa.activity_status') }}
    </h1>
    <a href="{{ route('poa.reports.activity_status.export') }}" class="color-success-500"><span
                class="fas fa-file-excel fa-2x"></span> {{ trans('general.excel') }}</a>
@endsection

@section('content')
    <div class="card">
        <table class="border border-dark m-0">
            <thead class="border border-dark bg-primary-50">
            <tr class="border border-dark">
                <th class="border border-dark text-center" style="width: 20%">{{ strtoupper(__('poa.program')) }}</th>
                <th class="border border-dark text-center"
                    style="width: 30%">{{ strtoupper(trans_choice('poa.indicator', 2)) }}</th>
                <th class="border border-dark text-center"
                    style="width: 30%">{{ strtoupper(trans_choice('poa.activity', 2)) }}</th>
                <th class="border border-dark text-center" style="width: 10%">{{ strtoupper(__('poa.responsible')) }}</th>
                <th class="border border-dark text-center" style="width: 10%">{{ strtoupper(__('poa.status')) }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $item)
                <tr class="border border-dark">
                    <td class="border border-dark">{{ $item['programName'] }}</td>
                    <td class="border border-dark">{{ $item['indicator'] }}</td>
                    <td class="border border-dark">{{ $item['activity'] }}</td>
                    <td class="border border-dark">{{ $item['responsible'] }}</td>
                    <td class="border border-dark"
                        @if($item['status'] == \App\Models\Poa\PoaActivity::STATUS_FINISHED)
                        style="background-color: lightgreen;"
                            @endif>
                        {{ $item['status'] }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection