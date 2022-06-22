@extends('modules.budget.expenses.expenses')
@section('title', trans_choice('budget.expense',1))

@section('breadcrumb')
    <ol class="breadcrumb bg-transparent pl-0 pr-0 mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('budgets.index') }}">
                Presupuestos
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('budgets.show',$transaction->id) }}">
                Presupuesto {{$transaction->year}}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('budgets.expenses',$transaction->id) }}">
                {{trans('budget.expense')}}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('budgets.expenses_poas',$transaction->id) }}">
                {{$activity->first()->program->poa->name}}
            </a>
        </li>

        <li class="breadcrumb-item active"> {{$activity->name}}</li>
    </ol>
@endsection
@section('expenses-page')
    <div class="d-flex flex-column mt-3">
        <x-label-section>
            <i class="fa fa-money-bill mr-2"></i> Partidas Presupuestarias / <small class="text-primary d-inline"> {{$activity->name}}</small>
        </x-label-section>
        <div class="section-divider"></div>
    </div>

    <div class="d-flex mt-2">
        <div class="w-50">
            <table class="table table-bordered detail-table">
                <tbody>
                <tr>
                    <td class="fs-1x fw-700 w-20">Ejercicio</td>
                    <td colspan="2">
                        {{$transaction->year}}</td>
                </tr>
                <tr>
                    <td class="fs-1x fw-700">Poa</td>
                    <td class="w-5">
                        {{$activity->program->poa->year}}
                    </td>
                    <td class="fs-1x fw-700">
                        {{$activity->program->poa->name}}
                    </td>
                </tr>
                <tr>
                    <td class="fs-1x fw-700">{{trans_choice('general.plan',1)}}</td>
                    <td>
                        {{$activity->program->planDetail->plan->code}}
                    </td>
                    <td class="fs-1x fw-700">
                        {{$activity->program->planDetail->plan->name}}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="w-50">
            <table class="table table-bordered detail-table">
                <tbody>
                <tr>
                    <td class="fs-1x fw-700 w-20">{{$activity->program->planDetail->parent->parent->planRegistered->name}}
                    </td>
                    <td class="w-5">{{$activity->program->planDetail->parent->parent->code}}</td>
                    <td class="fs-1x fw-700">{{$activity->program->planDetail->parent->parent->name}}</td>
                </tr>
                <tr>
                    <td class="fs-1x fw-700 w-20">{{$activity->program->planDetail->parent->planRegistered->name}}
                    </td>
                    <td class="w-5">{{$activity->program->planDetail->parent->code}}</td>
                    <td class="fs-1x fw-700">{{$activity->program->planDetail->parent->name}}</td>
                </tr>
                <tr>
                    <td class="fs-1x fw-700">{{trans_choice('general.programs',1)}}</td>
                    <td>
                        {{$activity->program->planDetail->code}}
                    </td>
                    <td class="fs-1x fw-700">
                        {{$activity->program->planDetail->name}}
                    </td>
                </tr>
                <tr>
                    <td class="fs-1x fw-700 w-20">Indicador
                    </td>
                    <td class="w-5">{{$activity->indicator->code}}</td>
                    <td class="fs-1x fw-700">{{$activity->indicator->name}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="dropdown-divider"></div>
    <div class="d-flex flex-row mb-2 align-items-center justify-content-between">
        <a href="javascript:void(0);" class="btn btn-outline-success btn-sm ml-auto" data-toggle="modal" data-target="#budget-poa-expense-create"
           data-activity-id="{{$activity->id}}"
           class="btn btn-success btn-sm"><span class="fas fa-plus mr-1"></span>
            &nbsp;{{ trans('general.add_new') }}
        </a>
    </div>
    @if($expenses->count()>0)
        <div class="table-responsive">
            <table class="table table-light table-hover">
                <thead>
                <tr>
                    <th class="table-th w-20">@sortablelink('code', trans('general.item'))</th>
                    <th class="table-th w-30">@sortablelink('name', trans('general.name'))</th>
                    <th class="table-th w-30">@sortablelink('description', trans('general.description'))</th>
                    <th class="table-th w-10">@sortablelink('debit', trans('general.value'))</th>
                    <th class="table-th w-10"><a href="#">{{  trans('general.actions') }}</a></th>
                </tr>
                </thead>
                <tbody>
                @foreach($expenses as $item)
                    <tr class="tr-hover">
                        <td><span class="badge {{$item->is_new ? 'badge-warning' : '' }}  badge-pill fs-1x fw-700">{{ $item->code }}</span></td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->balanceDraft($transaction->status) }} </td>
                        <td>
                        </td>
                    </tr>
                @endforeach
                <tr style="background-color: #e0e0e0">
                    <td colspan="3"></td>
                    <td style="color: #008000" class="fs-2x fw-700">Total: {{ $total }}</td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
        <x-pagination :items="$expenses"/>

    @else
        <x-empty-content>
            <x-slot name="img">
                <i class="fas fa-money-bill-wave" style="color: #2582fd;"></i>
            </x-slot>
            <x-slot name="title">
                No existen partidas presupuestarias creadas
            </x-slot>
            <div class="d-flex flex-column">
                <a href="javascript:void(0);" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#budget-poa-expense-create"
                   data-activity-id="{{$activity->id}}"
                   class="btn btn-success btn-sm"><span class="fas fa-plus mr-1"></span>
                    &nbsp;{{ trans('general.add_new') }}
                </a>
            </div>
        </x-empty-content>
    @endif

    <div wire:ignore>
        <livewire:budget.expenses.poa.expense-poa-activity-create-budget :activityId="$activity->id" :transaction="$transaction"/>
    </div>
    <div wire:ignore>
        <livewire:budget.expenses.poa.expense-poa-activity-edit-budget :activityId="$activity->id" :transaction="$transaction"/>
    </div>
@endsection
@push('page_script')
    <script>
        $('#budget-poa-expense-edit').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let expenseId = $(e.relatedTarget).data('expense-id');
            //Livewire event trigger
            Livewire.emit('loadExpensePoa', expenseId);
        });

    </script>
@endpush