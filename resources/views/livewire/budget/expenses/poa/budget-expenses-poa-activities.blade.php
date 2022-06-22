@section('breadcrumb')
    <ol class="breadcrumb bg-transparent pl-0 pr-0 mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('budgets.index') }}">
                Presupuestos
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('budgets.show',$transaction->id) }}">
                Presupuesto {{ $transaction->year }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('budgets.expenses',$transaction->id) }}">
                {{ trans('budget.expense') }}
            </a>
        </li>

        @if(isset($activities)  && $activities->count()>0)
            <li class="breadcrumb-item active">  {{ $activities->first()->program->poa->name }}</li>
        @endif
    </ol>
@endsection
<div>

    <div class="d-flex mb-3 mt-2">
        @if(isset($programs ) && count($programs) > 0)
            <div class="input-group bg-white shadow-inset-2 w-25 mr-2">
                <input type="text" class="form-control border-right-0 bg-transparent pr-0"
                       placeholder="{{ trans('general.filter') . ' ' . trans_choice('general.activities', 2) }} ..."
                       wire:model="search">
                <div class="input-group-append">
                <span class="input-group-text bg-transparent border-left-0">
                    <i class="fal fa-search"></i>
                </span>
                </div>
            </div>
        @endif

        @if(isset($programs ) && count($programs) > 0)
            <div class="btn-group">
                <button class="btn btn-outline-secondary dropdown-toggle @if(count($selectedPrograms) > 0) filtered @endif"
                        type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ trans_choice('general.programs', 2) }}
                    @if(count($selectedPrograms) > 0)
                        <span class="badge bg-white ml-2">{{ count($selectedPrograms) }}</span>
                    @endif
                </button>
                <div class="dropdown-menu">
                    @foreach($programs as $program)
                        <div class="dropdown-item">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="i-program-{{ $program['id'] }}" wire:model="selectedPrograms"
                                       value="{{ $program['id'] }}">
                                <label class="custom-control-label" for="i-program-{{ $program['id'] }}">{{  $program->planDetail->name }}</label>
                            </div>
                        </div>
                    @endforeach
                    <div class="dropdown-divider"></div>
                    <div class="dropdown-item">
                        <span wire:click="$set('selectedPrograms', [])">{{ trans('general.delete_selection') }}</span>
                    </div>
                </div>
            </div>
        @endif
        @if(count($selectedPrograms) > 0 || $search != '')
            <a href="javascript:void(0);" class="btn btn-outline-default ml-2" wire:click="clearFilters">{{ trans('common.clean_filters') }}</a>
        @endif
    </div>
    @if( isset($activities)  && $activities->count()>0)
        <div class="d-flex align-items-start">
            <div class="w-100">
                <div class="table-responsive">
                    <table class="table table-light table-hover">
                        <thead>
                        <tr>
                            <th class="w-15">@sortablelink('planDetail.name', trans_choice('general.programs', 1))</th>
                            <th class="w-15">@sortablelink('indicator.name', trans_choice('general.indicators', 1))</th>
                            <th class="w-25">@sortablelink('name', __('poa.name'))</th>
                            <th class="w-25">@sortablelink('name', __('poa.budget'))</th>
                            <th class="w-15">@sortablelink('responsible.name', __('general.responsible'))</th>
                            <th class="w-25">{{trans('general.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($activities as $item)
                            <tr class="tr-hover" wire:loading.class.delay="opacity-50">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="w-35">
                                            <span class="color-item shadow-hover-5 mr-2 cursor-default" style="background-color: {{ $item->program->color }}"></span>

                                        </div>
                                        <div class="w-65">
                                            <span>{{ $item->planDetail->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->indicator->name }}</td>
                                <td>
                                    <a href="javascript:void(0);" aria-expanded="false"
                                       data-toggle="modal"
                                       data-target="#show-budget-expenses-poa-activity"
                                       data-activity-id="{{$item->id}}">{{ $item->code ? $item->code . ' - ':'' }}{{ $item->name }}
                                    </a>
                                </td>
                                <td>{{ $item->getTotalBudget($transaction) }}</td>
                                <td>
                                    @if($item->responsible)
                                        <span class="mr-2">
                                        <img src="{{ asset_cdn('img/user.svg') }}" class="rounded-circle width-1">
                                    </span>
                                        {{ $item->responsible->name }}
                                    @else
                                        <span class="mr-2">
                                        <img src="{{ asset_cdn('img/user_off.png') }}" class="rounded-circle width-1">
                                    </span>
                                        {{ trans('general.not_assigned') }}
                                    @endif
                                </td>
                                <td>
                                    @if($item->validateCrateBudget())
                                        <a href="{{route('budgets.expenses_poa_activity',['transaction'=>$transaction,'activity'=>$item->id])}}" class="mr-2"
                                           data-toggle="tooltip"
                                           data-placement="top" title=""
                                           data-original-title="{{ trans('budget.budget_items') }}">
                                            <i class="fas fa-money-bill"></i>
                                        </a>
                                    @endif
                                    <a href="javascript:void(0);" aria-expanded="false"
                                       data-toggle="modal"
                                       data-target="#show-budget-expenses-poa-activity"
                                       data-activity-id="{{$item->id}}">
                                        <i class="fas fa-search mr-1 text-info"
                                           data-toggle="tooltip" data-placement="top" title=""
                                           data-original-title="Ver Actividad"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <x-pagination :items="$activities"/>
            </div>
        </div>
    @else
        <x-empty-content>
            <x-slot name="title">
                {{ trans('general.there_are_no_activities') }}
            </x-slot>
        </x-empty-content>
    @endif
    <div wire:ignore>
        <livewire:budget.expenses.poa.budget-expenses-show-poa-activity :transaction="$transaction"/>
    </div>
</div>
@push('page_script')
    <script>
        $('#show-budget-expenses-poa-activity').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let activityId = $(e.relatedTarget).data('activity-id');

            //Livewire event trigger
            Livewire.emit('loadActivity', activityId);
        });
    </script>
@endpush