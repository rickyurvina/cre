<div>
    <div class="d-flex flex-column">
        <div class="d-flex flex-nowrap">
            <div class="flex-grow-1 w-100" style="overflow: hidden auto">
                <ul class="nav nav-tabs nav-tabs-clean" role="tablist">
                    @if(session('company_id')===$project->company_id)
                        <li class="nav-item" wire:ignore>
                            <a class="nav-link active" data-toggle="tab" href="#tab-general" role="tab"
                               aria-selected="true">{{ trans('general.general') }}</a>
                        </li>
                    @endif
                    <li class="nav-item" wire:ignore>
                        <a class="nav-link" data-toggle="tab" href="#tab-implementation" role="tab"
                           aria-selected="false">Ejecución de Actividades</a>
                    </li>
                    <li class="nav-item" wire:ignore>
                        <a class="nav-link" data-toggle="tab" href="#tab-indicators" role="tab" aria-selected="false">Indicadores</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="d-flex mb-1 mt-1">
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
                        @if(count($results) > 0)
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary dropdown-toggle @if(count($selectedResults) > 0) filtered @endif"
                                        type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ trans_choice('general.result',2)}}
                                    @if(count($selectedResults) > 0)
                                        <span class="badge bg-white ml-2">{{ count($selectedResults) }}</span>
                                    @endif
                                </button>
                                <div class="dropdown-menu">
                                    @foreach($results as $result)
                                        <div class="dropdown-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="i-program-{{ $result->id}}" wire:model="selectedResults"
                                                       value="{{ $result->id }}">
                                                <label class="custom-control-label"
                                                       for="i-program-{{ $result->id }}">{{ strlen($result->text)>40? substr($result->text , 0,40).'...': $result->text  }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="dropdown-divider"></div>
                                    <div class="dropdown-item">
                                        <span wire:click="$set('selectedResults', [])">{{ trans('general.delete_selection') }}</span>
                                    </div>
                                    <div class="dropdown-item">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="showProgramPanel"
                                                   checked="" wire:model="showProgramPanel">
                                            <label class="custom-control-label"
                                                   for="showProgramPanel">{{ trans('general.show_panel_results') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(count($selectedResults) > 0 || $search != '')
                            <a class="btn btn-outline-default ml-2"
                               wire:click="clearFilters()">{{ trans('common.clean_filters') }}</a>
                        @endif
                        @if($project->phase instanceof \App\States\Project\Planning)
                            <button type="button" class="btn btn-success border-0 shadow-0 ml-2" data-toggle="modal"
                                    data-target="#project-create-result-activity">{{ trans('general.create')}} {{trans('general.activity')}}
                            </button>
                            <a class="btn btn-success border-0 shadow-0 ml-2 mr-2" style="color:white;"
                               wire:click="$emit('show', 'App\\Models\\Projects\\Project', '{{ $project->id }}')">
                                {{ trans('general.create') }} Indicador
                            </a>
                        @endif
                        <hr>
                    </div>
                    @if(session('company_id')===$project->company_id)
                        <div class="tab-pane fade active show" id="tab-general" role="tabpanel" wire:ignore.self>
                            <div class="pl-2 mt-2">
                                <div class="d-flex align-items-start">
                                    @if($showProgramPanel)
                                        <div class="panel w-30 mr-3">
                                            <div class="panel-hdr">
                                                <h2>
                                                    {{ trans_choice('general.result',2) }}
                                                </h2>
                                                <div class="panel-toolbar">
                                                    <button class="btn btn-panel" data-toggle="tooltip"
                                                            data-offset="0,10"
                                                            data-original-title="{{ trans('general.close') }}"
                                                            wire:click="$set('showProgramPanel', false)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="panel-container show">
                                                <div class="panel-content">
                                                    <div class="accordion accordion-outline accordion-clean"
                                                         id="accordion-progrms">
                                                        @foreach($results as $index => $result)
                                                            <div class="card mb-1">
                                                                <div class="card-header">
                                                                    <a href="javascript:void(0);"
                                                                       class="card-title py-2 collapsed"
                                                                       data-toggle="collapse"
                                                                       data-target="#accordion-p-{{ $result->id }}"
                                                                       aria-expanded="false">
                                                                        <span class="color-item shadow-hover-5 mr-2 w-5"
                                                                              style="background-color: {{ $result['color'] }}"></span>
                                                                        <span class="w-75">{{$result->text}}</span>
                                                                        <span class="ml-auto">
                                                                            <span class="collapsed-reveal">
                                                                                <i class="fal fa-minus fs-xl"></i>
                                                                            </span>
                                                                            <span class="collapsed-hidden">
                                                                                <i class="fal fa-plus fs-xl"></i>
                                                                            </span>
                                                                         </span>
                                                                    </a>
                                                                </div>
                                                                <div id="accordion-p-{{ $result->id }}" class="collapse">
                                                                    <div class="card-body">
                                                                        <div class="d-flex flex-column">
                                                                            <div class="dropdown-item cursor-pointer"
                                                                                 style="border-radius: 4px">
                                                                                <div class="dropdown-cell-wrapper show-child-on-hover cursor-pointer dropdown dropdown-table show-hidden-child-on-hover mr-2 dropdown-logic-frame"
                                                                                     data-toggle="dropdown">
                                                                                    <div class="dropdown-option-wrapper">
                                                                                        <div class="mr-2">
                                                                                            <i class="fas fa-plus-circle"></i>
                                                                                        </div>
                                                                                        <div class="option-names">
                                                                                            <span class="">
                                                                                                <span class="bg-gray-50"
                                                                                                      dir="auto">
                                                                                                    <span>{{ $result->indicators->count()>1?  $result->indicators->count().'-Indicadores':$result->indicators->count().'-Indicador'}}</span>
                                                                                                </span>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="dropdown-menu fadeindown  m-0 dropdown-menu-side show-child-on-hover pr-4">
                                                                                        @foreach($result->indicators as $indicator)
                                                                                            <div class="dropdown-item justify-content-between cursor-default"
                                                                                                 wire:key="{{ 'r.i.' . $loop->index }}">
                                                                                                <div class="col-md-9 cursor-pointer">
                                                                                                    <i class="fal fa-chart-line mr-2"></i>
                                                                                                    <span class="text-component"
                                                                                                          dir="auto">
                                                                                               <span>{{ strlen( $indicator->name)>25? substr( $indicator->name,0,25).'...': $indicator->name }}</span>
                                                                                             </span>
                                                                                                </div>
                                                                                                <div class="col-md-1 cursor-pointer"
                                                                                                     wire:click="$emit('triggerAdvance','{{ $indicator->id }}')">
                                                                                                    <span class="color-success-700"><i
                                                                                                                class="far fa-calendar-alt"></i></span>
                                                                                                </div>
                                                                                                <div class="col-md-1 cursor-pointer"
                                                                                                     wire:click="$emit('triggerEdit', '{{ $indicator->id }}')">
                                                                                                    <span class="color-warning-700"><i
                                                                                                                class="fas fa-pencil-alt"></i></span>
                                                                                                </div>
                                                                                                <div class="col-md-1 cursor-pointer"
                                                                                                     wire:click="$emit('triggerDeleteIndicator', '{{ $indicator->id }}')">
                                                                                                    <span class="color-danger-700"><i
                                                                                                                class="fas fa-trash-alt"></i></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endforeach
                                                                                        @if($project->phase instanceof \App\States\Project\Planning)
                                                                                            <div class="dropdown-item m-2 d-flex active mt-4"
                                                                                                 wire:click="$emit('show', 'App\\Models\\Projects\\Activities\\Task', '{{ $result->id }}')">
                                                                                                <i class="fal fa-plus mr-2"></i>
                                                                                                <span class="text-component"
                                                                                                      dir="auto">
                                                                                        <span>Agregar Indicador</span>
                                                                                    </span>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @if($result->childs()->count()<1)
                                                                                <div class="dropdown-item cursor-pointer"
                                                                                     style="border-radius: 4px"
                                                                                     data-toggle="modal"
                                                                                     wire:click="$emit('triggerDeleteResult', '{{ $result->id }}')">
                                                                                    <i class="fal fa-trash-alt mr-2"></i> {{  trans_choice('general.delete', 1) }}
                                                                                </div>
                                                                            @endif
                                                                            <h6 class="m-0 text-muted">{{ __('general.color') }}</h6>
                                                                            <div wire:ignore>
                                                                                <livewire:components.color-palette
                                                                                        :modelId="$result->id"
                                                                                        :key="time().$loop->index"
                                                                                        class="\App\Models\Projects\Activities\Task"
                                                                                        field="color"/>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="w-100">
                                        <div class="table-responsive">
                                            <table class="table table-light table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="w-10 table-th">{{__('general.code')}}</th>
                                                    <th class="w-20 table-th">{{__('general.name')}}</th>
                                                    <th class="w-20 table-th">{{__('general.responsable')}}</th>
                                                    <th class="w-20 table-th">Sede Ejecutora</th>
                                                    <th class="w-10 table-th">Indicadores</th>
                                                    <th class="w-10 table-th"><a
                                                                href="#">{{ trans('general.actions') }} </a></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($activities as $item)
                                                    <tr class="tr-hover" wire:loading.class.delay="opacity-50">
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <span class="color-item shadow-hover-5 mr-2 cursor-default"
                                                                      style="background-color: {{ $item->color }}"></span>
                                                                {{$item->code }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                {{$item->text }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if($item->responsible)
                                                                    {{$item->responsible->getFullName() }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                {{$item->company->name}}
                                                            </div>
                                                        </td>
                                                        <td class="border-right">
                                                            <div class="cursor-pointer  dropdown-table show-hidden-child-on-hover mr-2 dropdown-logic-frame"
                                                                 data-toggle="dropdown">
                                                                <div class="dropdown-option-wrapper">
                                                                    <div class="mr-2">
                                                                    <span class="bg-gray-50 mr-2">{{ $item->indicators->count() }}    <i
                                                                                class="fas fa-plus-circle text-info"></i></span>
                                                                    </div>
                                                                    <div class="dropdown-menu fadeindown dropdown-xl m-0 dropdown-menu-side show-child-on-hover">
                                                                        @foreach($item->indicators as $indicator)
                                                                            <div class="dropdown-item m-2 justify-content-between cursor-default"
                                                                                 wire:key="{{ 'r.i.' . $loop->index }}">
                                                                                <div class="col-md-9 cursor-pointer">
                                                                                    <i class="fal fa-chart-line mr-2"></i>
                                                                                    <span class="text-component"
                                                                                          dir="auto">
                                                                            <span>{{ strlen( $indicator->name)>25? substr( $indicator->name,0,25).'...': $indicator->name }}</span>
                                                                             </span>
                                                                                </div>
                                                                                <div class="col-md-1 cursor-pointer"
                                                                                     wire:click="$emit('triggerAdvance','{{ $indicator->id }}')">
                                                                                    <span class="color-success-700"><i
                                                                                                class="far fa-calendar-alt"></i></span>
                                                                                </div>
                                                                                <div class="col-md-1 cursor-pointer"
                                                                                     wire:click="$emit('triggerEdit', '{{ $indicator->id }}')">
                                                                                    <span class="color-warning-700"><i
                                                                                                class="fas fa-pencil-alt"></i></span>
                                                                                </div>
                                                                                <div class="col-md-1 cursor-pointer"
                                                                                     wire:click="$emit('triggerDeleteIndicator', '{{ $indicator->id }}')">
                                                                                    <span class="color-danger-700"><i
                                                                                                class="fas fa-trash-alt"></i></span>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                        <div class="dropdown-item m-2 d-flex active mt-4"
                                                                             wire:click="$emit('show', 'App\\Models\\Projects\\Activities\\Task', '{{ $item->id }}')">
                                                                            <i class="fal fa-plus mr-2"></i>
                                                                            <span class="text-component" dir="auto">
                                                                               <span>Agregar Indicador</span>
                                                                         </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);" aria-expanded="false"
                                                               wire:click="$emit('registerAdvance', '{{ $item->id }}')">
                                                                <i class="fas fa-edit mr-1 text-info"
                                                                   data-toggle="tooltip" data-placement="top" title=""
                                                                   data-original-title="Editar"></i>
                                                            </a>
                                                            @if($item->indicators->count() < 1)
                                                                @if(!($project->phase instanceof \App\States\Project\Implementation))
                                                                    <button class="border-0 bg-transparent"
                                                                            wire:click="$emit('triggerDeleteResult', '{{ $item->id }}')"
                                                                            data-toggle="tooltip"
                                                                            data-placement="top" title="Eliminar"
                                                                            data-original-title="Eliminar"><i
                                                                                class="fas fa-trash mr-1 text-danger"></i>
                                                                    </button>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7">
                                                            <div class="d-flex align-items-center justify-content-center">
                                                    <span class="color-fusion-500 fs-3x py-3"><i
                                                                class="fas fa-exclamation-triangle color-warning-900"></i> No se encontraron actividades</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        <x-pagination :items="$activities"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="tab-pane fade" id="tab-implementation" role="tabpanel" wire:ignore.self>
                        <div class="pl-2 pt-2">
                            <div class="content-detail">
                                <div class="d-flex flex-column">
                                    <div class="d-flex flex-nowrap mt-2">
                                        <div class="flex-grow-1 w-auto" style="overflow: hidden auto">
                                            <div class="d-flex flex-wrap">
                                                <x-label-section>Cronograma- Año Fiscal {{ date("Y")}}</x-label-section>
                                                <div class="ml-auto">
                                                    <x-label-section>Avance
                                                        Físico {{ number_format($projectAdvance,2) }}%
                                                    </x-label-section>
                                                </div>
                                                <div class="ml-auto">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary mr-2"
                                                            data-toggle="modal" data-target="#project-activities-weight">
                                                        {{ __('general.weight') }} {{ trans_choice('general.result', 2) }}
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary mr-4"
                                                            data-toggle="modal" data-target="#project-activities-wbs"
                                                            data-id="{{ $project->id }}">
                                                        WBS
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="section-divider"></div>
                                            <div class="row">
                                                <div class="col-10">
                                                    <div class="d-flex flex-wrap">
                                                        <x-label-detail>{{ trans_choice('general.project',1) }}</x-label-detail>
                                                        <x-content-detail>{{ $project->name}}</x-content-detail>
                                                    </div>
                                                    <div class="d-flex flex-wrap">
                                                        <x-label-detail>{{ trans('general.start_date') }}</x-label-detail>
                                                        <x-content-detail>{{$project->start_date?  $project->start_date->format('j F, Y') :'' }} </x-content-detail>
                                                    </div>
                                                    <div class="d-flex flex-wrap">
                                                        <x-label-detail>{{ trans('general.end_date') }}</x-label-detail>
                                                        <x-content-detail>{{$project->end_date? $project->end_date->format('j F, Y') :'' }}</x-content-detail>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="d-flex flex-wrap">
                                                        <x-label-detail>Terminada</x-label-detail>
                                                        <i class="fas fa-circle color-success-700 mt-2"></i>
                                                    </div>
                                                    <div class="d-flex flex-wrap">
                                                        <x-label-detail>En tiempo</x-label-detail>
                                                        <i class="fas fa-circle color-info-700 mt-2"></i>
                                                    </div>
                                                    <div class="d-flex flex-wrap">
                                                        <x-label-detail>Atraso</x-label-detail>
                                                        <i class="fas fa-circle color-danger-700 mt-2"></i>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="w-50">
                                            <div class="row">
                                                <div class="col-4 text-center">
                                                    <x-label-section> Estado A la Fecha</x-label-section>
                                                    <div class="mt-4" wire:ignore>
                                                        <div class="js-easy-pie-chart {{$project->calcSemaphore()}}
                                                                position-relative d-inline-flex align-items-center justify-content-center"
                                                             data-percent="{{$project->getProgressUpDate()}}"
                                                             data-piesize="100" data-linewidth="7" data-linecap="round"
                                                             data-scalelength="7">
                                                            <div class="d-flex flex-column align-items-center justify-content-center position-absolute pos-left pos-right pos-top pos-bottom fw-300 fs-xl">
                                                                <span class="js-percent d-block text-dark"></span>
                                                                <div class="d-block fs-xs text-dark opacity-70">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4 text-center">
                                                    <x-label-section> Estado Actual Del Proyecto</x-label-section>
                                                    <div class="mt-4" wire:ignore>
                                                        <div class="js-easy-pie-chart color-info-700
                                                                position-relative d-inline-flex align-items-center justify-content-center"
                                                             data-percent="{{$projectAdvance}}" data-piesize="100"
                                                             data-linewidth="7" data-linecap="round"
                                                             data-scalelength="7">
                                                            <div class="d-flex flex-column align-items-center justify-content-center position-absolute pos-left pos-right pos-top pos-bottom fw-300 fs-xl">
                                                                <span class="js-percent d-block text-dark"></span>
                                                                <div class="d-block fs-xs text-dark opacity-70">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <x-label-section> % Avance de Tiempo</x-label-section>
                                                    <div class="mt-4">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-striped bg-success"
                                                                 role="progressbar"
                                                                 style="width: {{$project->getProgressTimeUpDate()}}%"
                                                                 aria-valuenow="{{$project->getProgressTimeUpDate()}}"
                                                                 aria-valuemin="0"
                                                                 aria-valuemax="100">{{$project->getProgressTimeUpDate()}}
                                                                %
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="section-divider"></div>

                                    <div class="d-flex flex-nowrap mt-2">
                                        <div class="w-100">
                                            <div class="table-responsive">
                                                <table class="table table-light table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th class="w-5 table-th">{{__('general.code')}}</th>
                                                        <th class="w-auto table-th">{{__('general.activity')}}</th>
                                                        <th class="w-10 table-th">{{__('general.start_date')}}</th>
                                                        <th class="w-10 table-th">{{__('general.end_date')}}</th>
                                                        <th class="w-15 table-th">Tiempo Transcurrido</th>
                                                        <th class="w-5 table-th">Ponderación</th>
                                                        <th class="w-15 table-th">Progreso-SubTareas</th>
                                                        <th class="w-5 table-th">Estado</th>
                                                        <th class="w-5 table-th">Semaforo</th>
                                                        <th class="w-10 text-center table-th"><a
                                                                    href="#">{{ trans('general.actions') }} </a></th>
                                                    </tr>
                                                    <tr class="h-40px">
                                                        <th colspan="12" class="table-info h-25">
                                                            Ejecución de Resultados y Actividades
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($results as $result)

                                                        <tr class="table-bordered table-secondary table-striped">
                                                            <td>{{ $result->code}}</td>
                                                            <td>
                                                                @if($result->responsible)
                                                                    <a href="#" data-placement="top"
                                                                       title="{{$result->responsible->getFullName()}}"
                                                                       data-original-title="{{$result->responsible->getFullName()}}">
                                                                        ({{$result->responsible->shortNickName()??''}})
                                                                    </a>
                                                                @endif
                                                                {{$result->text}}
                                                            </td>
                                                            <td>{{ $result->start_date ? $result->start_date->format('j F, Y') :''}}</td>
                                                            <td>{{ $result->end_date ? $result->end_date->format('j F, Y') : ''}}</td>
                                                            <td>-</td>
                                                            <td>{{ number_format($result->weight,2) }}</td>
                                                            <td class="text-center">
                                                                @if(number_format($result->progress) >0)
                                                                    <div class="progress">
                                                                        <div class="progress-bar bg-danger-300 bg-warning-gradient"
                                                                             role="progressbar"
                                                                             style="width: {{ number_format($result->progress) }}%"
                                                                             aria-valuenow="{{ number_format($result->progress) }}"
                                                                             aria-valuemin="0"
                                                                             aria-valuemax="100"> {{ number_format($result->progress) }}
                                                                            %
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <span class="badge badge-secondary badge-pill">{{ number_format($result->progress) }}%</span>
                                                                @endif
                                                            </td>
                                                            <td><i class="fal fa-minus color-light-700 fs-2x"></i></td>
                                                            <td><i class="fal fa-minus color-light-700 fs-2x"></i></td>
                                                            <td class="text-center"><i
                                                                        class="fal fa-minus color-danger-700 fs-2x"></i>
                                                            </td>
                                                        </tr>
                                                        @foreach($activities->where('parent',$result->id) as $activity)
                                                            <tr>
                                                                <td>{{$activity->code}}</td>
                                                                <td> @if($activity->responsible)
                                                                        <a href="#" data-placement="top"
                                                                           title="{{$activity->responsible->getFullName()}}"
                                                                           data-original-title="{{$activity->responsible->getFullName()}}">
                                                                            ({{$activity->responsible->shortNickName()??''}}
                                                                            )
                                                                        </a> @endif
                                                                    {{$activity->text}}-{{$activity->company->name}}
                                                                </td>
                                                                <td>{{ $activity->start_date ? $activity->start_date->format('j F, Y') :''}} </td>
                                                                <td>{{$activity->end_date ? $activity->end_date->format('j F, Y'):''}}</td>
                                                                @if($activity->start_date>=now())
                                                                    <td class="text-center"><span
                                                                                class="badge badge-secondary badge-pill">No Empieza</span>
                                                                    </td>
                                                                @else
                                                                    <td class="text-center">
                                                                        <div class="progress">
                                                                            <div class="progress-bar bg-primary-700 bg-success-gradient"
                                                                                 role="progressbar"
                                                                                 style="width: {{$activity->getProgressTimeUpDate()}}%"
                                                                                 aria-valuenow="{{$activity->getProgressTimeUpDate()}}"
                                                                                 aria-valuemin="0"
                                                                                 aria-valuemax="100"> {{$activity->getProgressTimeUpDate()}}
                                                                                %
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                @endif
                                                                <td>{{number_format($activity->weight,2) }}</td>
                                                                <td class="text-center">
                                                                    @if(number_format($activity->progress) >0)
                                                                        <div class="progress">
                                                                            <div class="progress-bar bg-danger-300 bg-warning-gradient"
                                                                                 role="progressbar"
                                                                                 style="width: {{ number_format($activity->progress) }}%"
                                                                                 aria-valuenow="{{ number_format($activity->progress) }}"
                                                                                 aria-valuemin="0"
                                                                                 aria-valuemax="100"> {{ number_format($activity->progress) }}
                                                                                %
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <span class="badge badge-secondary badge-pill">{{ number_format($activity->progress) }}%</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <span class="badge {{ \App\Models\Projects\Activities\Task::STATUS_BGC[$activity->status] }} badge-primary badge-pill"> {{$activity->status}}</span>
                                                                </td>
                                                                <td class="text-center"><i
                                                                            class="fas fa-circle {{$activity->calcSemaphore()}}"></i>
                                                                </td>
                                                                <td class="text-center">
                                                                    <a href="javascript:void(0);" aria-expanded="false"
                                                                       wire:click="$emit('registerAdvance', '{{ $activity->id }}')">
                                                                        <i class="fas fa-edit mr-1 text-info"
                                                                           data-toggle="tooltip" data-placement="top"
                                                                           title=""
                                                                           data-original-title="Detalles Actividad"></i>
                                                                    </a>
                                                                    @if($item->goals->count()==0 && $item->accounts->count()==0 && $item->activitiesTask->count()==0)
                                                                        <button class="border-0 bg-transparent"
                                                                                wire:click="$emit('triggerDeleteResult', '{{ $activity->id }}')"
                                                                                data-toggle="tooltip"
                                                                                data-placement="top" title="Eliminar"
                                                                                data-original-title="Eliminar"><i
                                                                                    class="fas fa-trash mr-1 text-danger"></i>
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-indicators" role="tabpanel" wire:ignore.self>
                        <div class="pl-2 pt-0">
                            <div class="content-detail">
                                <div class="d-flex flex-column">
                                    <div class="d-flex flex-nowrap mt-2">
                                        <div class="w-100">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped w-100 dataTable no-footer dtr-inline">
                                                    <thead>
                                                    <tr>
                                                        <th class="w-20 table-th">{{__('general.name')}}</th>
                                                        <th class="w-5 table-th">{{__('general.code')}}</th>
                                                        <th class="w-auto table-th">{{trans_choice('general.indicators',2)}}</th>
                                                        <th class="w-10 table-th">Meta</th>
                                                        <th class="w-10 table-th">Avance</th>
                                                        <th class="w-15 table-th">Progreso</th>
                                                        <th>{{trans('general.responsible')}}</th>
                                                        <th class="w-10 text-center table-th">{{ trans('general.actions') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="8" class="table-info h-25 text-center">Proyecto
                                                        </td>
                                                    </tr>

                                                    @foreach($project->indicators as $indicator)
                                                        <tr>
                                                            @if($loop->first)
                                                                <th class="w-20 table-th align-middle align-items-center text-center"
                                                                    rowspan="{{$project->indicators->count()}}">{{$project->name}}</th>
                                                            @endif
                                                            <th class="w-5 table-th">{{$indicator->name ?? ''}}</th>
                                                            <th class="w-auto table-th">{{$indicator->code}}</th>
                                                            <th class="w-15 table-th">{{$indicator->total_goal_value}}</th>
                                                            <th class="w-5 table-th">{{$indicator->total_actual_value}}</th>
                                                            <td>
                                                                <span class="form-label badge {{$indicator->getStateIndicator()[0]?? null}}  badge-pill">{{$indicator->getStateIndicator()[1]?? null}}%</span>
                                                            </td>
                                                            <th>
                                                                <div class="dropdown-item">
                                                                                                <span class="mr-2">
                                                                                                    <img src="http://cre.test/img/user.svg"
                                                                                                         class="rounded-circle width-1">
                                                                                                </span>
                                                                    <span class="pt-1">{{ $indicator->user->getFullName() }}</span>
                                                                </div>
                                                            </th>
                                                            <th class="w-10 text-center table-th">
                                                                <div class="d-flex flex-wrap"
                                                                     wire:key="{{ 'r.i.' . $loop->index }}">
                                                                    <div class="w-25 cursor-pointer"
                                                                         wire:click="$emitTo('indicators.indicator-show', 'open', {{ $indicator->id }})">
                                                                        <span class="color-info-700"><i
                                                                                    class="far fa-eye" aria-expanded="false"
                                                                                    data-toggle="tooltip" data-placement="top" title=""
                                                                                    data-original-title="Ver"></i></span>
                                                                    </div>
                                                                    <div class="w-25 cursor-pointer"
                                                                         wire:click="$emit('triggerAdvance','{{ $indicator->id }}')">
                                                                        <span class="color-success-700"><i
                                                                                    class="far fa-calendar-alt" aria-expanded="false"
                                                                                    data-toggle="tooltip" data-placement="top" title=""
                                                                                    data-original-title="Avance"></i></span>
                                                                    </div>
                                                                    <div class="w-25 cursor-pointer"
                                                                         wire:click="$emit('triggerEdit', '{{ $indicator->id }}')">
                                                                        <span class="color-info-700"><i
                                                                                    class="fas fa-pencil-alt" aria-expanded="false"
                                                                                    data-toggle="tooltip" data-placement="top" title=""
                                                                                    data-original-title="Editar"></i></span>
                                                                    </div>
                                                                    <div class="w-25 cursor-pointer"
                                                                         wire:click="$emit('triggerDeleteIndicator', '{{ $indicator->id }}')">
                                                                        <span class="color-danger-700"><i
                                                                                    class="fas fa-trash-alt"></i></span>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="8" class="table-info h-25 text-center">Objetivos
                                                        </td>
                                                    </tr>
                                                    @foreach($objectives as $objective)
                                                        @foreach($objective->indicators as $indicator)
                                                            <tr>
                                                                @if($loop->first)
                                                                    <th class="w-20 table-th align-middle align-items-center text-center"
                                                                        rowspan="{{$objective->indicators->count()}}">{{$objective->name}}</th>
                                                                @endif
                                                                <th class="w-5 table-th">{{$indicator->code}}</th>
                                                                <th class="w-auto table-th">{{$indicator->name}}</th>
                                                                <th class="w-15 table-th">{{$indicator->total_goal_value}}</th>
                                                                <th class="w-5 table-th">{{$indicator->total_actual_value}}</th>
                                                                <td>
                                                                    <span class="form-label badge {{$indicator->getStateIndicator()[0]?? null}}  badge-pill">{{$indicator->getStateIndicator()[1]?? null}}%</span>
                                                                </td>
                                                                <th>
                                                                    <div class="dropdown-item">
                                                                            <span class="mr-2">
                                                                                <img src="http://cre.test/img/user.svg"
                                                                                     class="rounded-circle width-1">
                                                                            </span>
                                                                        <span class="pt-1">{{ $indicator->user->getFullName() }}</span>
                                                                    </div>
                                                                </th>
                                                                <th class="w-10 text-center table-th">
                                                                    <div class="d-flex flex-wrap"
                                                                         wire:key="{{ 'r.i.' . $loop->index }}">
                                                                        <div class="w-25 cursor-pointer"
                                                                             wire:click="$emitTo('indicators.indicator-show', 'open', {{ $indicator->id }})">
                                                                            <span class="color-info-700"><i
                                                                                        class="far fa-eye" aria-expanded="false"
                                                                                        data-toggle="tooltip" data-placement="top" title=""
                                                                                        data-original-title="Ver"></i></span>
                                                                        </div>
                                                                        <div class="w-25 cursor-pointer"
                                                                             wire:click="$emit('triggerAdvance','{{ $indicator->id }}')">
                                                                            <span class="color-success-700"><i
                                                                                        class="far fa-calendar-alt" aria-expanded="false"
                                                                                        data-toggle="tooltip" data-placement="top" title=""
                                                                                        data-original-title="Avance"></i></span>
                                                                        </div>
                                                                        <div class="w-25 cursor-pointer"
                                                                             wire:click="$emit('triggerEdit', '{{ $indicator->id }}')">
                                                                            <span class="color-info-700"><i
                                                                                        class="fas fa-pencil-alt" aria-expanded="false"
                                                                                        data-toggle="tooltip" data-placement="top" title=""
                                                                                        data-original-title="Editar"></i></span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="8" class="table-info h-25 text-center">Indicadores
                                                            de Resultados
                                                        </td>
                                                    </tr>
                                                    @foreach($results as $result)
                                                        @foreach($result->indicators as $indicatorR)
                                                            <tr>
                                                                @if($loop->first)
                                                                    <th class="w-20 table-th align-middle align-items-center text-center"
                                                                        rowspan="{{$result->indicators->count()}}">{{$result->text}}</th>
                                                                @endif
                                                                <th class="w-5 table-th">{{$indicatorR->code}}</th>
                                                                <th class="w-auto table-th">{{$indicatorR->name}}</th>
                                                                <th class="w-15 table-th">{{$indicatorR->total_goal_value}}</th>
                                                                <th class="w-5 table-th">{{$indicatorR->total_actual_value}}</th>
                                                                <td>
                                                                    <span class="form-label badge {{$indicatorR->getStateIndicator()[0]?? null}}  badge-pill">{{$indicatorR->getStateIndicator()[1]?? null}}%</span>
                                                                </td>
                                                                <th>
                                                                    <div class="dropdown-item">
                                                                            <span class="mr-2">
                                                                                <img src="http://cre.test/img/user.svg"
                                                                                     class="rounded-circle width-1">
                                                                            </span>
                                                                        <span class="pt-1">{{ $indicatorR->user->getFullName() }}</span>
                                                                    </div>
                                                                </th>
                                                                <th class="w-10 text-center table-th">
                                                                    <div class="d-flex flex-wrap"
                                                                         wire:key="{{ 'r.i.' . $loop->index }}">
                                                                        <div class="w-25 cursor-pointer"
                                                                             wire:click="$emitTo('indicators.indicator-show', 'open', {{ $indicatorR->id }})">
                                                                            <span class="color-info-700"><i
                                                                                        class="far fa-eye" aria-expanded="false"
                                                                                        data-toggle="tooltip" data-placement="top" title=""
                                                                                        data-original-title="Ver"></i></span>
                                                                        </div>
                                                                        <div class="w-25 cursor-pointer"
                                                                             wire:click="$emit('triggerAdvance','{{ $indicatorR->id }}')">
                                                                            <span class="color-success-700"><i
                                                                                        class="far fa-calendar-alt" aria-expanded="false"
                                                                                        data-toggle="tooltip" data-placement="top" title=""
                                                                                        data-original-title="Avance"></i></span>
                                                                        </div>
                                                                        <div class="w-25 cursor-pointer"
                                                                             wire:click="$emit('triggerEdit', '{{ $indicatorR->id }}')">
                                                                            <span class="color-info-700"><i
                                                                                        class="fas fa-pencil-alt" aria-expanded="false"
                                                                                        data-toggle="tooltip" data-placement="top" title=""
                                                                                        data-original-title="Editar"></i></span>
                                                                        </div>

                                                                    </div>
                                                                </th>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page_script')
    <script>
        Livewire.on('toggleRegisterAdvance', () => $('#register-indicator-advance').modal('toggle'));
        Livewire.on('toggleIndicatorShowModal', () => $('#indicator-show-modal').modal('toggle'));

        $('#project-create-activity').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let resultId = $(e.relatedTarget).data('result-id');
            //Livewire event trigger
            Livewire.emit('loadServices', resultId);
        });
        document.addEventListener('DOMContentLoaded', function () {
            $('div.dropdown-item, .color-item').on('click', function () {
                $(".open-drop").dropdown("hide");
            });
        @this.on('triggerDeleteIndicator', id => {
            Swal.fire({
                title: '{{ trans('messages.warning.sure') }}',
                text: '{{ trans('messages.warning.delete') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                confirmButtonText: '<i class="fas fa-trash"></i> {{ trans('general.yes') . ', ' . trans('general.delete') }}',
                cancelButtonText: '<i class="fas fa-times"></i> {{ trans('general.no') . ', ' . trans('general.cancel') }}'
            }).then((result) => {
                //if user clicks on delete
                if (result.value) {
                    // calling destroy method to delete
                @this.call('deleteIndicator', id);
                }
            });
        });
        @this.on('triggerEdit', id => {
            Livewire.emit('loadIndicatorEditData', id);
            $('#indicator-edit-modal').modal('toggle');
        });
        @this.on('triggerAdvance', id => {
            Livewire.emit('actionLoad', id);
            $('#register-indicator-advance').modal('toggle');
        });
        @this.on('triggerEdit', id => {
            Livewire.emit('loadIndicatorEditData', id);
            $('#indicator-edit-modal').modal('toggle');
        });

        @this.on('registerAdvance', id => {
            window.livewire.emitTo('projects.activities.project-register-advance-activity', 'openAdvance', {id: id});
        });
        @this.on('triggerAdvance', id => {
            Livewire.emit('actionLoad', id);
            $('#register-indicator-advance').modal('toggle');
        });


        @this.on('triggerDeleteActivity', id => {
            Swal.fire({
                title: '{{ trans('messages.warning.sure') }}',
                text: '{{ trans('messages.warning.delete') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                confirmButtonText: '<i class="fas fa-trash"></i> {{ trans('general.yes') . ', ' . trans('general.delete') }}',
                cancelButtonText: '<i class="fas fa-times"></i> {{ trans('general.no') . ', ' . trans('general.cancel') }}'
            }).then((result) => {
                //if user clicks on delete
                if (result.value) {
                    // calling destroy method to delete
                @this.call('deleteActivity', id);
                }
            });
        });

        @this.on('triggerDeleteResult', id => {
            Swal.fire({
                title: '{{ trans('messages.warning.sure') }}',
                text: '{{ trans('messages.warning.delete') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                confirmButtonText: '<i class="fas fa-trash"></i> {{ trans('general.yes') . ', ' . trans('general.delete') }}',
                cancelButtonText: '<i class="fas fa-times"></i> {{ trans('general.no') . ', ' . trans('general.cancel') }}'
            }).then((result) => {
                //if user clicks on delete
                if (result.value) {
                    // calling destroy method to delete
                @this.call('deleteResult', id);
                }
            });
        });
        });
    </script>

@endpush