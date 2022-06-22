<div class=""
     x-cloak
     x-data="{
        show: @entangle('show').defer,
        phase: @entangle('phase'),
        transition: @entangle('transition')
        }"
     x-init="
            $watch('show', value => {
                if (value) {
                    $('#project-status-change').modal('show');
                } else {
                    $('#project-status-change').modal('hide');
                    phase = false;
                }
            });

"
     x-on:keydown.escape.window="show = false;"
     x-on:close.stop="show = false;"
>
    <div class="frame-wrap m-0">
        <div class="d-flex flex-wrap">
            <div class="w-auto p-2 text-center">
                <div class="project-line subheader-block d-lg-flex align-items-center">
                    <div class="d-flex align-items-center p-2 mr-6">
                        <div class="d-flex align-items-center">
                            <h2>{{strlen($project->name)>10? substr($project->name,0,10).'...':$project->name}}</h2>
                            <div id="circularMenu1" class="circular-menu circular-menu-left" style="z-index: 99">
                                <a class="floating-btn"
                                   onclick="document.getElementById('circularMenu1').classList.toggle('active');">
                                    <i class="fa fa-bars"></i>
                                </a>
                                <menu class="items-wrapper">
                                    @can('view-files-project'||'manage-files-project')
                                        <a href="{{ route('projects.files', $project->id) }}"
                                           class="menu-item fal fa-paperclip" data-toggle="tooltip"
                                           data-original-title="Ver Archivos"></a>@endcan

                                    @can('view-events-project'||'manage-events-project')
                                        <a href="{{ route('projects.events', $project->id) }}"
                                           class="menu-item fal fa-line-height" data-toggle="tooltip"
                                           data-original-title="Ver Sucesos"></a>@endcan

                                    @can('view-reports-project')
                                        <a href="{{ route('projects.reportsIndex', $project->id) }}"
                                           class="menu-item fal fa-table" data-toggle="tooltip"
                                           data-original-title="Reportes"></a>@endcan

                                    @can('view-learnedLessons-project'||'manage-learnedLessons-project')
                                        <a href="{{ route('projects.lessons_learned', $project->id) }}"
                                           class="menu-item fal fa-book-open" data-toggle="tooltip"
                                           data-original-title="Lecciones Aprendidas"></a>@endcan

                                    @can('view-validations-project'||'manage-validations-project')
                                        <a href="{{ route('projects.validations', $project->id) }}"
                                           class="menu-item fal fa-check-circle" data-toggle="tooltip"
                                           data-original-title="Validaciones"></a>@endcan

                                    @can('view-reschedulings-project'||'manage-reschedulings-project')
                                        <a href="{{ route('projects.reschedulings', $project->id) }}"
                                           class="menu-item fal fa-clock" data-toggle="tooltip"
                                           data-original-title="Reprogramaciones"></a>@endcan

                                    @can('view-evaluations-project'||'manage-evaluations-project')
                                        <a href="{{ route('projects.evaluations', $project->id) }}"
                                           class="menu-item fal fa-book-medical" data-toggle="tooltip"
                                           data-original-title="Evaluaciones"></a>@endcan

                                    @can('view-administrativeTasks-project'||'manage-administrativeTasks-project')
                                        <a href="{{ route('projects.administrativeTasks', $project->id) }}"
                                           class="menu-item far fa-address-card" data-toggle="tooltip"
                                           data-original-title="Actividades Administrativas"></a>@endcan
                                </menu>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ol class="breadcrumb breadcrumb-lg breadcrumb-arrow mb-0 p-2">
                <li class="@if($project->phase->isActive(\App\States\Project\StartUp::class)) active @endif">
                    <a href="#">
                        <i class="fas fa-tasks"></i>
                        <span class="hidden-md-down">{{ \App\States\Project\StartUp::label()}}</span>
                    </a>
                </li>
                <li class="@if($project->phase->isActive(\App\States\Project\Planning::class)) active @endif">
                    <a href="#">
                        <i class="fas fa-pencil-alt"></i>
                        <span class="hidden-md-down">{{ \App\States\Project\Planning::label() }}</span>
                    </a>
                </li>
                <li class="@if($project->phase->isActive(\App\States\Project\Implementation::class)) active @endif">
                    <a href="#">
                        <i class="fas fa-play"></i>
                        <span class="hidden-md-down">{{ \App\States\Project\Implementation::label() }}</span>
                    </a>
                </li>
                <li class="@if($project->phase->isActive(\App\States\Project\Closing::class)) active @endif">
                    <a href="#">
                        <i class="fas fa-window-close"></i>
                        <span class="hidden-md-down">{{ \App\States\Project\Closing::label() }}</span>
                    </a>
                </li>
            </ol>
            @if($project->phase instanceof \App\States\Project\StartUp)
                <ol class="breadcrumb breadcrumb-lg breadcrumb-arrow mb-0 p-2">
                    <li class="@if($project->status->isActive(\App\States\Project\InProcess::class)) active @endif">
                        <a href="#"
                           @can('project-change-status') x-on:click="show = true; transition='En proceso';" @endcan>
                            <span class="badge border rounded-pill bg-white">1</span>
                            <span class="hidden-md-down">{{ \App\States\Project\InProcess::label() }}</span>
                        </a>
                    </li>
                    <li class="@if($project->status->isActive(\App\States\Project\InReview::class)) active @endif">
                        <a href="#"
                           @if($project->status->to() instanceof \App\States\Project\InReview) @can('project-change-status') x-on:click="show = true;" @endcan @endif >
                            <span class="badge border rounded-pill bg-white">2</span>
                            <span class="hidden-md-down">{{ \App\States\Project\InReview::label() }}</span>
                        </a>
                    </li>
                    <li class="@if($project->status->isActive(\App\States\Project\Formulated::class)) active @endif">
                        <a href="#"
                           @if($project->status->to() instanceof \App\States\Project\Formulated) @can('project-change-status') x-on:click="show = true" @endcan @endif >
                            <span class="badge border rounded-pill bg-white">3</span>
                            <span class="hidden-md-down">{{ \App\States\Project\Formulated::label() }}</span>
                        </a>
                    </li>
                    <li class="@if($project->status->isActive(\App\States\Project\Financed::class)) active @endif">
                        <a href="#"
                           @if($project->status->to() instanceof \App\States\Project\Pending) @can('project-change-status') x-on:click="show = true" @endcan @endif>
                            <span class="badge border rounded-pill bg-white">4</span>
                            <span class="hidden-md-down">{{ \App\States\Project\Financed::label() }}</span>
                        </a>
                    </li>
                </ol>
            @endif
            @if($project->phase instanceof \App\States\Project\Planning)
                <ol class="breadcrumb breadcrumb-lg breadcrumb-arrow mb-0 p-2">
                    <li class="@if($project->status->isActive(\App\States\Project\Pending::class)) active @endif">
                        <a href="#"
                           @if($project->status->to() instanceof \App\States\Project\Pending) @can('project-change-status') x-on:click="show = true" @endcan @endif>
                            <span class="badge border rounded-pill bg-white">1</span>
                            <span class="hidden-md-down">{{ \App\States\Project\Pending::label() }}</span>
                        </a>
                    </li>
                    <li class="@if($project->status->isActive(\App\States\Project\Completed::class)) active @endif">
                        <a href="#"
                           @if($project->status->to() instanceof \App\States\Project\Completed) @can('project-change-status') x-on:click="show = true" @endcan @endif>
                            <span class="badge border rounded-pill bg-white">2</span>
                            <span class="hidden-md-down">{{ \App\States\Project\Completed::label() }}</span>
                        </a>
                    </li>
                </ol>
            @endif
            @if($project->phase instanceof \App\States\Project\Implementation)
                <ol class="breadcrumb breadcrumb-lg breadcrumb-arrow mb-0 p-2">
                    <li class="@if($project->status->isActive(\App\States\Project\Execution::class)) active @endif">
                        <a href="#"
                           @can('project-change-status') x-on:click="show = true; transition='Ejecución'" @endcan>
                            <span class="badge border rounded-pill bg-white">1</span>
                            <span class="hidden-md-down">{{ \App\States\Project\Execution::label() }}</span>
                        </a>
                    </li>
                    <li class="@if($project->status->isActive(\App\States\Project\Canceled::class)) active @endif">
                        <a href="#"
                           @can('project-change-status') x-on:click="show = true; transition='Cancelado';" @endcan>
                            <span class="badge border rounded-pill bg-white">2</span>
                            <span class="hidden-md-down">{{ \App\States\Project\Canceled::label() }}</span>
                        </a>
                    </li>
                    <li class="@if($project->status->isActive(\App\States\Project\Completed::class)) active @endif">
                        <a href="#"
                           @can('project-change-status') x-on:click="show = true; transition='Completado'" @endcan>
                            <span class="badge border rounded-pill bg-white">3</span>
                            <span class="hidden-md-down">{{ \App\States\Project\Completed::label() }}</span>
                        </a>
                    </li>
                    <li class="@if($project->status->isActive(\App\States\Project\Discontinued::class)) active @endif">
                        <a href="#"
                           @can('project-change-status') x-on:click="show = true; transition='Suspendido'" @endcan>
                            <span class="badge border rounded-pill bg-white">4</span>
                            <span class="hidden-md-down">{{ \App\States\Project\Discontinued::label() }}</span>
                        </a>
                    </li>
                    <li class="@if($project->status->isActive(\App\States\Project\Extension::class)) active @endif">
                        <a href="#"
                           @can('project-change-status') x-on:click="show = true; transition= 'Extensión'" @endcan>
                            <span class="badge border rounded-pill bg-white">5</span>
                            <span class="hidden-md-down">{{ \App\States\Project\Extension::label() }}</span>
                        </a>
                    </li>
                </ol>
            @endif
            @if($project->phase instanceof \App\States\Project\Closing)
                <ol class="breadcrumb breadcrumb-lg breadcrumb-arrow mb-0 p-2">

                    <li class="@if($project->status->isActive(\App\States\Project\Pending::class)) active @endif">
                        <a href="#"
                           @can('project-change-status') x-on:click="show = true; transition='Pendiente'; " @endcan>
                            <span class="badge border rounded-pill bg-white">1</span>
                            <span class="hidden-md-down">{{ \App\States\Project\Pending::label() }}</span>
                        </a>
                    </li>
                    <li class="@if($project->status->isActive(\App\States\Project\Closed::class)) active @endif">
                        <a href="#"
                           @can('project-change-status') x-on:click="show = true; transition='Cerrado';" @endcan>
                            <span class="badge border rounded-pill bg-white">2</span>
                            <span class="hidden-md-down">{{ \App\States\Project\Closed::label() }}</span>
                        </a>
                    </li>
                </ol>
            @endif
        </div>
    </div>
    @if(session('company_id')===$project->company_id)
        <div class="frame-wrap mb-3">
            <div class="d-flex d-flex-row">
                @if($project->phase instanceof \App\States\Project\StartUp)
                    @can('manage-indexCard-project')
                        <a href="{{ route('projects.showIndex', $project->id) }}">
                                    <span class="btn btn-sm {{ $page == 'act' ? 'btn-success':' btn-info' }} mr-2"
                                          data-placement="top" title="Ficha"
                                          data-original-title="Ficha">
                                      <i class="fas fa-eye mr-1 "></i>  Ficha</span>
                        </a>
                    @endcan
                @endif

                @if($project->phase instanceof \App\States\Project\Planning)
                    @can('manage-governance-project')
                        <a href="{{ route('projects.team', $project->id) }}">
                      <span class="btn btn-sm {{ $page == 'team' ? 'btn-success':' btn-info' }} mr-2"
                            data-placement="top" title="{{trans('general.governance')}}"
                            data-original-title="{{trans('general.governance')}}">
                          <i class="fas fa-ball-pile mr-1"></i>  {{trans_choice('general.governance',0)}}</span>
                        </a>
                    @endcan
                @endif
                @can('manage-logicFrame-project')
                    <a href="{{ route('projects.logic-frame', $project->id) }}">
                                    <span class="btn btn-sm {{ $page == 'logic_frame' ? 'btn-success':' btn-info' }} mr-2"
                                          data-placement="top" title="{{trans('general.logic_frame')}}"
                                          data-original-title="{{trans('general.logic_frame')}}">
                                    <i class="fas fa-file-archive mr-1"></i>        {{substr(trans('general.logic_frame'),0,25) }}</span>
                    </a>
                @endcan
                @can('manage-stakeholders-project')

                    <a href="{{ route('projects.stakeholder', $project->id) }}">
                                   <span class="btn btn-sm {{ $page == 'stakeholders' ? 'btn-success':' btn-info' }} mr-2"
                                         data-placement="top" title="{{trans('general.stakeholders')}}"
                                         data-original-title="{{trans('general.stakeholders')}}"
                                   > <i class="fas fa-poll-people mr-1"></i>{{substr(trans('general.stakeholders'),0,25) }}</span>
                    </a>
                @endcan
                @can('manage-risks-project')
                    <a href="{{ route('projects.risks', $project->id) }}">
                                    <span class="btn btn-sm {{ $page == 'risks' ? 'btn-success':' btn-info' }} mr-2"
                                    ><i class="fas fa-engine-warning mr-1"></i> Riesgos</span>
                    </a>
                @endcan
                @if($project->phase instanceof \App\States\Project\StartUp)
                    @can('manage-formulatedDocument-project')
                        <a href="{{ route('projects.doc', $project->id) }}">
                                     <span class="btn btn-sm {{ $page == 'formulated_document' ? 'btn-success':' btn-info' }} mr-2"
                                           data-placement="top" title="Documento Formulado"
                                           data-original-title="Documento Formulado"
                                     > <i class="fas fa-file mr-1"></i>Documento Formulado</span>
                        </a>
                    @endcan
                    @can('manage-referentialBudget-project')
                        <a href="{{ route('projects.showReferentialBudget', $project->id) }}">
                                   <span class="btn btn-sm {{ $page == 'budget' ? 'btn-success':' btn-info' }} mr-2"
                                         data-placement="top" title="Presupuesto"
                                         data-original-title="Presupuesto"
                                   > <i class="fas fa-dollar-sign mr-1"></i>Presupuesto Referencial</span>
                        </a>
                    @endcan
                @endif

                @if(!($project->phase instanceof \App\States\Project\StartUp))
                    @can('manage-timetable-project')
                        <a href="{{ route('projects.activities', $project->id) }}">
                                    <span class="btn btn-sm {{ $page == 'activities' ? 'btn-success':' btn-info' }} mr-2"
                                          data-placement="top" title="{{trans('general.activities')}}"
                                          data-original-title="{{trans('general.activities')}}">
                                   <i class="fas fa-calendar mr-1"></i> Cronograma
                                    </span>
                        </a>
                    @endcan

                    @can('manage-calendar-project')
                        <a href="{{ route('projects.calendar', $project->id) }}">
                                    <span class="btn btn-sm {{ $page == 'calendar' ? 'btn-success':' btn-info' }} mr-2"
                                          data-placement="top" title="Calendario"
                                          data-original-title="Calendario">
                                   <i class="fas fa-calendar mr-1"></i> Calendario
                                    </span>
                        </a>
                    @endcan
                    @can('manage-activities-project')
                        <a href="{{ route('projects.activities_results', $project) }}">
                                    <span class="btn btn-sm {{ $page == 'activities_results' ? 'btn-success':' btn-info' }} mr-2"
                                          data-placement="top" title="{{trans_choice('general.activities',2)}}"
                                          data-original-title="{{trans_choice('general.activities',2)}}">
                                    <i class="fas fa-arrow-alt-from-top mr-1"></i>{{trans_choice('general.activities',2) }}
                                    </span>
                        </a>
                    @endcan

                    <a href="{{ route('projects.budgetDocumentReport', $project) }}">
                           <span class="btn btn-sm {{ $page == 'budget' ? 'btn-success':' btn-info' }} mr-2"
                                 data-placement="top" title="Presupuesto"
                                 data-original-title="Presupuesto"
                           > <i class="fas fa-dollar-sign mr-1"></i>Presupuesto</span>
                    </a>
                    @can('manage-acquisitions-project')
                        <a href="{{ route('projects.acquisitions', $project->id) }}">
                                    <span class="btn btn-sm {{ $page == 'acquisitions' ? 'btn-success':' btn-info' }} mr-2"
                                          data-placement="top" title="{{trans('general.acquisitions')}}"
                                          data-original-title="{{trans('general.acquisitions')}}">
                                      <i class="fas fa-bags-shopping mr-1"></i>  Adquisiciones</span>
                        </a>
                    @endcan
                    @can('manage-communication-project')
                        <a href="{{route('projects.communication', $project)}}">
                                    <span class="btn btn-sm {{ $page == 'communications' ? 'btn-success':' btn-info' }} mr-2"
                                          data-placement="top" title="{{trans('general.communication')}}"
                                          data-original-title="{{trans('general.communication')}}">
                                    <i class="fas fa-ballot mr-1"></i>    Comunicación
                                    </span>
                        </a>
                    @endcan

                @endif
                @can('view-summary-project')
                    <a href="{{ route('projects.showSummary', $project->id) }}">
                                    <span class="btn btn-sm {{ $page == 'summary' ? 'btn-success':' btn-info' }} mr-2"
                                          data-placement="top"
                                          title="Resumen de la Fase"
                                          data-original-title="Resumen de la Fase"
                                    > <i class="fas fa-folder-open mr-1"></i>Resumen</span>
                    </a>
                @endcan
            </div>
        </div>
    @endif

    <div class="modal fade" id="project-status-change" style="display: none;"
         data-backdrop="static" data-keyboard="false" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <template x-if="phase">
                        <h3 class="modal-title">{{ trans('general.change_phase') }}</h3>
                    </template>
                    <template x-if="!phase">
                        <h3 class="modal-title">{{ trans('general.change_status') }}</h3>
                    </template>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            x-on:click="show = false; transition=null">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <template x-if="phase">
                        <div class="d-flex align-items-center mb-6">

                            <div class="d-flex align-items-center flex-column">
                                <x-label-section>{{ trans('general.from') }}</x-label-section>
                                <span class="badge {{ $project->phase->color() }} fs-2x mr-3">
                          {{ $project->phase->label() }}
                            </span>
                            </div>

                            <span class="mr-3"><i class="fas fa-arrow-right color-success-500 fa-2x"></i></span>

                            <div class="d-flex align-items-center flex-column">
                                <x-label-section>{{ trans('general.to') }}</x-label-section>
                                <span class="badge @if($transition) {{ $project->phase->to($transition)->color() }}   @else     {{ $project->phase->to()->color() }} @endif fs-2x">
                                    @if($transition) {{$transition}}  @else      {{ $project->phase->to()->label() }} @endif
                            </span>
                            </div>
                        </div>
                    </template>
                    <template x-if="!phase">
                        <div class="d-flex align-items-center mb-6">
                            <div class="d-flex align-items-center flex-column">
                                <x-label-section>{{ trans('general.phase') }}</x-label-section>
                                <span class="badge fs-2x mr-3">{{ $project->phase }}:</span>
                            </div>

                            <div class="d-flex align-items-center flex-column">
                                <x-label-section>{{ trans('general.from') }}</x-label-section>
                                <span class="badge {{ $project->status->color() }} fs-2x mr-3">
                                {{ $project->status->label() }}
                            </span>
                            </div>

                            <span class="mr-3"><i class="fas fa-arrow-right color-success-500 fa-2x"></i></span>

                            <div class="d-flex align-items-center flex-column">
                                <x-label-section>{{ trans('general.to') }}</x-label-section>
                                <span class="badge @if($transition) {{ $project->status->to($transition)->color() }}   @else     {{ $project->status->to()->color() }} @endif fs-2x">
                                    @if($transition) {{$transition}}  @else      {{ $project->status->to()->label() }} @endif
                            </span>
                            </div>
                        </div>
                    </template>
                    <x-label-section>{{ trans('general.change_history') }}</x-label-section>

                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-2 content-detail"><span class="fw-700">{{ trans('general.from') }}</span>
                            </div>
                            <div class="col-1"></div>
                            <div class="col-2 content-detail"><span class="fw-700">{{ trans('general.to') }}</span>
                            </div>
                            <div class="col-4 content-detail"><span
                                        class="fw-700">{{ trans('general.updated_by') }}</span></div>
                            <div class="col-3 content-detail"><span class="fw-700">{{ trans('general.date') }}</span>
                            </div>
                        </div>
                        @foreach($project->statusChanges() as $change)
                            <div class="row mb-2">
                                <div class="col-2">
                                    <span class="badge {{ \App\Models\Projects\Project::statusColor($change->properties->get('old')['status']) }} mr-3">
                                        {{ $change->properties->get('old')['status'] }}
                                    </span>
                                </div>
                                <div class="col-1"><i class="fas fa-arrow-right color-success-500"></i></div>
                                <div class="col-2">
                                    <span class="badge {{ \App\Models\Projects\Project::statusColor($change->properties->get('attributes')['status']) }}">
                                        {{ $change->properties->get('attributes')['status'] }}
                                    </span>
                                </div>
                                <div class="col-4">
                                    <span class="mr-2">
                                        <img src="{{ asset_cdn('img/user.svg') }}" class="rounded-circle width-1">
                                    </span>
                                    {{ $change->causer->name }}</div>
                                <div class="col-3">{{ company_date($change->created_at) }}</div>
                            </div>
                        @endforeach
                    </div>
                    <hr>
                    <div class="panel-container show">
                        @if($viewOpening)
                            <div class="row">
                                <div class="col-10">
                                    <div class="form-group">
                                        <div class="frame-wrap">
                                            <x-form.modal.checkbox id="accountsOpening" label="{{ __('general.accounts_opening') }}"
                                                                   class="form-group col-sm-12"></x-form.modal.checkbox>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($viewSignature)
                            <div class="row">
                                <div class="col-10">
                                    <div class="form-group">
                                        <div class="frame-wrap">
                                            <x-form.modal.checkbox id="signatureAgreement" label="{{ __('general.signature_of_agreement') }}"
                                                                   class="form-group col-sm-12"></x-form.modal.checkbox>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <x-label-section>{{ trans('general.validations') }}</x-label-section>
                        <div class="frame-wrap demo">
                            <div class="demo">
                                @if($departments->validations)
                                    @foreach($departments->validations as $index => $department)
                                        <div @if(!in_array($department['id'],$arrayIdsDepartments)) style="pointer-events:none;" @endif>
                                            <x-form.modal.textarea id="justification.{{$index}}"
                                                                   label="{{ __('general.poa_request_justification') }}-{{$index}}"
                                                                   class="form-group col-12"
                                            >
                                            </x-form.modal.textarea>
                                            <div class="form-group col-12 text-center">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input wire:model="accept.{{$index}}" type="radio"
                                                           class="custom-control-input"
                                                           id="goalAnswerApproved{{$index}}"
                                                           name="accept{{$index}}"
                                                    >
                                                    <label class="custom-control-label"
                                                           for="goalAnswerApproved{{$index}}">{{ __('general.poa_approved') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input wire:model="decline.{{$index}}" type="radio"
                                                           class="custom-control-input"
                                                           id="golAnswerDenied{{$index}}" name="accept{{$index}}"
                                                    >
                                                    <label class="custom-control-label"
                                                           for="golAnswerDenied{{$index}}">{{ __('general.poa_denied') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @if($settings)
                            <table id="dt-basic-example"
                                   class="table table-bordered table-striped w-100 dataTable no-footer dtr-inline"
                                   role="grid"
                                   aria-describedby="dt-basic-example_info">
                                <thead>
                                <tr role="row">
                                    <th class="text-center"><span>{{trans('general.field')}}</span></th>

                                    <th class="text-center">{{trans('general.complete')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($project->get($settings['fields'])->first()->toArray() as $index => $item)
                                    <tr>
                                        <td class="text-center">
                                            {{trans('general.'.$index)}}
                                        </td>

                                        <td class="text-center"><i
                                                    class="fal {{!$project->{$index}?'fa-ban color-danger-700':'fa-check color-success-700 '}} fa-2x"></i>
                                        </td>

                                    </tr>
                                @endforeach
                                @foreach($settings['relations'] as $item)
                                    <tr>
                                        <td class="text-center">
                                            {{trans('general.'.$item)}}
                                        </td>
                                        <td class="text-center"><i
                                                    class="fal {{$project->{$item}->count()==0 ?'fa-ban color-danger-700':'fa-check color-success-700 '}} fa-2x"></i>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        @endif
                        @if($exists)
                            <div class="alert border-danger bg-transparent text-danger" role="alert">
                                No se puede cambiar de estado hasta que los campos obligatorios esten completos.
                            </div>
                        @else
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary shadow-none" data-dismiss="modal"
                                        x-on:click="show = false; transition=null">{{ trans('general.cancel') }}</button>
                                @if($phase)
                                    <button type="button" class="btn btn-success border-0 shadow-none"
                                            wire:click="changePhase">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                                              wire:target="changePhase" wire:loading></span>
                                        {{ trans('general.change') }}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-success border-0 shadow-none"
                                            wire:click="changeStatus">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                                              wire:target="changeStatus" wire:loading></span>
                                        {{ trans('general.change') }}
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('page_script')
    <script>
        Livewire.on('closeModalValidations', () => $('#project-status-change').modal('toggle'));
    </script>
@endpush