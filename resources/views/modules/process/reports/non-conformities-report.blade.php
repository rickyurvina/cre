@extends('layouts.admin')

@section('title',  trans('poa.card_reports') )

@section('subheader')
    <h1 class="subheader-title">
        <i class="fas fa-table text-primary"></i> <span class="fw-300">{{ trans('poa.card_reports') }}</span>
    </h1>
@endsection

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb bg-transparent pl-2 pr-0 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('process.reports') }}">
                    Informes
                </a>
            </li>

            <li class="breadcrumb-item active"> Informe de No Conformidades</li>
        </ol>
        <div style="display: grid !important;">
            <h2 style="padding: 4px" class="ml-auto">{{trans('general.cutoff_date')}}: {{now()->format('d-m-Y')}}</h2>
            <table class="border ex-e-table m-2">
                <tr class="border">
                    <th colspan="7" class="border text-center  font-weight-bold" style="background-color: #e1a8ce;height: 10px">{{trans('general.non_conformities_register')}}</th>
                </tr>
                <tr class="border">
                    <th class="text-center font-weight-bold p-2 w-50" style="background-color: #a0c9c0" rowspan="2">{{trans('general.process')}}</th>
                    <th class="text-center font-weight-bold p-2 w-20" style="background-color: #c99e80" rowspan="2">{{trans('general.non_conformities_number')}}</th>
                    <th class="text-center font-weight-bold p-2 w-20" style="background-color: #d7e14e" colspan="2">{{trans('general.non_conformities_treatment')}}</th>
                    <th class="text-center font-weight-bold p-2 w-20" style="background-color: #b0e155" colspan="3">{{trans('general.status')}}</th>
                </tr>
                <tr class="border">
                    <th class="text-center font-weight-bold p-2" style="background-color: #d7e14e">{{trans('general.sac')}}</th>
                    <th class="text-center font-weight-bold p-2" style="background-color: #d7e14e">{{trans('general.corrective_action')}}</th>
                    <th class="text-center font-weight-bold p-2" style="background-color: #b0e155">{{trans('general.closing_process')}}</th>
                    <th class="text-center font-weight-bold p-2" style="background-color: #b0e155">{{trans('general.open')}}</th>
                    <th class="text-center font-weight-bold p-2" style="background-color: #b0e155">{{trans('general.closed')}}</th>
                </tr>
                @foreach($processes as $process)
                    <tr class="border">
                        <td class="font-weight-bold" style="background-color: #a0c9c0">{{$process->name}}</td>
                        <td class="text-center" style="background-color: #c99e80">{{$process->nonConformities->count()}}</td>
                        <td class="text-center"
                            style="background-color: #d7e14e">{{$process->nonConformities->where('type',\App\Models\Process\NonConformities::TYPE_CORRECTIVE_ACTION)->count()}}</td>
                        <td class="text-center"
                            style="background-color: #d7e14e">{{$process->nonConformities->where('type',\App\Models\Process\NonConformities::TYPE_IMPROVEMENT_OPPORTUNITY)->count()}}</td>
                        <td class="text-center"
                            style="background-color: #b0e155">{{$process->nonConformities->where('state',\App\Models\Process\NonConformities::TYPE_WILL_CLOSED)->count()}}</td>
                        <td class="text-center"
                            style="background-color: #b0e155">{{$process->nonConformities->where('state',\App\Models\Process\NonConformities::TYPE_OPEN)->count()}}</td>
                        <td class="text-center"
                            style="background-color: #b0e155">{{$process->nonConformities->where('state',\App\Models\Process\NonConformities::TYPE_CLOSED)->count()}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection