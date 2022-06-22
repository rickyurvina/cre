<div>
    <div class="d-flex flex-wrap p-2 mb-3">
        <div class="w-25">
            <a href="javascript:void(0)" wire:click="$set('viewPoa', true)" class="btn btn-outline-success">{{ trans('general.poa') }}</a>
            <a href="javascript:void(0)" wire:click="$set('viewProject', true)" class="btn btn-outline-success">{{ trans_choice('general.project',2) }}</a>
        </div>
        <div class="w-75">
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
        </div>
    </div>

    @if($viewPoa)
        @if( isset($activitiesPoa)  && $activitiesPoa->count()>0)
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
                            @foreach($activitiesPoa as $item)
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
                                        <a href="javascript:void(0)" class="mr-2" wire:click="loadPoaActivity({{$item->id}})"
                                           data-toggle="tooltip"
                                           data-placement="top" title=""
                                           data-original-title="{{trans('general.create')}}{{trans_choice('general.certifications',1)}}">
                                            <i class="fas fa-check-circle color-success-700"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endif
    @if($viewPoaActivity)
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
                            {{$poaActivity->program->poa->year}}
                        </td>
                        <td class="fs-1x fw-700">
                            {{$poaActivity->program->poa->name}}
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-1x fw-700">{{trans_choice('general.plan',1)}}</td>
                        <td>
                            {{$poaActivity->program->planDetail->plan->code}}
                        </td>
                        <td class="fs-1x fw-700">
                            {{$poaActivity->program->planDetail->plan->name}}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-50">
                <table class="table table-bordered detail-table">
                    <tbody>
                    <tr>
                        <td class="fs-1x fw-700 w-20">{{$poaActivity->program->planDetail->parent->parent->planRegistered->name}}
                        </td>
                        <td class="w-5">{{$poaActivity->program->planDetail->parent->parent->code}}</td>
                        <td class="fs-1x fw-700">{{$poaActivity->program->planDetail->parent->parent->name}}</td>
                    </tr>
                    <tr>
                        <td class="fs-1x fw-700 w-20">{{$poaActivity->program->planDetail->parent->planRegistered->name}}
                        </td>
                        <td class="w-5">{{$poaActivity->program->planDetail->parent->code}}</td>
                        <td class="fs-1x fw-700">{{$poaActivity->program->planDetail->parent->name}}</td>
                    </tr>
                    <tr>
                        <td class="fs-1x fw-700">{{trans_choice('general.programs',1)}}</td>
                        <td>
                            {{$poaActivity->program->planDetail->code}}
                        </td>
                        <td class="fs-1x fw-700">
                            {{$poaActivity->program->planDetail->name}}
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-1x fw-700 w-20">Indicador
                        </td>
                        <td class="w-5">{{$poaActivity->indicator->code}}</td>
                        <td class="fs-1x fw-700">{{$poaActivity->indicator->name}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @if($expensesPoa->count()>0)
            <div class="table-responsive">
                <table class="table table-light table-hover">
                    <thead>
                    <tr>
                        <th class="table-th w-20">{{trans('general.item')}}</th>
                        <th class="table-th w-30"> {{trans('general.name')}}</th>
                        <th class="table-th w-30"> {{trans('general.description')}}</th>
                        <th class="table-th w-10">Por Comprometer</th>
                        <th class="table-th w-10"><a href="#">{{  trans('general.value') }}</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($expensesPoa as $item)
                        <tr class="tr-hover">
                            <td><span class="badge {{$item->is_new ? 'badge-warning' : '' }}  badge-pill fs-1x fw-700">{{ $item->code }} {{$item->name}}</span></td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->balanceDraft($transaction->status) }} </td>
                            <td>
                                <div class="w-100 content-read-active">
                                    <input class="w-100 border-0 fw-400" type="number" wire:model.lazy="certificationsValues.{{$item->id}}">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    @endif

    @if($viewProject)
        @if(count($projects) > 0)
            <div class="btn-group">
                <button class="btn btn-outline-secondary dropdown-toggle @if(count($selectedProjects) > 0) filtered @endif"
                        type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ trans_choice('general.project', 2) }}
                    @if(count($selectedProjects) > 0)
                        <span class="badge bg-white ml-2">{{ count($selectedProjects) }}</span>
                    @endif
                </button>
                <div class="dropdown-menu">
                    @foreach($projects as $project)
                        <div class="dropdown-item">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       id="i-program-{{ $project['id'] }}" wire:model="selectedProjects"
                                       value="{{ $project['id'] }}">
                                <label class="custom-control-label"
                                       for="i-program-{{ $project['id'] }}">{{ $project['name'] }}</label>
                            </div>
                        </div>
                    @endforeach
                    <div class="dropdown-divider"></div>
                    <div class="dropdown-item">
                        <span wire:click="$set('selectedProjects', [])">{{ trans('general.delete_selection') }}</span>
                    </div>
                </div>
            </div>
        @endif
        @if(count($selectedProjects) > 0 || $search != '')
            <button type="button" class="btn btn-outline-default ml-2"
                    wire:click="clearFilters">{{ trans('common.clean_filters') }}</button>
        @endif

        @if( isset($activitiesProject)  && $activitiesProject->count()>0)
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
                            @foreach($activitiesProject as $item)
                                <tr class="tr-hover" wire:loading.class.delay="opacity-50">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span>{{ $item->project->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $item->indicator ? $item->indicator->name: 'Sin Indicador Asociado' }}</td>
                                    <td>
                                        {{ $item->code ? $item->code . ' - ':'' }}{{ $item->text }}
                                    </td>
                                    <td>{{ $item->getTotalBudget($transaction->status)}}</td>
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
                                        <a href="javascript:void(0)" class="mr-2" wire:click="loadProjectActivity({{$item->id}})"
                                           data-toggle="tooltip"
                                           data-placement="top" title=""
                                           data-original-title="{{trans('general.create')}}{{trans_choice('general.certifications',1)}}">
                                            <i class="fas fa-check-circle color-success-700"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endif
    @if($viewProjectActivity)
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
                            {{$projectActivity->project->code}}
                        </td>
                        <td class="fs-1x fw-700">
                            {{$projectActivity->project->name}}
                        </td>
                    </tr>

                    <tr>
                        <td class="fs-1x fw-700">Junta Ejecutora</td>
                        <td>
                            {{$projectActivity->company->id}}
                        </td>
                        <td class="fs-1x fw-700">
                            {{$projectActivity->company->name}}
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-1x fw-700">Programa</td>
                        <td>
                            {{$projectActivity->taskable->service->lineAction->program->code}}
                        </td>
                        <td class="fs-1x fw-700">
                            {{$projectActivity->taskable->service->lineAction->program->name}}
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-1x fw-700 w-20">{{trans_choice('general.indicators',1)}}
                        </td>
                        <td class="w-5">{{$projectActivity->indicator->code ?? ''}}</td>
                        <td class="fs-1x fw-700">{{$projectActivity->indicator->name ?? 'Sin Indicador Asociado'}}</td>
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
                        <td class="w-5">{{$projectActivity->parentOfTask->objective->code ?? ''}}</td>
                        <td class="fs-1x fw-700">{{$projectActivity->parentOfTask->objective->name ?? ''}}</td>
                    </tr>
                    <tr>
                        <td class="fs-1x fw-700 w-20">{{trans('general.result')}}
                        </td>
                        <td class="w-5">{{$projectActivity->parentOfTask->code}}</td>
                        <td class="fs-1x fw-700">{{$projectActivity->parentOfTask->text}}</td>
                    </tr>
                    <tr>
                        <td class="fs-1x fw-700">{{trans('general.service')}}</td>
                        <td>
                            {{$projectActivity->taskable->service->code}}
                        </td>
                        <td class="fs-1x fw-700">
                            {{$projectActivity->taskable->service->name}}
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-1x fw-700 w-20">{{trans('general.lines_action')}}
                        </td>
                        <td>
                            {{$projectActivity->taskable->service->lineAction->code}}
                        </td>
                        <td class="fs-1x fw-700">
                            {{$projectActivity->taskable->service->lineAction->name}}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @if($expensesProject->count()>0)
            <div class="table-responsive">
                <table class="table table-light table-hover">
                    <thead>
                    <tr>
                        <th class="table-th w-20">{{trans('general.item')}}</th>
                        <th class="table-th w-30"> {{trans('general.name')}}</th>
                        <th class="table-th w-30"> {{trans('general.description')}}</th>
                        <th class="table-th w-10">Por Comprometer</th>
                        <th class="table-th w-10"><a href="#">{{  trans('general.value') }}</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($expensesProject as $item)
                        <tr class="tr-hover">
                            <td><span class="badge {{$item->is_new ? 'badge-warning' : '' }}  badge-pill fs-1x fw-700">{{ $item->code }} {{$item->name}}</span></td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->balanceDraft($transaction->status) }} </td>
                            <td>
                                <div class="w-100 content-read-active">
                                    <input class="w-100 border-0 fw-400" type="number" wire:model.lazy="certificationsValues.{{$item->id}}">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif

    @if($viewPoaActivity || $viewProjectActivity)
        <div class="d-flex flex-wrap">
            <div class="w-75 mr-2">
                <x-label-section>{{ trans('general.description') }}</x-label-section>
                <div class="content-read-active w-100">
                    <textarea type="text" class="form-control  @error('description') is-invalid @enderror w-100" rows="3" wire:model.defer="description"></textarea>
                </div>
                @error('description')
                <div style="color: #fd3995" class="fs-1x fw-700">{{ $message }}</div>
                @enderror
            </div>
            <div class="ml-auto w-auto">
                <div class="d-flex flex-wrap">
                    <div>
                        <a href="javascript:void(0)" class="btn btn-sm btn-success mr-2"
                           wire:click="saveCertification()">{{trans('general.save')}} {{trans_choice('general.certifications',1)}}
                        </a>
                    </div>
                    <div>
                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-secondary" wire:click="closeActivity()">Cerrar</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
