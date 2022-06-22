<div wire:ignore.self class="modal fade in" id="admin-edit-administrative-task" tabindex="-1" role="dialog"
     aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4">Editar Actividad Administrativa</h5>
                <button wire:click="resetForm" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column">
                    <div class="row mt-2">
                        <label class="form-label required col-6" for="name">{{ trans('general.name') }}</label>
                        <x-form.modal.text id="name"
                                           class="form-group col-12"
                                           placeholder="{{ __('general.form.enter', ['field' => __('general.name')]) }}">
                        </x-form.modal.text>
                        <div class="form-group col-3">
                            <label for="responsible" class="form-label col-10 required">Asignado a</label>
                            <x-form.modal.select id="user_id" class="form-group col-25">
                                <option value="">{{ __('general.form.select.field', ['field' => __('general.responsible')]) }}</option>
                                @foreach($users as $item)
                                    <option value="{{ $item->id }}">{{ $item->getFullName() }}</option>
                                @endforeach
                            </x-form.modal.select>
                        </div>
                        <div class="form-group col-4">
                            <label for="priority" class="form-label col-10 p-0 required">Prioridad</label>
                            <x-form.modal.select id="priority" class="col-25">
                                <option value="">{{ trans('general.form.select.field', ['field' => trans('general.priority')]) }}</option>
                                @foreach(\App\Models\AdministrativeTasks\AdministrativeTask::PRIORITIES  as $item)
                                    <option value="{{$item}}"> {{$item}}</option>
                                @endforeach
                            </x-form.modal.select>
                        </div>
                        <div class="form-group col-4">
                            <label for="status" class="form-label col-10 required">Estado</label>
                            <x-form.modal.select id="status" class="col-25">
                                <option value="">{{ trans('general.form.select.field', ['field' => trans('general.status')]) }}</option>
                                @foreach(\App\Models\AdministrativeTasks\AdministrativeTask::STATUSES  as $item)
                                    <option value="{{$item}}"> {{$item}}</option>
                                @endforeach
                            </x-form.modal.select>
                        </div>
                        <div class="form-group col-4 required">
                            <label class="form-label" for="start_date">{{ trans('general.start_date') }}</label>
                            <div class="input-group bg-white shadow-inset-2">
                                <div class="input-group-prepend">
                                            <span class="input-group-text bg-transparent border-right-0">
                                              <i class="fal fa-calendar"></i>
                                            </span>
                                </div>
                                <input type="date" wire:model.defer="start_date"
                                       class="form-control bg-transparent @error('start_date') is-invalid @enderror"
                                       placeholder="{{ trans('general.form.enter', ['field' => trans('general.start_date')]) }}">
                                <div class="invalid-feedback">{{ $errors->first('start_date') }}</div>
                            </div>
                        </div>

                        <div class="form-group col-4 required">
                            <label class="form-label" for="end_date">{{ trans('general.end_date') }}</label>
                            <div class="input-group bg-white shadow-inset-2">
                                <div class="input-group-prepend">
                                            <span class="input-group-text bg-transparent border-right-0">
                                              <i class="fal fa-calendar"></i>
                                            </span>
                                </div>
                                <input type="date" wire:model.defer="end_date"
                                       class="form-control bg-transparent @error('end_date') is-invalid @enderror"
                                       placeholder="{{ trans('general.form.enter', ['field' => trans('general.end_date')]) }}">
                                <div class="invalid-feedback">{{ $errors->first('end_date') }}</div>
                            </div>
                        </div>
                        <label class="form-label col-12"
                               for="description">{{ trans('general.description') }}</label>
                        <textarea wire:model.defer="description" rows="3"
                                  class="form-control bg-transparent @error('description') is-invalid @enderror">
                        </textarea>
                        <div class="col-12 p-2 d-flex flex-wrap">
                            @if($activitySubTasks)
                                <div class="mt-2 col-12">
                                    <label class="form-label col-6">Lista de Comprobación: </label>
                                    <div class="m-1 mt-2">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{$advanceSubTasks}}%"
                                                 aria-valuenow="{{$advanceSubTasks}}" aria-valuemin="0"
                                                 aria-valuemax="100">{{$advanceSubTasks}}%
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($activitySubTasks as $subTask)
                                        <div class="d-flex flex-wrap col-12">
                                            <div class="m-2 custom-control custom-checkbox col-6">
                                                <input type="checkbox" id="subTasks.{{$subTask->id}}"
                                                       wire:model="subTasks.{{$subTask->id}}"
                                                       value="{{$subTask->id}}">
                                                <label for="subTasks.{{$subTask->id}}">{{substr($subTask->name,0,10)}}</label>
                                            </div>
                                            <a class="p-0" href="javascript:void(0)"
                                               wire:click="deleteSubTask({{$subTask->id}})">
                                                <i class="fas fa-trash mr-1 text-danger" data-toggle="tooltip"
                                                   data-placement="top"
                                                   title="" data-original-title="Eliminar"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-group col-12 mt-2">
                                    <div class="align-content-center">
                                        <livewire:components.list-view title="Agregar"
                                                                       event="subTasksEdited"
                                        />
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if($task)
                            <div class="col-12">
                                <div class="mt-2">
                                    <livewire:components.files :modelId="$task->id"
                                                               model="\App\Models\AdministrativeTasks\AdministrativeTask"
                                                               folder="administrativeTasks"/>
                                </div>
                                <div class="mt-2">
                                    <x-label-section>{{ trans('general.comments') }}</x-label-section>
                                    <livewire:components.comments :modelId="$task->id"
                                                                  class="\App\Models\AdministrativeTasks\AdministrativeTask"
                                                                  :key="time().$task->id"
                                                                  identifier="administrativeTasks"/>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <div class="col-12">
                    <a class="btn btn-outline-secondary mr-1" wire:click="resetForm" type="button" class="close"
                       data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i> {{ trans('general.close') }}
                    </a>
                    <button wire:click="updateTask" class="btn btn-success">
                        <i class="fas fa-save pr-2"></i> {{ trans('general.update') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
