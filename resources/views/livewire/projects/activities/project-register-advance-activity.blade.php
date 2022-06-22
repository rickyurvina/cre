<div>
    <div wire:ignore.self class="modal fade" id="register-advance-activity" tabindex="-1" role="dialog"
         aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="max-width: 70rem;">
            @if($task)
                <div class="modal-content">
                    <div class="modal-header" style="padding-bottom: 0op !important;; margin-bottom: 0px !important;">
                        <div class="d-flex flex-wrap w-65">
                            <div class="w-100">
                                <livewire:components.input-text :modelId="$task->id"
                                                                class="\App\Models\Projects\Activities\Task"
                                                                field="text"
                                                                :title="true"
                                                                defaultValue="{{ $task->text }}"/>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap p-2 w-35">
                            <div class="mr-2">
                                <x-label-detail>Progreso: <small
                                            class="badge badge-success">{{ $progressBarSubActiviites}}%</small>
                                </x-label-detail>
                            </div>
                            <span class="badge badge-info">{{$task->company->name}}</span>
                        </div>
                        <button wire:click="resetForm" type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding-top: 0px !important; margin-top: 0px !important;">
                        <div class="content-detail">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-nowrap">
                                    <div class="flex-grow-1 w-65" style="overflow: hidden auto">
                                        <div class="mt-2">
                                            <x-label-section>{{ trans('general.description') }}</x-label-section>
                                            <livewire:components.input-text-editor-inline-editor
                                                    :modelId="$task->id"
                                                    class="\App\Models\Projects\Activities\Task"
                                                    field="description"
                                                    :placeholder="trans('general.add_description')"
                                                    :defaultValue="$task->description"/>
                                        </div>

                                        <div class="mt-2">
                                            <x-label-section>Actividad</x-label-section>
                                            <div class="demo-v-spacing mt-2">
                                                <nav class="nav nav-pills" wire:ignore.self>
                                                    @if($project->phase instanceof  \App\States\Project\Planning)
                                                        <a href="#plan_goals"
                                                           class="nav-item nav-link btn-xs @if($project->phase instanceof  \App\States\Project\Planning) active @endif"
                                                           data-toggle="pill" wire:ignore.self>
                                                            Planificación de Metas
                                                        </a>
                                                    @endif
                                                    @if($project->phase instanceof  \App\States\Project\Implementation)
                                                        {{--                                                        <a href="#assigments" class="nav-item nav-link btn-xs"--}}
                                                        {{--                                                           data-toggle="pill" wire:ignore.self>--}}
                                                        {{--                                                            Asignaciones--}}
                                                        {{--                                                        </a>--}}
                                                        <a href="#progress"
                                                           class="nav-item nav-link active btn-xs @if($project->phase instanceof  \App\States\Project\Implementation) active @endif"
                                                           data-toggle="pill" wire:ignore.self>
                                                            Avance Metas
                                                        </a>
                                                        <a href="#work_log" class="nav-item nav-link btn-xs"
                                                           data-toggle="pill" wire:ignore.self>
                                                            Registro de Trabajo
                                                        </a>
                                                    @endif
                                                    <a href="#child_issues" class="nav-item nav-link btn-xs"
                                                       data-toggle="pill" wire:ignore.self>
                                                        SubTareas
                                                    </a>
                                                    <a href="#budget" class="nav-item nav-link btn-xs"
                                                       data-toggle="pill" wire:ignore.self>
                                                        Partidas Presupuestarias
                                                    </a>
                                                    <a href="#files" class="nav-item nav-link btn-xs" data-toggle="pill"
                                                       wire:ignore.self>
                                                        Adjuntos y Comentarios
                                                    </a>
                                                </nav>
                                                <div class="tab-content py-3" wire:ignore.self>

                                                    <div class="tab-pane fade show @if($project->phase instanceof  \App\States\Project\Planning) active @endif"
                                                         id="plan_goals"
                                                         role="tabpanel" wire:ignore.self>
                                                        <x-label-detail>Planificación de Metas</x-label-detail>
                                                        <div class="form-group col-lg-12 required">
                                                            <div class="input-group d-flex flex-row  bg-white shadow-inset-2">
                                                                @foreach($task->goals as $goal)
                                                                    <div class="p-2">
                                                                        <x-form.inputs.text type="number" name="goals[]"
                                                                                            label="{{$goal->period->format('M,Y')}}"
                                                                                            id="goals[]"
                                                                                            wire:model.defer="goals.{{ $goal->id  }}"
                                                                                            value="{{$goal->goal??0}}"/>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="w-30 mx-auto">
                                                                <div class="d-flex justify-content-center">
                                                                    <x-label-detail>{{ trans('general.total') }}</x-label-detail>
                                                                    <x-content-detail>{{ array_sum($this->goals) }}</x-content-detail>
                                                                </div>
                                                            </div>
                                                            <div class="text-center p-2">
                                                                <button wire:click="updateGoals()"
                                                                        class="btn btn-success">
                                                                    <i class="fas fa-trash-alt pr-2"></i> {{ trans('general.save') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{--                                                    <div class="tab-pane fade show" id="assigments" role="tabpanel"--}}
                                                    {{--                                                         wire:ignore.self>--}}
                                                    {{--                                                        <div class="card">--}}
                                                    {{--                                                            <div class="table-responsive" wire:ignore.self>--}}
                                                    {{--                                                                <table class="table table-hover m-0">--}}
                                                    {{--                                                                    <thead class="bg-primary-50">--}}
                                                    {{--                                                                    <tr>--}}
                                                    {{--                                                                        <th class="w-auto">{{ trans('general.assigned_to')}}</th>--}}
                                                    {{--                                                                        <th class="w-60">{{ trans('general.name')}}</th>--}}
                                                    {{--                                                                        <th class="w-auto">{{ trans('general.hours_per_day')}}</th>--}}
                                                    {{--                                                                    </tr>--}}
                                                    {{--                                                                    </thead>--}}
                                                    {{--                                                                    <tbody>--}}
                                                    {{--                                                                    @foreach( $users as $item)--}}
                                                    {{--                                                                        <tr>--}}
                                                    {{--                                                                            <th>--}}
                                                    {{--                                                                                <div class="custom-control custom-checkbox">--}}
                                                    {{--                                                                                    <input type="checkbox"--}}
                                                    {{--                                                                                           class="custom-control-input"--}}
                                                    {{--                                                                                           id="isSelected.{{$item->id}}"--}}
                                                    {{--                                                                                           wire:model.defer="isSelected.{{$item->id}}">--}}
                                                    {{--                                                                                    <label class="custom-control-label"--}}
                                                    {{--                                                                                           for="isSelected.{{$item->id}}"></label>--}}
                                                    {{--                                                                                </div>--}}
                                                    {{--                                                                            </th>--}}
                                                    {{--                                                                            <th>{{$item->getFullName() ?? ''}}</th>--}}
                                                    {{--                                                                            <th class="w-10">--}}
                                                    {{--                                                                                <select class="form-control"--}}
                                                    {{--                                                                                        id="example-select"--}}
                                                    {{--                                                                                        wire:model.defer="hours.{{$item->id}}">--}}
                                                    {{--                                                                                    @for($i=1; $i<=14; $i++)--}}
                                                    {{--                                                                                        <option value="{{$i}}">{{$i}}h--}}
                                                    {{--                                                                                        </option>--}}
                                                    {{--                                                                                    @endfor--}}
                                                    {{--                                                                                </select>--}}
                                                    {{--                                                                            </th>--}}
                                                    {{--                                                                        </tr>--}}
                                                    {{--                                                                    @endforeach--}}
                                                    {{--                                                                    </tbody>--}}
                                                    {{--                                                                </table>--}}
                                                    {{--                                                            </div>--}}
                                                    {{--                                                            <hr>--}}
                                                    {{--                                                            <div class="text-center mb-2">--}}
                                                    {{--                                                                <button wire:click="saveUsers" class="btn btn-success">--}}
                                                    {{--                                                                    <i class="fas fa-save pr-2"></i> {{ trans('general.save') }}--}}
                                                    {{--                                                                </button>--}}
                                                    {{--                                                            </div>--}}
                                                    {{--                                                        </div>--}}
                                                    {{--                                                    </div>--}}
                                                    <div class="tab-pane fade show @if($project->phase instanceof  \App\States\Project\Implementation) active @endif"
                                                         id="progress"
                                                         role="tabpanel" wire:ignore.self>
                                                        <div class="form-group col-lg-12 required">
                                                            <div class="d-flex flex-wrap">
                                                                <x-label-detail>Avances</x-label-detail>
                                                                <div class="d-flex w-100">
                                                                    <div class="form-group col-lg-12 required">
                                                                        <div class="d-flex flex-wrap">
                                                                            @foreach($task->goals as $goal)
                                                                                <div class="d-flex flex-wrap align-items-center justify-content-between w-30 mr-2">
                                                                                    <div class="form-group w-50 pr-1">
                                                                                        <label class="form-label fw-700"
                                                                                               for="goals.{{ $loop->index }}.goal">{{$goal->period->format('M,Y')}}</label>
                                                                                        <input type="text"
                                                                                               id="goals.{{ $loop->index }}.goal"
                                                                                               class="form-control"
                                                                                               placeholder="Planificado"
                                                                                               value="{{$goal->goal??0}}"
                                                                                               readonly="readonly">
                                                                                        <span class="help-block">
                                                                                           Planificado.
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="form-group w-50">
                                                                                        <input type="text"
                                                                                               id="goals.{{ $loop->index }}.progress"
                                                                                               class="form-control"
                                                                                               placeholder="Ejecutado"
                                                                                               value="{{$goal->progress??0}}"
                                                                                               readonly="readonly">
                                                                                        <span class="help-block">
                                                                                             Ejecutado.
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex w-50">
                                                                    <x-label-detail>Periodo</x-label-detail>
                                                                    <div class="detail">
                                                                        <select class="form-control" id="example-select"
                                                                                wire:model="period">
                                                                            <option value="">Escoger Periodo</option>
                                                                            @foreach($task->goals as $goal)
                                                                                <option value="{{$goal->id}}">{{$goal->period->format('M,Y')}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex w-50 pl-2">
                                                                    <x-label-detail>Sumar Avance</x-label-detail>
                                                                    <div class="detail">
                                                                        <input type="number" class="form-control"
                                                                               name="advance" id="advance"
                                                                               wire:model.defer="advance">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if($taskDetail)
                                                                <div class="text-center p-2">
                                                                    <button wire:click="updateProgress()"
                                                                            class="btn btn-success ">
                                                                        <i class="fas fa-save btn-xs pr-2"></i>
                                                                        Registrar Avance
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade show" id="work_log" role="tabpanel"
                                                         wire:ignore.self>
                                                        <div>
                                                            @forelse($workLogs->take($workLogCount) as $workLog)
                                                                <div class="row mt-1 mb-1"
                                                                     style=" box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015);">
                                                                    <div class="col-1 mt-2">
                                                                        @if($workLog->user->picture)
                                                                            <span class="mr-1 ml-2">
                                                                             <img src="http://cre.test/img/user.svg"
                                                                                  class="rounded-circle width-2">
                                                                        </span>
                                                                        @else
                                                                            <span class="mr-1 ml-2">
                                                                                 <img src="http://cre.test/img/user_off.png"
                                                                                      class="rounded-circle width-2">
                                                                           </span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-11">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <div class="d-flex flex-wrap p-2 pb-0">
                                                                                    <div class="w-auto mr-2">
                                                                                        <strong class="font-weight-bold">
                                                                                            {{$workLog->user->getFullName() ?? ''}}
                                                                                        </strong>
                                                                                        <strong>
                                                                                            registró
                                                                                        </strong>
                                                                                    </div>
                                                                                    <div class="w-auto ml-2 mr-2"
                                                                                         style="margin-top:-1.5% !important;">
                                                                                        <div class="d-flex flex-wrap">
                                                                                            <div class="w-80">
                                                                                                <livewire:components.input-text
                                                                                                        :modelId="$workLog->id"
                                                                                                        class="\App\Models\Projects\Activities\TaskWorkLog"
                                                                                                        field="value"
                                                                                                        :title="false"
                                                                                                        defaultValue="{{$workLog->value}}"
                                                                                                        eventLivewire="workLogEdited"
                                                                                                        :key="time().$workLog->id"/>
                                                                                            </div>
                                                                                            <div class="w-auto">
                                                                                                <label class="mt-2">h</label></div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="w-auto">
                                                                                        {{$workLog->created_at ? $workLog->created_at->format('j F, Y'):''}}
                                                                                        {{$workLog->created_at ? $workLog->created_at->format('g:i A'):''}}
                                                                                        <a class="p-3"
                                                                                           href="javascript:void(0)"
                                                                                           wire:click="deleteWorkLog({{$workLog->id}})">
                                                                                            <i class="fas fa-trash mr-1 text-danger"
                                                                                               data-toggle="tooltip"
                                                                                               data-placement="top"
                                                                                               title=""
                                                                                               data-original-title="Eliminar"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            @empty
                                                                No se han registrado avances de tiempo
                                                            @endforelse
                                                        </div>
                                                        @if($workLogs->count()>=$workLogCount && $workLogs->count()>0)
                                                            <div class="col-12">
                                                                <div class="p-3 text-center">
                                                                    <a href="javascript:void(0);"
                                                                       wire:click="chargeWorkLog"
                                                                       class="btn-link font-weight-bold">{{trans('general.see_more')}}
                                                                        ({{$this->workLogs->count()-$workLogCount}})</a>f
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="col-12">
                                                                <div class="p-3 text-center">
                                                                    <a href="javascript:void(0);"
                                                                       wire:click="chargeWorkLog({{true}})"
                                                                       class="btn-link font-weight-bold">{{trans('general.close')}}</a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="tab-pane fade show" id="child_issues" role="tabpanel"
                                                         wire:ignore.self>
                                                        <div class="d-flex flex-wrap mt-1">
                                                            <x-label-section>Sub Tareas</x-label-section>
                                                            <div class="ml-auto mr-3">
                                                                <a href="javascript:void(0);" class="color-black"
                                                                   wire:click="$set('showAddActivity', true)">
                                                                    <i class="fal fa-plus fa-1x"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-wrap mt-1">
                                                            <div class="w-75">
                                                                <div class="progress">
                                                                    <div class="progress-bar progress-bar-striped bg-success"
                                                                         role="progressbar"
                                                                         style="width: {{$progressBarSubActiviites}}%"
                                                                         aria-valuenow="{{$progressBarSubActiviites}}"
                                                                         aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <span class="ml-auto fs-1x fw-500 mr-2"
                                                                  style="color: rgb(107, 119, 140)">{{$progressBarSubActiviites}} % Hecho</span>
                                                        </div>
                                                        @foreach($activitiesTask->take($countActivityTasks) as $index => $activityTask)
                                                            <div class="d-flex flex-wrap mt-2 w-100" wire:ignore wire:key="{{time().$activityTask->id.$index}}">
                                                                <i class="fal fa-bring-forward color-info-800 ml-1 mt-3 w-5"></i>
                                                                <div class="fs-1x color-info-600 mr-2">
                                                                    <div wire:ignore wire:key="{{time().$activityTask->id}}">
                                                                        <livewire:components.input-text
                                                                                :modelId="$activityTask->id"
                                                                                class="\App\Models\Projects\Activities\ActivityTask"
                                                                                field="code"
                                                                                :rules="'required|max:5|alpha_num|alpha_dash'"
                                                                                :key="time().$activityTask->id"
                                                                                defaultValue="{{ $activityTask->code }}"/>
                                                                    </div>
                                                                </div>
                                                                <div wire:ignore wire:key="{{time().$activityTask->id}}" class="w-50">
                                                                    <livewire:components.input-text
                                                                            :modelId="$activityTask->id"
                                                                            class="\App\Models\Projects\Activities\ActivityTask"
                                                                            field="name"
                                                                            :rules="'required|max:250|min:3'"
                                                                            :key="time().$activityTask->id"
                                                                            defaultValue="{{ $activityTask->name }}"/>
                                                                </div>

                                                                <div class="d-flex w-25 ml-auto">
                                                                    <div class="w-5 mt-2 mr-2">
                                                                        <a class="p-0" href="javascript:void(0)"
                                                                           wire:click="deleteSubTaskActivity({{ $activityTask->id }})">
                                                                            <i class="fas fa-trash mr-1 mt-2 text-danger" data-toggle="tooltip"
                                                                               data-placement="top"
                                                                               title="" data-original-title="Eliminar"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div wire:ignore
                                                                         wire:key="{{time().$activityTask->id}}" style="z-index:99;">
                                                                        <livewire:components.dropdown-user
                                                                                :modelId="$activityTask->id"
                                                                                modelClass="\App\Models\Projects\Activities\ActivityTask"
                                                                                field="user_id"
                                                                                :user="$activityTask->user"
                                                                                :usersAdd="$users"
                                                                                onlyIcon="true"
                                                                                toLeft="true"
                                                                                event="notifyUser"
                                                                                :key="time().$activityTask->id"
                                                                        />
                                                                    </div>

                                                                    @if($project->phase instanceof  \App\States\Project\Implementation)
                                                                        <div wire:ignore
                                                                             wire:key="{{time().$activityTask->id}}" class="w-auto">
                                                                            <livewire:components.dropdown-simple
                                                                                    :modelId="$activityTask->id"
                                                                                    modelClass="\App\Models\Projects\Activities\ActivityTask"
                                                                                    :values="\App\Models\Projects\Activities\Task::STATUSES_DD"
                                                                                    field="status"
                                                                                    :key="time().$activityTask->id"
                                                                                    selfEventEmited="statusUpdatedSubActivity"
                                                                                    :defaultValue="\App\Models\Projects\Activities\Task::STATUSES_DD[$activityTask->status]"
                                                                            />
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        <div class="d-flex frame-wrap mt-1" wire:ignore.self
                                                             wire:key="{{time()}}">
                                                            @if($activitiesTask->count()>=$countActivityTasks && $activitiesTask->count()>0)
                                                                <div class="col-12">
                                                                    <div class="p-3 text-center">
                                                                        <a href="javascript:void(0);"
                                                                           wire:click="chargeActivityTask"
                                                                           class="btn-link font-weight-bold">{{trans('general.see_more')}}
                                                                            ({{$activitiesTask->count()-$countActivityTasks}}
                                                                            )</a>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        @if($showAddActivity)
                                                            <div class="d-flex flex-wrap mt-2" wire:ignore.self>
                                                                <div class="input-group" style="width: 85% !important;">
                                                                    <input type="text"
                                                                           class="form-control col-2 @error($codeActivityTask) is-invalid @enderror"
                                                                           placeholder="{{ trans('general.code') }}"
                                                                           id="name-f"
                                                                           wire:model.defer="codeActivityTask">
                                                                    <input type="text"
                                                                           class="form-control @error($nameActivityTask) is-invalid @enderror"
                                                                           placeholder="{{ trans('general.name') }}"
                                                                           id="name-l"
                                                                           wire:model.defer="nameActivityTask">
                                                                </div>
                                                                <div class="w-10 mt-1 pl-2">
                                                                    <a href="javascript:void(0);"
                                                                       wire:click="saveActivityTask({{$task->id}})">
                                                                        <i class="fal fa-plus fa-2x color-success-700 mr-2"></i>
                                                                    </a>
                                                                    <a href="javascript:void(0);"
                                                                       wire:click="$set('showAddActivity', false)">
                                                                        <i class="fal fa-times fa-2x color-black"></i>
                                                                    </a>
                                                                </div>
                                                                @if($errors)
                                                                    <div class="row w-100">
                                                                        <div class="col-2">
                                                                            @error('codeActivityTask')
                                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="col-10">
                                                                            @error('nameActivityTask')
                                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="tab-pane fade show" id="budget" role="tabpanel"
                                                         wire:ignore>
                                                        <div class="d-flex flex-column">
                                                            <x-label-section>{{ trans_choice('budget.budget',1) }}</x-label-section>
                                                            <div class="section-divider"></div>
                                                            @isset($expenses)
                                                                <div class="table-responsive">
                                                                    <table class="table table-light table-hover">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="table-th w-33"> {{trans('general.item')}}</th>
                                                                            <th class="table-th w-33">{{ trans('general.name')}}</th>
                                                                            <th class="table-th w-33">{{trans('general.value')}}</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                        @foreach($expenses as $item)
                                                                            <tr class="tr-hover">
                                                                                <td>
                                                                                    <span class="badge {{$item->is_new ? 'badge-warning' : '' }}  badge-pill fs-1x fw-700">{{ $item->code }}</span>
                                                                                </td>
                                                                                <td>{{ $item->name }}</td>
                                                                                <td>{{ $item->balance}} </td>
                                                                            </tr>
                                                                        @endforeach

                                                                        <tr style="background-color: #e0e0e0">
                                                                            <td colspan="2"></td>
                                                                            <td style="color: #008000"
                                                                                class="fs-2x fw-700">Total:
                                                                                ${{ money($total)}}</td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @else
                                                                <x-empty-content>
                                                                    <x-slot name="img">
                                                                        <i class="fas fa-money-bill-wave"
                                                                           style="color: #2582fd;"></i>
                                                                    </x-slot>
                                                                    <x-slot name="title">
                                                                        No existen partidas presupuestarias creadas
                                                                    </x-slot>
                                                                </x-empty-content>
                                                            @endisset
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade show" id="files" role="tabpanel"
                                                         wire:ignore.self>
                                                        <div class="mt-2">
                                                            <livewire:components.files :modelId="$task->id"
                                                                                       model="\App\Models\Projects\Activities\Task"
                                                                                       folder="activities"
                                                                                       event="fileAdded"
                                                            />
                                                        </div>
                                                        <div class="mt-2">
                                                            <x-label-section>{{ trans('general.comments') }}</x-label-section>
                                                            <livewire:components.comments :modelId="$task->id"
                                                                                          class="\App\Models\Projects\Activities\Task"
                                                                                          :key="time().$task->id"
                                                                                          identifier="activities"
                                                                                          event="commentAdded"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex-grow-1 w-35" style="overflow: hidden auto">
                                        @if($project->phase instanceof  \App\States\Project\Implementation)
                                            <div class="pl-2 content-detail w-33">
                                                <livewire:components.select-inline-edit :modelId="$task->id"
                                                                                        class="\App\Models\Projects\Activities\Task"
                                                                                        field="status"
                                                                                        value="{{$task->status === \App\Models\Projects\Activities\Task::STATUS_FINISHED_DELAY ? \App\Models\Projects\Activities\Task::STATUS_FINISHED : $task->status }}"
                                                                                        :selectArray="\App\Models\Projects\Activities\Task::STATUSES"
                                                                                        :limit="3"
                                                                                        event="updateResultsActivities"
                                                                                        :key="time().$task->id"/>

                                            </div>
                                            <hr>
                                        @endif
                                        <div class="pl-2 content-detail">
                                            <x-label-section>{{ trans_choice('general.details', 2) }}</x-label-section>
                                        </div>
                                        <div class="pl-2 content-detail">
                                            <div class="d-flex flex-wrap mt-2">
                                                <x-label-detail>{{ trans('general.responsible') }}</x-label-detail>
                                                <div class="detail" style="z-index: 99" wire:ignore>
                                                    @if($project->phase instanceof  \App\States\Project\Planning)
                                                        <livewire:components.dropdown-user :modelId="$task->id"
                                                                                           modelClass="\App\Models\Projects\Activities\Task"
                                                                                           field="owner_id"
                                                                                           :user="$task->responsible"
                                                                                           event="notifyUser"
                                                                                           :usersAdd="$users"
                                                                                           :key="time().$task->id"
                                                        />
                                                    @else
                                                        {{$task->responsible ? $task->responsible->getFullName() : ''}}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex flex-wrap mt-2">
                                                <x-label-detail>{{ trans('general.code') }}</x-label-detail>
                                                <div class="detail" wire:ignore>
                                                    @if($project->phase instanceof  \App\States\Project\Planning)
                                                        <livewire:components.input-inline-edit :modelId="$task->id"
                                                                                               class="\App\Models\Projects\Activities\Task"
                                                                                               field="code"
                                                                                               defaultValue="{{ $task->code }}"
                                                                                               :rules="$rule"
                                                                                               :key="time().$task->id"
                                                        />
                                                    @else
                                                        {{$task->code}}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex flex-wrap mt-2">
                                                <x-label-detail>{{ trans('general.start_date') }}</x-label-detail>
                                                <div class="detail">
                                                    @if($project->phase instanceof  \App\States\Project\Planning)
                                                        <livewire:components.date-inline-edit :modelId="$task->id"
                                                                                              class="\App\Models\Projects\Activities\Task"
                                                                                              field="start_date"
                                                                                              type="date"
                                                                                              event="refreshPage"
                                                                                              :rules="'required|before:'.$task->end_date"
                                                                                              defaultValue="{{$task->start_date? $task->start_date->format('j F, Y'): ''}}"
                                                                                              :key="time().$task->id"/>
                                                    @else
                                                        {{$task->start_date? $task->start_date->format('j F, Y'): ''}}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex flex-wrap mt-2">
                                                <x-label-detail>{{ trans('general.end_date') }}</x-label-detail>
                                                <div class="detail">
                                                    @if($project->phase instanceof  \App\States\Project\Planning)

                                                        <livewire:components.date-inline-edit :modelId="$task->id"
                                                                                              class="\App\Models\Projects\Activities\Task"
                                                                                              field="end_date"
                                                                                              type="date"
                                                                                              event="refreshPage"
                                                                                              :rules="'required|after:'.$task->start_date"
                                                                                              defaultValue="{{$task->end_date ? $task->end_date->format('j F, Y'): ''}}"
                                                                                              :key="time().$task->id"/>
                                                    @else
                                                        {{$task->end_date? $task->end_date->format('j F, Y'): ''}}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex flex-wrap mt-2">
                                                <x-label-detail>{{trans('general.estimated_time')}}</x-label-detail>
                                                <x-content-detail>{{ $task->duration }}h</x-content-detail>
                                            </div>
                                            @if($project->phase instanceof  \App\States\Project\Implementation)
                                                <div class="d-flex flex-wrap mt-2" wire:loading.class="bg-warning-100">
                                                    {{--                                                    @if($showPanelWork===false)--}}
                                                    <x-label-detail>Seguimiento de Tiempo</x-label-detail>
                                                    <x-content-detail>
                                                        <div class="progress cursor-pointer"
                                                             style="width: 100% !important;"
                                                             wire:click="showPanelWorkLog()">
                                                            <div class="progress-bar" role="progressbar"
                                                                 style="width: {{ $widthProgress }}%;"
                                                                 aria-valuenow="{{$registerTime}}" aria-valuemin="0"
                                                                 aria-valuemax="100">
                                                                {{ $task->workLogs->sum('value') }}h
                                                            </div>
                                                        </div>
                                                    </x-content-detail>
                                                    {{--                                                    @endif--}}
                                                </div>
                                            @endif
                                            <div class="d-flex flex-wrap mt-2">
                                                @if($showPanelWork)
                                                    <div class="text-center">
                                                        <x-label-section>Registrar Tiempo</x-label-section>
                                                    </div>
                                                    <div class="w-100 pr-2" wire:loading.class="bg-warning-100">

                                                        <div class="d-flex flex-wrap mt-2">
                                                            <x-label-detail>{{ trans('general.value') }}(h)
                                                            </x-label-detail>
                                                            <div class="detail">
                                                                <input type="number" min="0" max="24"
                                                                       class="form-control" name="valueWorkLog"
                                                                       id="valueWorkLog"
                                                                       wire:model.defer="valueWorkLog" placeholder="4">
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-wrap mt-2">
                                                            <x-label-detail>{{ trans('general.description') }}</x-label-detail>
                                                            <div class="detail">
                                                                <textarea name="valueWorkLogText" id="valueWorkLogText"
                                                                          class="form-control w-100" rows="3"
                                                                          wire:model.defer="valueWorkLogText"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="text-center p-2">
                                                            <button wire:click="$set('showPanelWork', false)"
                                                                    class="btn btn-outline-info btn-sm">
                                                                <i class="fas fa-trash pr-2"></i> Cancelar
                                                            </button>
                                                            <button wire:click="saveWorkLog()"
                                                                    class="btn btn-success btn-sm">
                                                                <i class="fas fa-save pr-2"></i> Guardar
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            @if($project->phase instanceof  \App\States\Project\Planning)
                                                <div class="d-flex flex-column w-100">
                                                    <label class="form-label required">{{ trans_choice('general.programs', 1) }}</label>
                                                    <div class="btn-group">
                                                        <button class="btn btn-outline-secondary dropdown-toggle"
                                                                type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                                style="white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                                                            @if($poaProgramId != null)
                                                                {{ $poaProgramName }}
                                                            @else
                                                                {{ trans('general.select') }}
                                                            @endif
                                                        </button>
                                                        <div class="dropdown-menu" style="left: 0; right: 0">
                                                            @foreach($programs as $program)
                                                                <div class="dropdown-item" wire:click="$set('poaProgramId', '{{ $program['id'] }}')"
                                                                     style="white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                                                                    <span style="white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">{{ $program->planDetail->name  }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column w-100 mt-3">
                                                    <label class="form-label required">{{ trans_choice('general.indicators', 1) }}</label>
                                                    <div class="btn-group">
                                                        <button class="btn btn-outline-secondary dropdown-toggle"
                                                                type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                                style="white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                                                            @if($poaActivityIndicatorName != '')
                                                                {{ $poaActivityIndicatorName }}
                                                            @else
                                                                {{ trans('general.select') }}
                                                            @endif
                                                        </button>
                                                        <div class="dropdown-menu" style="left: 0; right: 0">
                                                            @foreach($programIndicators as $programIndicator)
                                                                <div class="dropdown-item" wire:click="$set('poaActivityIndicatorId', '{{ $programIndicator->indicator->id }}')"
                                                                     style="white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                                                                    <span style="white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">{{ $programIndicator->indicator->name }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="d-flex flex-wrap mt-2">
                                                    <x-label-detail>{{trans('general.program')}}</x-label-detail>
                                                    <x-content-detail>  {{ $poaProgramName }}</x-content-detail>
                                                </div>
                                                <div class="d-flex flex-wrap mt-2">
                                                    <x-label-detail>{{trans_choice('general.indicators', 1)}}</x-label-detail>
                                                    <x-content-detail>  {{ $poaActivityIndicatorName }}</x-content-detail>
                                                </div>
                                            @endif
                                            <div class="d-flex flex-wrap">
                                                <x-label-detail>{{ trans('general.poa_activity_impact') }}</x-label-detail>
                                                <div class="detail">
                                                    @if($project->phase instanceof  \App\States\Project\Planning)
                                                        <livewire:components.dropdown-simple :modelId="$task->id"
                                                                                             modelClass="\App\Models\Projects\Activities\Task"
                                                                                             :values="\App\Models\Poa\PoaActivity::CATEGORIES"
                                                                                             field="impact"
                                                                                             event="App\Events\ProjectActivityWeightChanged"
                                                                                             eventLivewire="refreshPage"
                                                                                             :defaultValue="\App\Models\Poa\PoaActivity::CATEGORIES[$task->impact ?? 1]"
                                                        />
                                                    @else
                                                        <div class="dropdown-item">
                                                            <i class="{{ \App\Models\Poa\PoaActivity::CATEGORIES[$task->impact ?? 1]['icon']}}} mx-1 fw-700"></i>
                                                            <span>{{ \App\Models\Poa\PoaActivity::CATEGORIES[$task->impact ?? 1]['text']}}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex flex-wrap">
                                                <x-label-detail>{{ trans('general.poa_activity_complexity') }}</x-label-detail>
                                                <div class="detail">
                                                    @if($project->phase instanceof  \App\States\Project\Planning)
                                                        <livewire:components.dropdown-simple :modelId="$task->id"
                                                                                             modelClass="\App\Models\Projects\Activities\Task"
                                                                                             :values="\App\Models\Poa\PoaActivity::CATEGORIES"
                                                                                             field="complexity"
                                                                                             event="App\Events\ProjectActivityWeightChanged"
                                                                                             eventLivewire="refreshPage"
                                                                                             :defaultValue="\App\Models\Poa\PoaActivity::CATEGORIES[$task->complexity ?? 1]"
                                                        />
                                                    @else
                                                        <div class="dropdown-item">
                                                            <i class="{{ \App\Models\Poa\PoaActivity::CATEGORIES[$task->complexity ?? 1]['icon']}}} mx-1 fw-700"></i>
                                                            <span>{{ \App\Models\Poa\PoaActivity::CATEGORIES[$task->complexity ?? 1]['text']}}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex flex-wrap mt-2">
                                                <x-label-detail>{{trans('general.cost')}}</x-label-detail>
                                                <div class="detail">
                                                    @if($project->phase instanceof  \App\States\Project\Planning)

                                                        <livewire:components.input-text :modelId="$task->id"
                                                                                        class="\App\Models\Projects\Activities\Task"
                                                                                        field="amount"
                                                                                        :rules="'required|numeric'"
                                                                                        event="App\Events\ProjectActivityWeightChanged"
                                                                                        eventLivewire="refreshPage"
                                                                                        defaultValue="{{ $task->amount}}"/>
                                                    @else
                                                        {{$task->amount}}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex flex-wrap mt-2">
                                                <x-label-detail>{{trans('general.weight')}}</x-label-detail>
                                                <x-content-detail>{{number_format($task->weight, 2)  }}</x-content-detail>
                                            </div>
                                            <div class="form-group col-12">
                                                <label class="form-label" for="province1">{{ __('general.poa_activity_location') }}</label>
                                                <div class="mb-2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" id="province" name="location"
                                                               value="PROVINCE" wire:model="typeLocation">
                                                        <label class="custom-control-label" for="province">{{trans('general.province')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" id="canton" name="location"
                                                               value="CANTON" wire:model="typeLocation">
                                                        <label class="custom-control-label" for="canton">{{trans('general.canton')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" class="custom-control-input" id="parish" name="location"
                                                               value="PARISH" wire:model="typeLocation">
                                                        <label class="custom-control-label" for="parish">{{trans('general.parish')}}</label>
                                                    </div>
                                                </div>

                                                <div class="position-relative w-100" x-data="{ open: false }">
                                                    <button class="btn btn-outline-secondary dropdown-toggle-custom w-100" x-on:click="open = ! open"
                                                            type="button">
                                <span class="spinner-border spinner-border-sm" wire:loading
                                      wire:target="typeLocation"></span>
                                                        {{ $selectedLocationName != '' ? $selectedLocationName:trans('general.select')  }}
                                                    </button>
                                                    <div class="dropdown mb-2" x-on:click.outside="open = false" x-show="open"
                                                         style="will-change: top, left;top: 37px;left: 0;">
                                                        <div class="input-group bg-white">
                                                            <input type="text" class="form-control border-0 bg-transparent pr-0"
                                                                   placeholder="{{ trans('general.search') }}"
                                                                   wire:model.debounce.500ms="searchLocation"
                                                                   wire:keydown.escape="$set('searchLocation', '')"
                                                                   x-on:escape="open = false">
                                                            <div class="input-group-append">
                                                        <span class="input-group-text bg-transparent border-0"
                                                              wire:click="$set('searchLocation', '')">
                                                            @if($searchLocation != '')
                                                                <i class="fal fa-times-circle cursor-pointer"></i>
                                                            @else
                                                                <i class="fal fa-search"></i>
                                                            @endif
                                                        </span>
                                                            </div>
                                                        </div>
                                                        <div class="dropdown-divider"></div>
                                                        <div class="p-3 hidden-child" wire:loading.class.remove="hidden-child"
                                                             wire:target="searchLocation">
                                                            <div class="d-flex justify-content-center">
                                                                <div class="spinner-border">
                                                                    <span class="sr-only"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div wire:loading.class="hidden-child">
                                                            <div style="max-height: 300px; overflow-y: auto" class="w-100">
                                                                @if(empty($locations))
                                                                    <div class="dropdown-item" x-cloak
                                                                         @click="open = false">
                                                                        <span>{{ trans('general.select_location_type') }}</span>
                                                                    </div>
                                                                @endif
                                                                @foreach($locations as $item)
                                                                    <div class="dropdown-item cursor-pointer" x-cloak
                                                                         @click="open = false" wire:key="{{time().$item->id}}"
                                                                         wire:click="$set('selectedLocationId', '{{ $item->id }}')">
                                                                        <span>{{ $item->getPath() }}</span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="pl-2 content-detail">
                                            <div class="d-flex flex-wrap mt-2">
                                                <small style="color: rgb(107, 119, 140);
                                                                                                    padding-top: 2px;
                                                                                                    white-space: nowrap;
                                                                                                    margin-top: 0px;
                                                                                                    font-size: 12px;
                                                                                                    line-height: 1.33333;">
                                                    {{trans('general.created_at')}}: {{$task->created_at->format('j F, Y')}}
                                                </small>
                                            </div>
                                            <div class="d-flex flex-wrap mt-2">
                                                <small style="color: rgb(107, 119, 140);
                                                                                                    padding-top: 2px;
                                                                                                    white-space: nowrap;
                                                                                                    margin-top: 0px;
                                                                                                    font-size: 12px;
                                                                                                    line-height: 1.33333;">
                                                    {{trans('general.updated_at')}}: {{$task->updated_at->format('j F, Y')}}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>