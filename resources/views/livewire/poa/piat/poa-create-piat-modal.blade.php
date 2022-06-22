<div wire:ignore.self class="modal fade" id="add_piat_modal" tabindex="-1" role="dialog" aria-hidden="true"
    style="height: 100%;">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div wire:ignore class="modal-header bg-primary text-white">
                <h5 class="modal-title">{{ trans('general.add_new') }}</h5>
                <button type="button" data-dismiss="modal" class="close text-white" aria-label="Close">
                    <span aria-hidden="true"><i class="far fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="panel-content">
                    @if ($newPoaActivityPiatId == null)
                        <form wire:submit.prevent="submit()" method="post" autocomplete="off">
                            <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                <div class="form-group w-65 pr-1">
                                    <label class="form-label fw-700"
                                        for="name">{{ trans('poa.piat_matrix_create_placeholder_name') }}</label>
                                    <input type="text"
                                        class="form-control bg-transparent @error('name') is-invalid @enderror"
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
                                    <input type="text" wire:model="place" class="form-control "
                                        placeholder="{{ trans('poa.piat_matrix_create_placeholder_place') }}" />
                                </div>
                                <div class="form-group w-50">
                                    <label class="form-label fw-700"
                                        for="date">{{ trans('poa.piat_matrix_create_placeholder_date') }}</label>
                                    <input type="date" wire:model="date"
                                        class="form-control bg-transparent @error('date') is-invalid @enderror"
                                        placeholder="{{ trans('general.form.enter', ['field' => trans('poa.piat_matrix_create_placeholder_date')]) }}" />
                                    <div class="invalid-feedback">{{ $errors->first('date') }}</div>
                                </div>
                                <div class="form-group"></div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                <div class="form-group w-50 pr-1">
                                    <label class="form-label fw-700 timepicker"
                                        for="initTime">{{ trans('poa.piat_matrix_create_placeholder_initial_time') }}</label>
                                    <input type="time" wire:model="initTime"
                                        class="form-control bg-transparent @error('initTime') is-invalid @enderror"
                                        placeholder="{{ trans('poa.piat_matrix_create_placeholder_initial_time') }}" />
                                    <div class="invalid-feedback">{{ $errors->first('initTime') }}</div>
                                </div>
                                <div class="form-group w-50">
                                    <label class="form-label fw-700 timepicker"
                                        for="endTime">{{ trans('poa.piat_matrix_create_placeholder_end_time') }}</label>
                                    <input type="time" wire:model="endTime"
                                        class="form-control bg-transparent @error('endTime') is-invalid @enderror"
                                        placeholder="{{ trans('poa.piat_matrix_create_placeholder_end_time') }}" />
                                    <div class="invalid-feedback">{{ $errors->first('endTime') }}</div>
                                </div>
                                <div class="form-group"></div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between mr-2">
                                <div class="form-group w-30 pr-1">
                                    <label class="form-label fw-700"
                                        for="province">{{ trans('poa.piat_matrix_create_placeholder_province') }}</label>
                                    <select wire:model="province"
                                        class="custom-select bg-transparent @error('province') is-invalid @enderror"
                                        id="province">
                                        <option value="" selected>
                                            {{ trans('poa.piat_matrix_create_placeholder_province') }}
                                        </option>
                                        @foreach ($provinces as $item)
                                            <option value="{{ $item->id }}">{{ $item->description }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">{{ $errors->first('province') }}</div>
                                </div>
                                <div class="form-group w-30">
                                    <label class="form-label fw-700"
                                        for="canton">{{ trans('poa.piat_matrix_create_placeholder_canton') }}</label>
                                    <select wire:model="canton"
                                        class="custom-select bg-transparent @error('canton') is-invalid @enderror">
                                        <option value="" selected>
                                            {{ trans('poa.piat_matrix_create_placeholder_canton') }}
                                        </option>
                                        @foreach ($cantons as $item)
                                            <option value="{{ $item->id }}">{{ $item->description }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">{{ $errors->first('canton') }}</div>
                                </div>
                                <div class="form-group w-30">
                                    <label class="form-label fw-700"
                                        for="parish">{{ trans('poa.piat_matrix_create_placeholder_parish') }}</label>
                                    <select wire:model="parish"
                                        class="custom-select bg-transparent @error('parish') is-invalid @enderror">
                                        <option value="" selected>
                                            {{ trans('poa.piat_matrix_create_placeholder_parish') }}
                                        </option>
                                        @foreach ($parishes as $item)
                                            <option value="{{ $item->id }}">{{ $item->description }}</option>
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
                                    <input type="number" wire:model="numberMaleResp" class="form-control"
                                        placeholder="Cantidad de responsables hombres" />
                                    <div class="invalid-feedback">{{ $errors->first('numberMaleResp') }}</div>
                                </div>
                                <div class="form-group w-50">
                                    <label class="form-label fw-700"
                                        for="numberFeMaleResp">{{ trans('poa.piat_matrix_create_placeholder_resp_female') }}</label>
                                    <input type="number" wire:model="numberFeMaleResp" class="form-control"
                                        placeholder="Cantidad de responsables mujeres" />
                                    <div class="invalid-feedback">{{ $errors->first('numberFeMaleResp') }}</div>
                                </div>
                                <div class="form-group"></div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                <div class="form-group w-50 pr-1">
                                    <label class="form-label fw-700"
                                        for="maleBenef">{{ trans('poa.piat_matrix_create_placeholder_benef_male') }}</label>
                                    <input type="number" wire:model="maleBenef" class="form-control"
                                        placeholder="Cantidad de beneficiarios hombres" />
                                    <div class="invalid-feedback">{{ $errors->first('maleBenef') }}</div>
                                </div>
                                <div class="form-group w-50">
                                    <label class="form-label fw-700"
                                        for="femaleBenef">{{ trans('poa.piat_matrix_create_placeholder_benef_female') }}</label>
                                    <input type="number" wire:model="femaleBenef" class="form-control"
                                        placeholder="Cantidad de beneficiarios mujeres" />
                                    <div class="invalid-feedback">{{ $errors->first('femaleBenef') }}</div>
                                </div>
                                <div class="form-group"></div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                <div class="form-group w-50 pr-1">
                                    <label class="form-label fw-700"
                                        for="maleVol">{{ trans('poa.piat_matrix_create_placeholder_vol_male') }}</label>
                                    <input type="number" wire:model="maleVol" class="form-control"
                                        placeholder="Cantidad de vlontarios hombres" />
                                    <div class="invalid-feedback">{{ $errors->first('maleVol') }}</div>
                                </div>
                                <div class="form-group w-50">
                                    <label class="form-label fw-700"
                                        for="femaleVol">{{ trans('poa.piat_matrix_create_placeholder_vol_female') }}</label>
                                    <input type="number" wire:model="femaleVol" class="form-control"
                                        placeholder="Cantidad de vlontarios mujeres" />
                                    <div class="invalid-feedback">{{ $errors->first('femaleVol') }}</div>
                                </div>
                                <div class="form-group"></div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                <div class="form-group w-65 pr-1">
                                    <label class="form-label fw-700"
                                        for="goal">{{ trans('poa.piat_matrix_create_placeholder_goals') }}</label>
                                    <textarea wire:model="goal" class="form-control"
                                        placeholder="{{ trans('poa.piat_matrix_create_placeholder_goals') }}"></textarea>
                                    <div class="invalid-feedback">{{ $errors->first('goal') }}</div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <x-form.modal.footer data-dismiss="modal"></x-form.modal.footer>
                            </div>
                        </form>
                    @endif
                    @if ($newPoaActivityPiatId != null)
                        @if (count($piatPlan) > 0)
                            <div class="card-body pt-0">
                                <div class="frame-wrap">
                                    <table class="table m-0">
                                        <thead>
                                            <tr>
                                                <th>{{ __('poa.piat_matrix_create_placeholder_task') }}</th>
                                                <th>{{ __('poa.piat_matrix_create_placeholder_date') }}</th>
                                                <th>{{ __('poa.piat_matrix_create_placeholder_initial_time') }}</th>
                                                <th>{{ __('poa.piat_matrix_create_placeholder_end_time') }}</th>
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
                        @endif
                        <form wire:submit.prevent="submitPlan()" method="post" autocomplete="off">
                            <div>MATRIZ: {{ $newPoaActivityPiatName }}</div>
                            <div></div>
                            <div class="section-divider"></div>
                            <x-label-section>{{ trans('poa.piat_matrix_activity_plan') }}</x-label-section>
                            <div class="section-divider"></div>

                            <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                <div class="form-group w-50 pr-1">
                                    <label class="form-label fw-700"
                                        for="task">{{ trans('poa.piat_matrix_create_placeholder_task') }}</label>
                                    <input type="text" class="form-control"
                                        placeholder="{{ trans('poa.piat_matrix_create_placeholder_task') }}"
                                        wire:model="task">
                                    <div class="invalid-feedback">{{ $errors->first('task') }}</div>
                                </div>
                                <div class="form-group w-50">
                                    <label class="form-label fw-700"
                                        for="responsable">{{ trans('poa.piat_matrix_create_placeholder_responsable') }}</label>
                                    <select wire:model="responsable" class="custom-select bg-transparent">
                                        <option value="" selected>
                                            {{ trans('poa.piat_matrix_create_placeholder_responsable') }}
                                        </option>
                                        @foreach ($users as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">{{ $errors->first('responsable') }}</div>
                                </div>
                                <div class="form-group"></div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                <div class="form-group w-65">
                                    <label class="form-label fw-700"
                                        for="planDate">{{ trans('poa.piat_matrix_create_placeholder_date') }}</label>
                                    <input type="date" wire:model="planDate" class="form-control"
                                        placeholder="{{ trans('general.form.enter', ['field' => trans('poa.piat_matrix_create_placeholder_date')]) }}" />
                                    <div class="invalid-feedback">{{ $errors->first('planDate') }}</div>
                                </div>
                                <div></div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                <div class="form-group w-50 pr-1">
                                    <label class="form-label fw-700 timepicker"
                                        for="planInitTime">{{ trans('poa.piat_matrix_create_placeholder_initial_time') }}</label>
                                    <input type="time" wire:model="planInitTime" class="form-control"
                                        placeholder="{{ trans('poa.piat_matrix_create_placeholder_initial_time') }}" />
                                    <div class="invalid-feedback">{{ $errors->first('planInitTime') }}</div>
                                </div>
                                <div class="form-group w-50">
                                    <label class="form-label fw-700 timepicker"
                                        for="planEndTime">{{ trans('poa.piat_matrix_create_placeholder_end_time') }}</label>
                                    <input type="time" wire:model="planEndTime" class="form-control"
                                        placeholder="{{ trans('poa.piat_matrix_create_placeholder_end_time') }}" />
                                    <div class="invalid-feedback">@error('planEditTime') {{ $message }} @enderror</div>
                                </div>
                                <div class="form-group"></div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <x-form.modal.footer data-dismiss="modal"></x-form.modal.footer>
                            </div>
                        </form>
                    @endif

                    @if ($newPoaActivityPiatId != null)
                        @if (count($piatReq) > 0)
                            <div class="card-body pt-0">
                                <div class="frame-wrap">
                                    <table class="table m-0">
                                        <thead>
                                            <tr>
                                                <th>{{ __('poa.piat_matrix_create_placeholder_description') }}</th>
                                                <th>{{ __('poa.piat_matrix_create_placeholder_quantity') }}</th>
                                                <th>{{ __('poa.piat_matrix_create_placeholder_cost') }}</th>
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
                        @endif
                        <form wire:submit.prevent="submitRequirements()" method="post" autocomplete="off">
                            <div class="section-divider"></div>
                            <x-label-section>{{ trans('poa.piat_matrix_activity_requirement') }}</x-label-section>
                            <div class="section-divider"></div>

                            <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                <div class="form-group w-50 pr-1">
                                    <label class="form-label fw-700"
                                        for="description">{{ trans('poa.piat_matrix_create_placeholder_description') }}</label>
                                    <input type="text" class="form-control"
                                        placeholder="{{ trans('poa.piat_matrix_create_placeholder_description') }}"
                                        wire:model="description">
                                    <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                </div>
                                <div class="form-group w-50 pr-1">
                                    <label class="form-label fw-700"
                                        for="quantity">{{ trans('poa.piat_matrix_create_placeholder_quantity') }}</label>
                                    <input type="text" class="form-control"
                                        placeholder="{{ trans('poa.piat_matrix_create_placeholder_quantity') }}"
                                        wire:model="quantity">
                                    <div class="invalid-feedback">{{ $errors->first('quantity') }}</div>
                                </div>
                                <div class="form-group"></div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                <div class="form-group w-50 pr-1">
                                    <label class="form-label fw-700"
                                        for="approxCost">{{ trans('poa.piat_matrix_create_placeholder_cost') }}</label>
                                    <input type="text" class="form-control"
                                        placeholder="{{ trans('poa.piat_matrix_create_placeholder_cost') }}"
                                        wire:model="approxCost">
                                    <div class="invalid-feedback">{{ $errors->first('approxCost') }}</div>
                                </div>
                                <div class="form-group w-50">
                                    <label class="form-label fw-700"
                                        for="reqResponsable">{{ trans('poa.piat_matrix_create_placeholder_responsable') }}</label>
                                    <select wire:model="reqResponsable" class="custom-select bg-transparent">
                                        <option value="" selected>
                                            {{ trans('poa.piat_matrix_create_placeholder_responsable') }}
                                        </option>
                                        @foreach ($users as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">{{ $errors->first('reqResponsable') }}</div>
                                </div>
                                <div class="form-group"></div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <x-form.modal.footer data-dismiss="modal"></x-form.modal.footer>
                                <button wire:click="closeModal" type="button" class="btn btn-danger btn-sm">
                                    <span aria-hidden="true">Terminar</span>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
