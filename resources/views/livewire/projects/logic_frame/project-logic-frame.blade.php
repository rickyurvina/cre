<div>
    <div class="d-flex flex-column">
        <div class="d-flex flex-nowrap">
            <div class="flex-grow-1 w-100" style="overflow: hidden auto">
                <ul class="nav nav-tabs nav-tabs-clean" role="tablist">
                    <li class="nav-item" wire:ignore>
                        <a class="nav-link active" data-toggle="tab" href="#tab-logic-frame" role="tab"
                           aria-selected="true">{{ trans('general.general') }}</a>
                    </li>
                    <li class="nav-item" wire:ignore>
                        <a class="nav-link" data-toggle="tab" href="#tab-indicators" role="tab" aria-selected="false">Indicadores</a>
                    </li>
                    <li class="text-right w-100 m-0">
                        <a class="btn btn-outline-primary btn-xs shadow-0" wire:click="downloadLogicFrameExcel('{{$project->id}}')">
                            <i class="fas fa-file-excel"></i> Marco Lógico</a>
                    </li>
                    <br>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="tab-logic-frame" role="tabpanel" wire:ignore.self>
                        <div class="d-flex mb-3">
                            <div class="input-group bg-white shadow-inset-2 w-25 mr-2">
                                <input type="text" class="form-control border-right-0 bg-transparent pr-0"
                                       placeholder="{{ trans('general.filter') . ' ' . trans_choice('general.result', 2) }} ..."
                                       wire:model="search">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-transparent border-left-0">
                                        <i class="fal fa-search"></i>
                                    </span>
                                </div>
                            </div>

                            @if(count($objectives) > 0)
                                <div class="btn-group">
                                    <button class="btn btn-outline-secondary dropdown-toggle @if(count($selectedObjectives) > 0) filtered @endif"
                                            type="button" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                        {{ trans('general.objectives_name')}}
                                        @if(count($selectedObjectives) > 0)
                                            <span class="badge bg-white ml-2">{{ count($selectedObjectives) }}</span>
                                        @endif
                                    </button>
                                    <div class="dropdown-menu" style="min-width: 30rem !important;">
                                        @foreach($objectives as $objective)
                                            <div class="dropdown-item">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                           id="i-program-{{ $objective['id'] }}"
                                                           wire:model="selectedObjectives"
                                                           value="{{ $objective['id'] }}">
                                                    <label class="custom-control-label"
                                                           for="i-program-{{ $objective['id'] }}">{{ strlen($objective['name'])>40? substr($objective['name'], 0,40).'...': $objective['name']  }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="dropdown-divider"></div>
                                        <div class="dropdown-item">
                                            <span wire:click="$set('selectedObjectives', [])">{{ trans('general.delete_selection') }}</span>
                                        </div>
                                        <div class="dropdown-item">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="showProgramPanel" checked="" wire:model="showProgramPanel">
                                                <label class="custom-control-label"
                                                       for="showProgramPanel">{{ trans('general.show_panel_objectives') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(count($selectedObjectives) > 0 || $search != '')
                                <a class="btn btn-outline-default ml-2"
                                   wire:click="clearFilters()">{{ trans('common.clean_filters') }}</a>
                            @endif
                            <button type="button" class="btn btn-success border-0 shadow-0 ml-2" data-toggle="modal"
                                    data-target="#project-create-specific-objective">{{ trans('general.create')}}  {{trans('general.objectives_name')}}
                            </button>

                            <x-tooltip-help
                                    message="{{$messages->where('code','marco_logico')->first()->description}}"></x-tooltip-help>
                        </div>
                        <div class="d-flex flex-wrap align-items-start">
                            @if($showProgramPanel)
                                <div class="panel w-25">
                                    <div class="panel-hdr">
                                        <h2>
                                            {{ trans('general.objectives_name') }}
                                        </h2>
                                        <div class="panel-toolbar">

                                            <button class="btn btn-panel" data-toggle="tooltip" data-offset="0,10"
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
                                                @foreach($project->objectives as $index => $objective)
                                                    <div class="card mb-1">
                                                        <div class="card-header">
                                        <span href="javascript:void(0);" class="card-title py-2 collapsed w-90"
                                              data-toggle="collapse"
                                              data-target="#accordion-p-{{ $objective['id'] }}"
                                              aria-expanded="false">
                                            <span class="color-item shadow-hover-5 mr-2 cursor-default"
                                                  style="background-color: {{ $objective['color'] }};">
                                            </span>
                                            <span wire:ignore wire:key="{{time().$objective->code}}" class="w-75">
                                                 <livewire:components.input-text :modelId="$objective['id']"
                                                                                 class="\App\Models\Projects\Objectives\ProjectObjectives"
                                                                                 field="name"
                                                                                 :rules="'required|max:255|min:3'"
                                                                                 defaultValue="{{ $objective['name'] }}"
                                                                                 :key="time().$objective['id']"/>
                                                        </span>

                                                        <span class="ml-auto">
                                                            <span class="collapsed-reveal">
                                                                <i class="fal fa-minus fs-xl"></i>
                                                            </span>
                                                            <span class="collapsed-hidden">
                                                                <i class="fal fa-plus fs-xl"></i>
                                                            </span>
                                                        </span>
                                                    </span>
                                                        </div>
                                                        <div id="accordion-p-{{ $objective['id'] }}" class="collapse">
                                                            <div class="card-body">
                                                                <div class="d-flex flex-column">

                                                                    <div class="dropdown-item cursor-pointer"
                                                                         style="border-radius: 4px" data-toggle="modal"
                                                                         data-target="#project-create-results"
                                                                         data-objective-id="{{ $objective['id'] }}">
                                                                        <i class="fas fa-plus-circle mr-2"></i> {{ trans('general.create') . ' ' . trans_choice('general.result', 1) }}
                                                                    </div>

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
                                                                                    <span class="bg-gray-50" dir="auto">
                                                                                        <span>{{ $objective->indicators->count()>1?  $objective->indicators->count().'-Indicadores':$objective->indicators->count().'-Indicador'}}</span>
                                                                                    </span>
                                                                                </span>
                                                                                </div>

                                                                            </div>
                                                                            <div class="dropdown-menu fadeindown  m-0 dropdown-menu-side show-child-on-hover pr-4">
                                                                                @foreach($objective->indicators as $indicator)
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
                                                                                <div class="dropdown-item m-2 d-flex active mt-4"
                                                                                     wire:click="$emit('show', 'App\\Models\\Projects\\Objectives\\ProjectObjectives', '{{ $objective->id }}')">
                                                                                    <i class="fal fa-plus mr-2"></i>
                                                                                    <span class="text-component"
                                                                                          dir="auto">
                                                                                        <span>Agregar Indicador</span>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @if($objective->results->count()<1)
                                                                        <div class="dropdown-item cursor-pointer"
                                                                             style="border-radius: 4px"
                                                                             data-toggle="modal"
                                                                             wire:click="$emit('triggerDeleteObjective', '{{ $objective->id }}')">
                                                                            <i class="fal fa-trash-alt mr-2"></i> {{  trans_choice('general.delete', 1) }}
                                                                        </div>
                                                                    @endif
                                                                    <h6 class="m-0 text-muted">{{ __('general.color') }}</h6>
                                                                    <livewire:components.color-palette
                                                                            :modelId="$objective['id']"
                                                                            :key="time().$loop->index"
                                                                            class="App\Models\Projects\Objectives\ProjectObjectives"
                                                                            field="color"/>
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
                            <div class="w-75 pl-2">
                                <div class="table-responsive">
                                    <table class="table table-light table-hover">
                                        <thead>
                                        <tr>
                                            <th class="w-5 table-th">{{__('general.code')}}</th>
                                            <th class="w-25 table-th">{{__('general.name')}}</th>
                                            <th class="w-20 table-th">{{__('general.responsable')}}</th>
                                            <th class="w-10 table-th">Supuestos</th>
                                            <th class="w-10 table-th">{{trans_choice('general.indicators',2)}}</th>
                                            <th class="w-10 table-th">{{__('general.services')}}</th>
                                            <th class="w-10 table-th"><a href="#">{{ trans('general.actions') }} </a>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($results as $index => $item)
                                            <tr class="tr-hover" wire:loading.class.delay="opacity-50" wire:key="{{time().$item->objective->id.$index}}" wire:ignore.self>
                                                <td class="w-10">
                                                    <div class="d-flex align-items-center">
                                                        <span class="color-item shadow-hover-5 mr-2 cursor-default"
                                                              style="background-color: {{ $item->color }}"></span>
                                                        <div wire:key="{{time().$item->code}}" wire:ignore>
                                                            <livewire:components.input-inline-edit :modelId="$item->id"
                                                                                                   class="\App\Models\Projects\Activities\Task"
                                                                                                   field="code"
                                                                                                   :rules="'required|max:5|alpha_num|alpha_dash|unique:prj_tasks,code,' . $item->id . ',id,objective_id,' . $item->objective->id. ',type,project'"
                                                                                                   defaultValue="{{$item->code ?? '' }}"
                                                                                                   :key="time().$item->id"
                                                            />
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div wire:key="{{time().$item->text}}" style="width: 300px; !important;" wire:ignore>
                                                        <livewire:components.input-text :modelId="$item->id"
                                                                                        class="\App\Models\Projects\Activities\Task"
                                                                                        field="text"
                                                                                        :rules="'required|max:200|min:5'"
                                                                                        defaultValue="{{$item->text ?? __('general.add_name') }}"
                                                                                        :key="time().$item->id"/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div wire:key="{{time().$item->id.'user'}}" wire:ignore>
                                                        <livewire:components.dropdown-user :modelId="$item->id"
                                                                                           modelClass="\App\Models\Projects\Activities\Task"
                                                                                           field="owner_id"
                                                                                           :key="time().$item->id"
                                                                                           :user="$item->responsible"
                                                                                           :key="time().$item->id"/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div wire:ignore wire:key="{{time().$item->id}}">
                                                        <livewire:components.input-text :modelId="$item->id"
                                                                                        class="\App\Models\Projects\Activities\Task"
                                                                                        field="assumptions"
                                                                                        defaultValue="{{$item->assumptions }}"
                                                                                        :key="time().$item->id"/>

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
                                                                            <span class="text-component" dir="auto">
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

                                                <td class="text-center">
                                                    <div class="d-flex align-items-center">
                                                        <span>
                                                            {{$item->services->count()??'0'}}
                                                        </span>
                                                        <button class="border-0 bg-transparent"
                                                                data-toggle="modal"
                                                                data-target="#project-create-services"
                                                                data-result-id="{{  $item->id }}"
                                                        ><i class="fas fa-plus-circle mr-1 text-info"
                                                            data-placement="top" title="Añadir Servicios"
                                                            data-original-title="Añadir Servicios"></i>
                                                        </button>

                                                    </div>
                                                </td>
                                                <td>
                                                    @if($item->childs->count()<1)
                                                        <button class="border-0 bg-transparent"
                                                                wire:click="$emit('triggerDelete', '{{ $item->id }}')"
                                                                data-toggle="tooltip"
                                                                data-placement="top" title="Eliminar"
                                                                data-original-title="Eliminar"><i
                                                                    class="fas fa-trash mr-1 text-danger"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                                                        <span class="color-fusion-500 fs-3x py-3"><i
                                                                                                    class="fas fa-exclamation-triangle color-warning-900"></i> No se encontraron resultados</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <x-pagination :items="$results"/>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-indicators" role="tabpanel" wire:ignore.self>
                        <div class="pl-2 pt-2">
                            <div class="content-detail">
                                <div class="d-flex flex-column">
                                    <div class="d-flex flex-nowrap mt-2">
                                        <div class="w-100">
                                            <div class="d-flex mr-auto">
                                                <a class="btn btn-success border-0 shadow-0 mr-2 mb-1 mt-0"
                                                   style="color:white;"
                                                   wire:click="$emit('show', 'App\\Models\\Projects\\Project', '{{ $project->id }}')">
                                                    {{ trans('general.create') }} Indicador
                                                </a>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped w-100 dataTable no-footer dtr-inline" wire:loading.class.delay="opacity-50">
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
                                                            <th class="w-5 table-th">{{$indicator->code}}</th>
                                                            <th class="w-auto table-th">{{$indicator->name}}</th>
                                                            <th class="w-15 table-th">{{$indicator->total_goal_value}}</th>
                                                            <th class="w-5 table-th">{{$indicator->total_actual_value}}</th>
                                                            <td>
                                                                <span class="form-label badge {{$indicator->getStateIndicator()[0]?? null}}  badge-pill">{{$indicator->getStateIndicator()[1]?? null}}</span>
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
                                                                                    class="fas fa-trash-alt" aria-expanded="false"
                                                                                    data-toggle="tooltip" data-placement="top" title=""
                                                                                    data-original-title="Eliminar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="8" class="table-info h-25 text-center">Objetivos
                                                        </td>
                                                    </tr>
                                                    @foreach($objectives->sortBy('id') as $objective)
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
                                                                    <span class="form-label badge {{$indicator->getStateIndicator()[0]?? null}}  badge-pill">{{$indicator->getStateIndicator()[1]?? null}}</span>
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
                                                                                        class="fas fa-trash-alt" aria-expanded="false"
                                                                                        data-toggle="tooltip" data-placement="top" title=""
                                                                                        data-original-title="Eliminar"></i></span>
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
                                                                    <span class="form-label badge {{$indicatorR->getStateIndicator()[0]?? null}}  badge-pill">{{$indicatorR->getStateIndicator()[1]?? null}}</span>
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
                                                                        <div class="w-25 cursor-pointer"
                                                                             wire:click="$emit('triggerDeleteIndicator', '{{ $indicatorR->id }}')">
                                                                            <span class="color-danger-700"><i
                                                                                        class="fas fa-trash-alt" aria-expanded="false"
                                                                                        data-toggle="tooltip" data-placement="top" title=""
                                                                                        data-original-title="Eliminar"></i></span>
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
        $('#project-create-services').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let resultId = $(e.relatedTarget).data('result-id');
            //Livewire event trigger
            Livewire.emit('loadServices', resultId);
        });

        document.addEventListener('DOMContentLoaded', function () {
            $('div.dropdown-item, .color-item').on('click', function () {
                $(".open-drop").dropdown("hide");
            });

        @this.on('triggerDelete', id => {
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
                @this.call('delete', id);
                }
            });
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

        @this.on('triggerDeleteObjective', id => {
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
                @this.call('deleteObjective', id);
                }
            });
        });
        });
    </script>

@endpush