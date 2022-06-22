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
            <a href="{{ route('budgets.expenses_projects',$transaction->id) }}">
                {{$activity->project->name}}
            </a>
        </li>

        <li class="breadcrumb-item active"> {{$activity->text}}</li>
    </ol>
@endsection
@section('expenses-page')
    <div>
        <div class="d-flex flex-column mt-3">
            <x-label-section>
                <i class="fa fa-money-bill mr-2"></i> Partidas Presupuestarias / <small
                        class="text-primary d-inline"> {{$activity->text}}</small>
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
                        <td class="fs-1x fw-700">Proyecto</td>
                        <td class="w-5">
                            {{$activity->project->code}}
                        </td>
                        <td class="fs-1x fw-700">
                            {{$activity->project->name}}
                        </td>
                    </tr>

                    <tr>
                        <td class="fs-1x fw-700">Junta Ejecutora</td>
                        <td>
                            {{$activity->company->id}}
                        </td>
                        <td class="fs-1x fw-700">
                            {{$activity->company->name}}
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-1x fw-700">Programa</td>
                        @if($activity->taskable_id)
                            <td>
                                {{$activity->taskable->service->lineAction->program->code}}
                            </td>
                            <td class="fs-1x fw-700">
                                {{$activity->taskable->service->lineAction->program->name}}
                            </td>
                        @endif
                    </tr>
                    <tr>
                        <td class="fs-1x fw-700 w-20">{{trans_choice('general.indicators',1)}}
                        </td>
                        <td class="w-5">{{$activity->indicator->code ?? ''}}</td>
                        <td class="fs-1x fw-700">{{$activity->indicator->name ?? 'Sin Indicador Asociado'}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-50">
                <table class="table table-bordered detail-table">
                    <tbody>
                    <tr>
                        <td class="fs-1x fw-700 w-20">{{trans('general.specific_objective')}}
                        </td>
                        <td class="w-5">{{$activity->parentOfTask->objective->code ?? ''}}</td>
                        <td class="fs-1x fw-700">{{$activity->parentOfTask->objective->name ?? ''}}</td>
                    </tr>
                    <tr>
                        <td class="fs-1x fw-700 w-20">{{trans('general.result')}}
                        </td>
                        <td class="w-5">{{$activity->parentOfTask->code}}</td>
                        <td class="fs-1x fw-700">{{$activity->parentOfTask->text}}</td>
                    </tr>
                    <tr>
                        @if($activity->taskable_id)
                            <td class="fs-1x fw-700">{{trans('general.service')}}</td>
                            <td>
                                {{$activity->taskable->service->code}}
                            </td>
                            <td class="fs-1x fw-700">
                                {{$activity->taskable->service->name}}
                            </td>
                        @endif

                    </tr>
                    <tr>
                        @if($activity->taskable_id)

                            <td class="fs-1x fw-700 w-20">{{trans('general.lines_action')}}
                            </td>
                            <td>
                                {{$activity->taskable->service->lineAction->code}}
                            </td>
                            <td class="fs-1x fw-700">
                                {{$activity->taskable->service->lineAction->name}}
                            </td>
                        @endif
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="dropdown-divider"></div>
        <div class="d-flex flex-row mb-2 align-items-center justify-content-between">
            @if($activity->validateCrateBudget())
                <a href="javascript:void(0);" class="btn btn-outline-success btn-sm ml-auto" data-toggle="modal"
                   data-target="#budget-expense-project-create"
                   class="btn btn-success btn-sm"><span class="fas fa-plus mr-1"></span>
                    &nbsp;{{ trans('general.add_new') }}
                </a>
            @else
                <div class="w-100">
                    <div class="alert alert-warning align-center" role="alert" id="error">
                        No se puede crear partida presupuestaria Si la actividad no tiene un indicador asociado o no se
                        ha asignado una localidad al proyecto y a la actividad
                    </div>
                </div>
            @endif
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
                            <td>
                                <span class="badge {{$item->is_new ? 'badge-warning' : '' }}  badge-pill fs-1x fw-700">{{ $item->code }}</span>
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->balanceDraft($transaction->status) }} </td>
                            <td>
                                @if($transaction->status instanceof  \App\States\Transaction\Draft)
                                    <div class="d-flex flex-wrap w-100">
                                        <div class="ml-2">
                                            <a href="#" data-toggle="modal" data-expense-id="{{ $item->id }}"
                                               data-target="#budget-expense-project-edit">
                                                <i class="fas fa-edit mr-1 text-info"></i>
                                            </a>
                                        </div>
                                        <div class="ml-2">
                                            <x-delete-link
                                                    action="{{ route('budget-project.delete', [$item->id,$item->accountable_id] ) }}"
                                                    id="{{ $item->id }}"/>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr style="background-color: #e0e0e0">
                        <td colspan="3"></td>
                        <td style="color: #008000" class="fs-2x fw-700">Total: {{$total}}</td>
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
                    @if($activity->validateCrateBudget())
                        <a href="javascript:void(0);" class="btn btn-outline-success btn-sm ml-auto" data-toggle="modal"
                           data-target="#budget-expense-project-create"
                           class="btn btn-success btn-sm"><span class="fas fa-plus mr-1"></span>
                            &nbsp;{{ trans('general.add_new') }}
                        </a>
                    @else
                        <div class="w-100">
                            <div class="alert alert-warning align-center" role="alert" id="error">
                                No se puede crear partida presupuestaria Si la actividad no tiene un indicador asociado
                                o no se ha asignado una localidad al proyecto y a la
                                actividad
                            </div>
                        </div>
                    @endif
                </div>
            </x-empty-content>
        @endif

        <div wire:ignore>
            <livewire:budget.expenses.project.expense-project-activity-create-budget :activityId="$activity->id"
                                                                                     :transaction="$transaction"/>
        </div>
        <div wire:ignore>
            <livewire:budget.expenses.project.expense-project-activity-edit-budget :activityId="$activity->id"
                                                                                   :transaction="$transaction"/>
        </div>
    </div>
@endsection
@push('page_script')
    <script>
        $('#budget-expense-project-edit').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let expenseId = $(e.relatedTarget).data('expense-id');
            //Livewire event trigger
            Livewire.emit('loadExpense', expenseId);
        });

    </script>
@endpush