<div wire:ignore.self class="modal fade" id="edit_piat_modal" tabindex="-1" role="dialog" aria-hidden="true"
     style="height: 100%;">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div wire:ignore class="modal-header bg-primary text-white">
                <h5 class="modal-title">{{ trans('general.poa_activity_piat_edit_modal') }}</h5>
                <button type="button" data-dismiss="modal" class="close text-white" aria-label="Close">
                    <span aria-hidden="true"><i class="far fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="panel-content">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#activityWorkshop" role="tab"
                               aria-selected="true" wire:ignore>
                                <i class="fal fa-user text-primary"></i>
                                <span class="hidden-sm-down ml-1">Actividades / Talleres</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#workshopAgenda" role="tab"
                               aria-selected="false" wire:ignore>
                                <i class="fal fa-address-card"></i>
                                <span class="hidden-sm-down ml-1">Agenda Taller</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#requirements" role="tab"
                               aria-selected="false" wire:ignore>
                                <i class="fal fa-address-card"></i>Requerimientos
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content border border-top-0 p-3">
                        <div class="card tab-pane fade show active margin-left" id="activityWorkshop" role="tabpanel"
                             wire:ignore.self>
                            @if ($piat)
                                <div class="float-right" style="margin-top: 0.5rem;">
                                    <ol class="breadcrumb breadcrumb-lg breadcrumb-arrow mb-0">
                                        <li class="@if ($piat->status->isActive(\App\States\Poa\Pending::class)) active @endif">
                                            <a href="#" @if ($piat->status->to() instanceof \App\States\Poa\Pending)  @endif
                                            wire:click="changeStatus">
                                                <span class="badge border rounded-pill bg-white">1</span>
                                                <span
                                                        class="hidden-md-down">{{ \App\States\Poa\Pending::label() }}</span>
                                            </a>
                                        </li>
                                        <li class="@if ($piat->status->isActive(\App\States\Poa\ApprovedPiat::class)) active @endif">
                                            <a href="#" @if ($piat->status->to() instanceof \App\States\Poa\ApprovedPiat)  @endif
                                            wire:click="changeStatus">
                                                <span class="badge border rounded-pill bg-white">2</span>
                                                <span
                                                        class="hidden-md-down">{{ \App\States\Poa\ApprovedPiat::label() }}</span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            @endif
                            <form wire:submit.prevent="submit()" method="post" autocomplete="off"
                                  style="margin-top: 1.5rem; margin-left: 1.5rem;">
                                <x-label-section>{{ trans('poa.piat_matrix_activity_workshop') }}</x-label-section>
                                <div class="section-divider"></div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                    <div class="form-group w-65 pr-1">
                                        <label class="form-label fw-700"
                                               for="name">{{ trans('poa.piat_matrix_create_placeholder_name') }}</label>
                                        <input @if($is_terminated) disabled @endif type="text"
                                               class="form-control bg-transparent  @error('name') is-invalid @enderror"
                                               placeholder="{{ trans('poa.piat_matrix_create_placeholder_name') }}"
                                               wire:model="name">
                                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                    </div>
                                    <div></div>
                                </div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                    <div class="form-group w-50 pr-1">
                                        <label class="form-label fw-700"
                                               for="place">{{ trans('poa.piat_matrix_create_placeholder_place') }}</label>
                                        <input @if($is_terminated) disabled @endif type="text" wire:model="place" class="form-control"
                                               placeholder="{{ trans('poa.piat_matrix_create_placeholder_place') }}"/>
                                        <div class="invalid-feedback">{{ $errors->first('place') }}</div>
                                    </div>
                                    <div class="form-group w-50">
                                        <label class="form-label fw-700"
                                               for="date">{{ trans('poa.piat_matrix_create_placeholder_date') }}</label>
                                        <input @if($is_terminated) disabled @endif type="date" wire:model="date"
                                               class="form-control bg-transparent @error('date') is-invalid @enderror"
                                               placeholder="{{ trans('general.form.enter', ['field' => trans('poa.piat_matrix_create_placeholder_date')]) }}"/>
                                        <div class="invalid-feedback">{{ $errors->first('date') }}</div>
                                    </div>
                                    <div class="form-group"></div>
                                </div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                    <div class="form-group w-50 pr-1">
                                        <label class="form-label fw-700 timepicker"
                                               for="initTime">{{ trans('poa.piat_matrix_create_placeholder_initial_time') }}</label>
                                        <input @if($is_terminated) disabled @endif type="time" wire:model="initTime"
                                               class="form-control bg-transparent  @error('initTime') is-invalid @enderror"
                                               placeholder="{{ trans('poa.piat_matrix_create_placeholder_initial_time') }}"/>
                                        <div class="invalid-feedback">{{ $errors->first('initTime') }}</div>
                                    </div>
                                    <div class="form-group w-50">
                                        <label class="form-label fw-700 timepicker"
                                               for="endTime">{{ trans('poa.piat_matrix_create_placeholder_end_time') }}</label>
                                        <input @if($is_terminated) disabled @endif type="time" wire:model="endTime"
                                               class="form-control bg-transparent  @error('endTime') is-invalid @enderror"
                                               placeholder="{{ trans('poa.piat_matrix_create_placeholder_end_time') }}"/>
                                        <div class="invalid-feedback">{{ $errors->first('endTime') }}</div>
                                    </div>
                                    <div class="form-group"></div>
                                </div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between mr-2">
                                    <div class="form-group w-30 pr-1">
                                        <label class="form-label fw-700"
                                               for="province">{{ trans('poa.piat_matrix_create_placeholder_province') }}</label>
                                        <select @if($is_terminated) disabled @endif wire:model="province"
                                                class="custom-select bg-transparent  @error('province') is-invalid @enderror"
                                                id="province">
                                            <option value="" selected>
                                                {{ trans('poa.piat_matrix_create_placeholder_province') }}
                                            </option>
                                            @foreach ($provinces as $item)
                                                <option value="{{ $item->id }}">{{ $item->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">{{ $errors->first('province') }}</div>
                                    </div>
                                    <div class="form-group w-30">
                                        <label class="form-label fw-700"
                                               for="canton">{{ trans('poa.piat_matrix_create_placeholder_canton') }}</label>
                                        <select @if($is_terminated) disabled @endif wire:model="canton"
                                                class="custom-select bg-transparent  @error('canton') is-invalid @enderror">
                                            <option value="" selected>
                                                {{ trans('poa.piat_matrix_create_placeholder_canton') }}
                                            </option>
                                            @foreach ($cantons as $item)
                                                <option value="{{ $item->id }}">{{ $item->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">{{ $errors->first('canton') }}</div>
                                    </div>
                                    <div class="form-group w-30">
                                        <label class="form-label fw-700"
                                               for="parish">{{ trans('poa.piat_matrix_create_placeholder_parish') }}</label>
                                        <select @if($is_terminated) disabled @endif wire:model="parish"
                                                class="custom-select bg-transparent  @error('parish') is-invalid @enderror">
                                            <option value="" selected>
                                                {{ trans('poa.piat_matrix_create_placeholder_parish') }}
                                            </option>
                                            @foreach ($parishes as $item)
                                                <option value="{{ $item->id }}">{{ $item->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">{{ $errors->first('parish') }}</div>
                                    </div>
                                    <div class="form-group"></div>
                                </div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                    <div class="form-group w-50 pr-1">
                                        <label class="form-label fw-700"
                                               for="numberMaleResp">{{ trans('poa.piat_matrix_create_placeholder_resp_male') }}</label>
                                        <input @if($is_terminated) disabled @endif type="number" wire:model="numberMaleResp" class="form-control"
                                               placeholder="Cantidad de responsables hombres"/>
                                        <div class="invalid-feedback">{{ $errors->first('numberMaleResp') }}</div>
                                    </div>
                                    <div class="form-group w-50">
                                        <label class="form-label fw-700"
                                               for="numberFeMaleResp">{{ trans('poa.piat_matrix_create_placeholder_resp_female') }}</label>
                                        <input @if($is_terminated) disabled @endif type="number" wire:model="numberFeMaleResp" class="form-control"
                                               placeholder="Cantidad de responsables mujeres"/>
                                        <div class="invalid-feedback">{{ $errors->first('numberFeMaleResp') }}</div>
                                    </div>
                                    <div class="form-group"></div>
                                </div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                    <div class="form-group w-50 pr-1">
                                        <label class="form-label fw-700"
                                               for="maleBenef">{{ trans('poa.piat_matrix_create_placeholder_benef_male') }}</label>
                                        <input @if($is_terminated) disabled @endif type="number" wire:model="maleBenef" class="form-control"
                                               placeholder="Cantidad de beneficiarios hombres"/>
                                        <div class="invalid-feedback">{{ $errors->first('maleBenef') }}</div>
                                    </div>
                                    <div class="form-group w-50">
                                        <label class="form-label fw-700"
                                               for="femaleBenef">{{ trans('poa.piat_matrix_create_placeholder_benef_female') }}</label>
                                        <input @if($is_terminated) disabled @endif type="number" wire:model="femaleBenef" class="form-control"
                                               placeholder="Cantidad de beneficiarios mujeres"/>
                                        <div class="invalid-feedback">{{ $errors->first('femaleBenef') }}</div>
                                    </div>
                                    <div class="form-group"></div>
                                </div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                    <div class="form-group w-50 pr-1">
                                        <label class="form-label fw-700"
                                               for="maleVol">{{ trans('poa.piat_matrix_create_placeholder_vol_male') }}</label>
                                        <input @if($is_terminated) disabled @endif type="number" wire:model="maleVol" class="form-control"
                                               placeholder="Cantidad de vlontarios hombres"/>
                                        <div class="invalid-feedback">{{ $errors->first('maleVol') }}</div>
                                    </div>
                                    <div class="form-group w-50">
                                        <label class="form-label fw-700"
                                               for="femaleVol">{{ trans('poa.piat_matrix_create_placeholder_vol_female') }}</label>
                                        <input @if($is_terminated) disabled @endif type="number" wire:model="femaleVol" class="form-control"
                                               placeholder="Cantidad de vlontarios mujeres"/>
                                        <div class="invalid-feedback">{{ $errors->first('femaleVol') }}</div>
                                    </div>
                                    <div class="form-group"></div>
                                </div>
                                {{--                               --}}
                                <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                    <div class="form-group w-65 pr-1">
                                        <label class="form-label fw-700"
                                               for="goal">{{ trans('poa.piat_matrix_create_placeholder_goals') }}</label>
                                        <textarea @if($is_terminated) disabled @endif wire:model="goal" class="form-control"
                                                  placeholder="{{ trans('poa.piat_matrix_create_placeholder_goals') }}"></textarea>
                                        <div class="invalid-feedback">{{ $errors->first('goal') }}</div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <x-form.modal.footer data-dismiss="modal"></x-form.modal.footer>
                                    @if(!$is_terminated)
                                        <button wire:click="closeModal" type="button" class="btn btn-danger btn-sm">
                                            <span aria-hidden="true">Terminar</span>
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="card tab-pane fade" id="workshopAgenda" role="tabpanel" wire:ignore.self>
                            <div class="card-body pt-0">
                                <div class="frame-wrap">
                                    <table class="table m-0">
                                        <thead>
                                        <tr>
                                            <th>{{ __('poa.piat_matrix_create_placeholder_task') }}</th>
                                            <th>{{ __('poa.piat_matrix_create_placeholder_date') }}</th>
                                            <th>{{ __('poa.piat_matrix_create_placeholder_initial_time') }}</th>
                                            <th>{{ __('poa.piat_matrix_create_placeholder_end_time') }}</th>
                                            <th><a href="#">{{ trans('general.actions') }} </a></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($piatPlan as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex float-left">
                                                            <span
                                                                    class="color-item shadow-hover-5 mr-2 cursor-default"></span>
                                                        <span>{{ $item['task'] }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex float-left">
                                                            <span
                                                                    class="color-item shadow-hover-5 mr-2 cursor-default"></span>
                                                        <span>{{ $item['date'] }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex float-left">
                                                            <span
                                                                    class="color-item shadow-hover-5 mr-2 cursor-default"></span>
                                                        <span>{{ $item['initial_time'] }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex float-left">
                                                            <span
                                                                    class="color-item shadow-hover-5 mr-2 cursor-default"></span>
                                                        <span>{{ $item['end_time'] }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if(!$is_terminated)
                                                        <div class="d-flex flex-wrap">
                                                            <span class="cursor-pointer trash"
                                                                  wire:click="editThemeTask('{{ $item['id'] }}')">
                                                                <i class="fas fa-edit"></i></span>
                                                        </div>
                                                        <div class="d-flex flex-wrap">
                                                            <span class="cursor-pointer trash"
                                                                  wire:click="deleteThemeTask('{{ $item['id'] }}')">
                                                                <i class="fas fa-trash text-danger"></i></span>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                            <span class="color-fusion-500 fs-3x py-3"><i
                                                                        class="fas fa-exclamation-triangle color-warning-900"></i>
                                                                No se encontraron actividades</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <form wire:submit.prevent="submitPlan()" method="post" autocomplete="off"
                                  style="margin-top: 1.5rem; margin-left: 1.5rem;">
                                <div></div>
                                @if(!$is_terminated)
                                    <x-label-section>{{ trans('poa.piat_matrix_activity_plan') }}</x-label-section>
                                    <div class="section-divider"></div>
                                    <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                        <div class="form-group w-50 pr-1">
                                            <label class="form-label fw-700"
                                                   for="task">{{ trans('poa.piat_matrix_create_placeholder_task') }}</label>
                                            <input @if($is_terminated) disabled @endif type="text" id="task" class="form-control"
                                                   placeholder="{{ trans('poa.piat_matrix_create_placeholder_task') }}"
                                                   wire:model="taskPlan">
                                            <div class="invalid-feedback" style="display: block;">{{ $errors->first('taskPlan') }}</div>
                                        </div>
                                        <div class="form-group w-50">
                                            <label class="form-label fw-700"
                                                   for="responsablePlan">{{ trans('poa.piat_matrix_create_placeholder_responsable') }}</label>
                                            <select @if($is_terminated) disabled @endif wire:model="responsablePlan" class="custom-select bg-transparent">
                                                <option value="{{ $responsablePlan }}" selected>
                                                    {{ trans('poa.piat_matrix_create_placeholder_responsable') }}
                                                </option>
                                                @foreach ($users as $item)
                                                    <option value="{{ $item->id }}">{{ $item->getFullName() }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" style="display: block;">{{ $errors->first('responsablePlan') }}</div>
                                        </div>
                                        <div class="form-group"></div>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center justify-content-between mr-2">
                                        <div class="form-group w-30 pr-1">
                                            <label class="form-label fw-700"
                                                   for="datePlan">{{ trans('poa.piat_matrix_create_placeholder_date') }}</label>
                                            <input @if($is_terminated) disabled @endif type="date" wire:model="datePlan" id="datePlan" class="form-control"
                                                   placeholder="{{ trans('general.form.enter', ['field' => trans('poa.piat_matrix_create_placeholder_date')]) }}"/>
                                            <div class="invalid-feedback" style="display: block;">{{ $errors->first('datePlan') }}</div>
                                        </div>
                                        <div class="form-group w-30">
                                            <label class="form-label fw-700 timepicker"
                                                   for="initTimePlan">{{ trans('poa.piat_matrix_create_placeholder_initial_time') }}</label>
                                            <input @if($is_terminated) disabled @endif type="time" wire:model="initTimePlan" id="initTimePlan"
                                                   class="form-control"
                                                   placeholder="{{ trans('poa.piat_matrix_create_placeholder_initial_time') }}"/>
                                            <div class="invalid-feedback" style="display: block;">{{ $errors->first('initTimePlan') }}</div>
                                        </div>
                                        <div class="form-group w-30">
                                            <label class="form-label fw-700 timepicker"
                                                   for="endTimePlan">{{ trans('poa.piat_matrix_create_placeholder_end_time') }}</label>
                                            <input @if($is_terminated) disabled @endif type="time" wire:model="endTimePlan" id="endTimePlan"
                                                   class="form-control"
                                                   placeholder="{{ trans('poa.piat_matrix_create_placeholder_end_time') }}"/>
                                            <div class="invalid-feedback" style="display: block;">{{ $errors->first('endTimePlan') }}</div>
                                        </div>
                                        <div class="form-group">
                                            <input hidden type="text" wire:model="planId" id="planId"
                                                   value="{{ $planId }}"/>
                                        </div>
                                    </div>
                                @endif

                                <div class="modal-footer justify-content-center">
                                    <x-form.modal.footer data-dismiss="modal"></x-form.modal.footer>
                                    @if(!$is_terminated)
                                        <button wire:click="closeModal" type="button" class="btn btn-danger btn-sm">
                                            <span aria-hidden="true">Terminar</span>
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="card tab-pane fade" id="requirements" role="tabpanel" wire:ignore.self>
                            <div class="card-body pt-0">
                                <div class="frame-wrap">
                                    <table class="table m-0">
                                        <thead>
                                        <tr>
                                            <th>{{ __('poa.piat_matrix_create_placeholder_description') }}</th>
                                            <th>{{ __('poa.piat_matrix_create_placeholder_quantity') }}</th>
                                            <th>{{ __('poa.piat_matrix_create_placeholder_cost') }}</th>
                                            <th><a href="#">{{ trans('general.actions') }} </a></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($piatReq as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex float-left">
                                                            <span
                                                                    class="color-item shadow-hover-5 mr-2 cursor-default"></span>
                                                        <span>{{ $item['description'] }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex float-left">
                                                            <span
                                                                    class="color-item shadow-hover-5 mr-2 cursor-default"></span>
                                                        <span>{{ $item['quantity'] }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex float-left">
                                                            <span
                                                                    class="color-item shadow-hover-5 mr-2 cursor-default"></span>
                                                        <span>{{ $item['approximate_cost'] }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-wrap">
                                                            <span class="cursor-pointer trash"
                                                                  wire:click="editRequirements('{{ $item['id'] }}')">
                                                                <i class="fas fa-edit"></i></span>
                                                    </div>
                                                    <div class="d-flex flex-wrap">
                                                            <span class="cursor-pointer trash"
                                                                  wire:click="deleteRequirements('{{ $item['id'] }}')">
                                                                <i class="fas fa-trash text-danger"></i></span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                            <span class="color-fusion-500 fs-3x py-3"><i
                                                                        class="fas fa-exclamation-triangle color-warning-900"></i>
                                                                No se encontraron requerimientos</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <form wire:submit.prevent="submitRequirements()" method="post" autocomplete="off"
                                  style="margin-top: 1.5rem; margin-left: 1.5rem;">
                                @if(!$is_terminated)
                                    <x-label-section>{{ trans('poa.piat_matrix_activity_requirement') }}
                                    </x-label-section>
                                    <div class="section-divider"></div>
                                    <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                        <div class="form-group w-50 pr-1">
                                            <label class="form-label fw-700"
                                                   for="description">{{ trans('poa.piat_matrix_create_placeholder_description') }}</label>
                                            <input @if($is_terminated) disabled @endif type="text" wire:model="description" id="descriptionReq"
                                                   class="form-control"
                                                   placeholder="{{ trans('poa.piat_matrix_create_placeholder_description') }}"/>
                                            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                        </div>
                                        <div class="form-group w-50 pr-1">
                                            <label class="form-label fw-700"
                                                   for="quantity">{{ trans('poa.piat_matrix_create_placeholder_quantity') }}</label>
                                            <input @if($is_terminated) disabled @endif type="text" wire:model="quantity" id="quantity" class="form-control"
                                                   placeholder="{{ trans('poa.piat_matrix_create_placeholder_quantity') }}"/>
                                            <div class="invalid-feedback">{{ $errors->first('quantity') }}</div>
                                        </div>
                                        <div class="form-group"></div>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                        <div class="form-group w-50 pr-1">
                                            <label class="form-label fw-700"
                                                   for="approximateCost">{{ trans('poa.piat_matrix_create_placeholder_cost') }}</label>
                                            <input @if($is_terminated) disabled @endif type="text" wire:model="approximateCost" id="approximateCost"
                                                   class="form-control"
                                                   placeholder="{{ trans('poa.piat_matrix_create_placeholder_cost') }}"/>
                                            <div class="invalid-feedback">{{ $errors->first('approximateCost') }}
                                            </div>
                                        </div>
                                        <div class="form-group w-50">
                                            <label class="form-label fw-700"
                                                   for="responsableReq">{{ trans('poa.piat_matrix_create_placeholder_responsable') }}</label>
                                            <select @if($is_terminated) disabled @endif wire:model="responsableReq" class="custom-select bg-transparent">
                                                <option value="{{ $responsableReq }}" selected>
                                                    {{ trans('poa.piat_matrix_create_placeholder_responsable') }}
                                                </option>
                                                @foreach ($users as $item)
                                                    <option value="{{ $item->id }}">{{ $item->getFullName() }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">{{ $errors->first('responsableReq') }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input hidden type="text" wire:model="reqId" id="reqId"
                                                   value="{{ $reqId }}"/>
                                        </div>
                                    </div>
                                @endif
                                <div class="modal-footer justify-content-center">
                                    <x-form.modal.footer data-dismiss="modal"></x-form.modal.footer>
                                    @if(!$is_terminated)
                                        <button wire:click="closeModal" type="button" class="btn btn-danger btn-sm">
                                            <span aria-hidden="true">Terminar</span>
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
