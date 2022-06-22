<div x-data="poa_program" x-cloak>

    <div class="accordion" id="js_demo_accordion-4">

        @if(count($programs)<1)
            <div class="alert border-danger bg-transparent text-danger" role="alert">
                <strong>No se </strong>ha configurado POA.
            </div>
        @endif
        @if($programs)
            @foreach($programs as $program)
                @if(user()->allowedProgram($program->planDetail->name))
                    <div class="card">
                        <div class="card-header" style="position: relative !important">
                            <a href="javascript:void(0);" class="card-title" data-toggle="collapse" data-target="#js_demo_accordion-4{{$program->id}}" aria-expanded="false">

                                <span style="color: {{ $program->color ?? '#039FDB' }}">{{ $program->planDetail->name }} - {{ $program->planDetail->parent->name }}</span>
                                <span style="color: {{ $program->color ? $program->color : '#039FDB' }}; margin-right: 1%; margin-left: 1%">
                                <strong>{{ number_format($program->calcProgress()*100,1) }}%</strong>
                            </span>
                                <div class="bg-white align-middle" style="width: 0.5%">
                                    <div class="w-20px dropdown dropdown-table">
                                    <span class="btn btn-default btn-xs btn-icon border-0 fs-md show-on-hover-parent open-drop"
                                          data-toggle="dropdown">
                                        <i class="fas fa-caret-down"></i>
                                    </span>
                                        <div class="dropdown-menu fadeindown dropdown-md m-0">
                                            <div class="dropdown-item m-2" style="border-radius: 4px" data-toggle="modal"
                                                 data-target="#poa-assign-weights"
                                                 data-program-id="{{ $program->poa->id }}">
                                                <i class="fal fa-plus-hexagon mr-2"></i> {{  trans_choice('general.assign_weights', 1) }}
                                            </div>
                                            <div class="dropdown-item m-2" style="border-radius: 4px" data-toggle="modal"
                                                 data-target="#poa-assign-goals"
                                                 data-program-id="{{ $program->id }}">
                                                <i class="fal fa-grip-lines-vertical mr-2"></i> {{  trans_choice('general.assign_goals', 1) }}
                                            </div>
                                            @if($program->activities->count() == 0)
                                                <div class="dropdown-item m-2" style="border-radius: 4px" data-toggle="modal"
                                                     data-target="#poa-delete-program"
                                                     data-program-id="{{ $program->poa->id }}"
                                                     wire:click="$emit('programDelete', '{{ $program->id }}')">
                                                    <i class="fal fa-trash-alt mr-2"></i> {{  trans_choice('general.delete', 1) }}
                                                </div>
                                            @endif
                                            <div class="dropdown-divider"></div>
                                            <div class="m-2 px-2 py-1">
                                                <h6 class="m-0 text-muted">{{ __('general.change') . ' ' . __('general.color') }}</h6>
                                                <livewire:components.color-palette :modelId="$program->id" :key="time().$loop->index"
                                                                                   class="App\Models\Poa\PoaProgram" field="color"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="ml-auto">
                                <span class="collapsed-reveal">
                                    <i class="fal fa-minus-circle text-danger fs-xl"></i>
                                </span>
                                <span class="collapsed-hidden">
                                    <i class="fal fa-plus-circle text-success fs-xl"></i>
                                </span>
                            </span>
                            </a>
                        </div>
                        <div wire:ignore.self id="js_demo_accordion-4{{ $program->id }}" class="collapse" data-parent="#js_demo_accordion-4">
                            <div class="card-body" style="min-height: 300px !important;">
                                <table class="table table-sm custom-table m-0">
                                    <tbody>
                                    <tr>
                                        <td style="width: 1.5%"></td>
                                        <td colspan="2" style="padding-left: 40px">
                                            <table class="table m-0 h-100 custom-table">
                                                <thead class="fs-lg">
                                                <tr>
                                                    <th width="30%" class="border-0 pt-0 text-center"></th>
                                                    <th width="10%" class="border-0 pt-0 text-center"></th>
                                                    <th width="10%" class="border-0 pt-0 text-center"></th>
                                                    <th width="20%" colspan="2" class="border-0 pt-0 text-center">Metas</th>
                                                    <th width="10%" class="border-0 pt-0 text-center"></th>
                                                    <th width="10%" class="border-0 pt-0 text-center"></th>
                                                    <th width="10%" class="border-0 pt-0 text-center"></th>
                                                </tr>
                                                <tr>
                                                    <th width="32%" class="border-0 pt-0 text-center"></th>
                                                    <th width="5%" class="border-0 pt-0 text-center">Peso</th>
                                                    <th width="10%" class="border-0 pt-0 text-center">Responsable</th>
                                                    <th width="10%" class="border-0 pt-0 text-center">Planificado</th>
                                                    <th width="10%" class="border-0 pt-0 text-center">Ejecutado</th>
                                                    <th width="10%" class="border-0 pt-0 text-center">Progreso</th>
                                                    <th width="10%" class="border-0 pt-0 text-center">Estado</th>
                                                    <th width="10%" class="border-0 pt-0 text-center"></th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </td>
                                    </tr>

                                    @foreach($program->planDetail->indicators as $indicator)
                                        @if($indicator->category == \App\Models\Indicators\Indicator\Indicator::CATEGORY_OPERATIVE && $company->parent_id == null)
                                            @continue
                                        @endif
                                        @if($indicator->category == \App\Models\Indicators\Indicator\Indicator::CATEGORY_TACTICAL && $company->parent_id != null)
                                            @continue
                                        @endif
                                        @php($key = array_search($indicator->id, array_column($poaIndicatorConfigs, 'indicator_id')))
                                        @if ($key === false)
                                            @continue
                                        @endif
                                        <tr class="bg-white border-bottom border-white">
                                            <td colspan="3" style="padding-left: 40px">
                                                <span class="fs-lg font-weight-light table-cell "
                                                      style="color: {{ $program->color ?? '#039FDB' }}"> {{ $indicator->name }} - Meta: {{ $indicator->total_goal_value ?? 0 }}
                                                    <span class="form-label badge ml-2 {{ $indicator->getStateIndicator()[0] ?? null }} badge-pill">
                                                        Progreso: {{ $indicator->getStateIndicator()[1] ?? null }}%</span>

                                                </span>
                                            </td>
                                        </tr>

                                        @foreach($indicator->poaActivities->where('poa_program_id', $program->id) as $act)
                                            <tr class="border-bottom border-white show-child-on-hover">
                                                <td style="width: 1.5%"></td>
                                                <td colspan="2" style="padding-left: 50px">
                                                    <table class="table table-striped m-0 h-25 custom-table
                                                    @if($act->project_activity_id)
                                                            bg-success-50
                                                    @else
                                                            bg-gray-200
                                                    @endif"
                                                           x-bind:class="activitySelected.length ? 'row-selected':''">
                                                        <tbody>
                                                        <tr>
                                                            <td class="bg-white align-middle" style="width: 2%">
                                                                @if(!$act->project_activity_id)
                                                                    <div class="dropdown dropdown-table">
                                                                    <span class="btn btn-default btn-xs btn-icon border-0 fs-md show-on-hover-parent open-drop"
                                                                          data-toggle="dropdown">
                                                                        <i class="fas fa-caret-down"></i>
                                                                    </span>
                                                                        <div class="dropdown-menu fadeindown dropdown-md m-0 p-0">
                                                                            <div class="dropdown-item m-2" style="border-radius: 4px"
                                                                                 wire:click="$emit('saveActivityTemplate', '{{ $act->id }}')">
                                                                                <i class="fas fa-save mr-2"></i>{{ trans('general.save').' como '.trans_choice('general.templates',1) }}
                                                                            </div>
                                                                            <div class="dropdown-item m-2" style="border-radius: 4px"
                                                                                 wire:click="$emit('triggerDelete', '{{ $act->id }}')">
                                                                                <i class="fas fa-trash mr-2 text-danger"></i>{{ trans('general.delete') . ' ' . trans_choice('general.activities', 1) }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td width="30%">
                                                                <div class="h-100 d-flex align-items-center pr-1">
                                                                    <div class="left-indicator mr-1 cursor-pointer"
                                                                         style="background-color: {{ $program->color ?? '#808080' }}"
                                                                         x-on:click="update('{{ $act->id }}')">
                                                                        <div class="left-indicator-inner">
                                                                            <div class="left-indicator-checkbox"
                                                                                 x-bind:class="hasItem('{{ $act->id  }}') ? 'selected':'' "
                                                                            >
                                                                        <span class="left-indicator-checkbox-mark fa fa-check"
                                                                              style="color: {{ $program->color ?? 'black' }}"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @if($act->project_activity_id)
                                                                        {{ $act->name }}
                                                                    @else
                                                                        <livewire:components.input-inline-edit :modelId="$act->id"
                                                                                                               class="\App\Models\Poa\PoaActivity"
                                                                                                               field="name"
                                                                                                               defaultValue="{{$act->name}}"
                                                                                                               :key="time().$poaId"/>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td width="10%" class="text-center">
                                                                <div class="h-100 d-flex align-items-center justify-content-center">
                                                                    <a href="javascript:void(0);"
                                                                       wire:click="$emit('activitySelected', {{ $act->id }}, {{ $poaId }})"
                                                                       data-toggle="modal"
                                                                       data-target="#poa-edit-activity-weight-modal">
                                                                        {{ number_format($act->poa_weight, 2, '.', ',') }}
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <td width="10%" class="text-center">
                                                                <div class="h-100 d-flex align-items-center justify-content-center">

                                                                    <livewire:components.select-inline-edit :modelId="$act->id"
                                                                                                            :fieldId="$act->responsible->id"
                                                                                                            class="\App\Models\Poa\PoaActivity"
                                                                                                            field="user_id_in_charge"
                                                                                                            value="{{$act->responsible->name??''}}"
                                                                                                            :selectClass="$users"
                                                                                                            selectField="name"
                                                                                                            selectRelation="responsible"
                                                                                                            :key="time().$poaId"/>
                                                                </div>
                                                            </td>
                                                            <td width="10%" class="text-center">
                                                                <div class="h-100 d-flex align-items-center justify-content-center">
                                                                    <a href="javascript:void(0);"
                                                                       wire:click="$emit('activitySelected', {{ $act->id }}, {{ $poaId }})"
                                                                       data-toggle="modal"
                                                                       data-target="#poa-edit-activity-goal-modal">
                                                                        {{ $act->assignProgress() }}
                                                                    </a>
                                                                    @if($poa->status != App\Models\Poa\Poa::STATUS_DRAFT)
                                                                        <div class="d-flex align-items-center fs-3x cursor-pointer"
                                                                             wire:click="$emit('requestGoalChange', {{ $act->id }}, {{ $poaId }})"
                                                                             data-toggle="modal" data-target="#poa-goal-change-request-modal">
                                                                            <div class="width-3 height-2 d-inline-flex align-items-center justify-content-center
                                                                            position-relative mr-1 color-fusion-200">
                                                                                <i class="fal fa-lock-alt fw-500"></i>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td width="10%" class="text-center">
                                                                <div class="h-100 d-flex align-items-center justify-content-center">
                                                                    <a href="javascript:void(0);"
                                                                       wire:click="$emit('activitySelected', {{ $act->id }}, {{ $poaId }})"
                                                                       data-toggle="modal"
                                                                       data-target="#poa-edit-activity-progress-modal">
                                                                        {{ $act->progress }}

                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <td width="10%" class="text-center">
                                                                <div class="h-100 d-flex align-items-center justify-content-center">
                                                                    <strong>
                                                                        ({{ $act->assignProgress() ? number_format($act->progress / $act->assignProgress()  * 100, 0, '.', ',') : number_format(0.0, 0, '.', ',') }}
                                                                        %)
                                                                    </strong>
                                                                </div>
                                                            </td>
                                                            <td width="10%" class="text-center">
                                                                <div class="h-100 d-flex align-items-center justify-content-center">
                                                                    @if($act->project_activity_id)
                                                                        {{ $act->status }}
                                                                    @else
                                                                        <livewire:components.select-inline-edit :modelId="$act->id"
                                                                                                                class="\App\Models\Poa\PoaActivity"
                                                                                                                field="status"
                                                                                                                value="{{$act->status??''}}"
                                                                                                                event="updateProgress"
                                                                                                                :selectArray="$status"
                                                                                                                :key="time().$poaId"/>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td width="10%" class="text-center">
                                                                <div class="d-flex align-items-center fs-3x cursor-pointer"
                                                                     wire:click="$emit('show', '{{ $act->id }}')">
                                                                    <div
                                                                            class="width-3 height-2 d-inline-flex align-items-center justify-content-center position-relative mr-1
                                                                            {{ $act->comments_count ? 'color-blue':'color-fusion-200' }}">
                                                                        <i class="fal fa-comment fw-500"></i>
                                                                        @if($act->comments_count)
                                                                            <span class="badge badge-icon pos-top pos-right">{{ $act->comments_count }}</span>
                                                                        @endif
                                                                    </div>
                                                                    @if($act->hasMedia('file'))
                                                                        <span class="color-fusion-100"><i
                                                                                    class="fas fa-paperclip"></i></span>
                                                                    @else
                                                                        <div class="indent"></div>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="bg-white border-bottom border-white">
                                            <td style="width: 1.5%"></td>
                                            <td colspan="2" style="padding-left: 50px">
                                                <button class="btn btn-outline-default btn-xs shadow-0" data-toggle="modal"
                                                        data-target="#poa-create-activity-modal"
                                                        data-program-id="{{ $program->id }}"
                                                        data-indicator-id="{{ $indicator->id }}">
                                                    <i class="fas fa-plus"></i>
                                                    {{ __('general.title.add', ['type' => trans_choice('general.activities', 1)]) }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>

    <div style="pointer-events: none">
        <div class="w-100 d-flex justify-content-center">
            <div class="dialog-bottom-actions w-25" x-show="show" x-transition:enter.duration.350ms
                 x-transition:leave.duration.200ms>
                <div class="w-15 bg-success-500 d-flex flex-column justify-content-center text-center cursor-default rounded-left">
                    <div class="fs-xxl" x-text="count"></div>
                </div>
                <div class="d-flex flex-column justify-content-center flex-1">
                    <div class="w-75 fs-xl pl-3 font-weight-light">Elemento seleccionados</div>
                </div>
                <div class="d-flex flex-column justify-content-center px-2 cursor-pointer text-center action-btn"
                     wire:click="$emit('triggerDelete', [])">
                    <span class="fal fa-trash-alt mt-1 fs-xl"></span>
                    <span class="mt-1 fs-lg">Eliminar</span>
                </div>
                <div class="d-flex flex-column justify-content-center w-10 cursor-pointer text-center rounded-right px-2 action-btn close-btn"
                     x-on:click="clean">
                    <span class="fal fa-times" style="font-size: 1.50rem"></span>
                </div>
            </div>
        </div>
    </div>


    @push('page_script')
        <script>

            document.addEventListener('alpine:init', () => {
                Alpine.data('poa_program', () => ({
                    activitySelected: @entangle('activitySelected').defer,
                    show: @entangle('show').defer,
                    update(id) {
                        if (this.hasItem(id)) {
                            let index = this.activitySelected.indexOf(id);
                            if (index !== -1) {
                                this.activitySelected.splice(index, 1);
                            }
                        } else {
                            this.activitySelected.push(id);
                        }
                        this.show = this.count() > 0;
                    },
                    clean() {
                        this.activitySelected = [];
                        this.show = false
                    },
                    count() {
                        return this.activitySelected.length;
                    },
                    hasItem(id) {
                        return this.activitySelected.indexOf(id) !== -1;
                    },
                }))
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
                    @this.call('deleteActivity', id);
                    }
                });
            })

            @this.on('saveActivityTemplate', id => {
                Swal.fire({
                    title: '{{ trans('messages.warning.sure') }}',
                    text: '{{ trans('general.save').' '.trans_choice('general.templates',1) }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--success)',
                    confirmButtonText: '<i class="fas fa-save"></i> {{ trans('general.yes') . ', ' . trans('general.save') }}',
                    cancelButtonText: '<i class="fas fa-times"></i> {{ trans('general.no') . ', ' . trans('general.cancel') }}'
                }).then((result) => {
                    if (result.value) {
                    @this.call('saveTemplate', id);
                    }
                });
            });

            })

            document.addEventListener('DOMContentLoaded', function () {
                $('div.dropdown-item, .color-item').on('click', function () {
                    $(".open-drop").dropdown("hide");
                });

            @this.on('programDelete', id => {
                Swal.fire({
                    title: '{{ trans('messages.warning.sure') }}',
                    text: '{{ trans('messages.warning.delete') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--danger)',
                    confirmButtonText: '<i class="fas fa-trash"></i> {{ trans('general.yes') . ', ' . trans('general.delete') }}',
                    cancelButtonText: '<i class="fas fa-times"></i> {{ trans('general.no') . ', ' . trans('general.cancel') }}'
                }).then((result) => {
                    if (result.value) {
                    @this.call('delete', id);
                    }
                });
            });
            })

            $("#poa-assign-goals").on("hidden.bs.modal", function () {
                // Aquí va el código a disparar en el evento
                Livewire.emit('goalProgressUpdated');
            });
        </script>
    @endpush
</div>
