<div wire:ignore.self class="modal fade" id="report_piat_modal" tabindex="-1" role="dialog" aria-hidden="true"
    style="height: 100%;">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div wire:ignore class="modal-header bg-primary text-white">
                <h5 class="modal-title">{{ trans('general.poa_activity_piat_report_modal') }}</h5>
                <button type="button" data-dismiss="modal" class="close text-white" aria-label="Close">
                    <span aria-hidden="true"><i class="far fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="panel-content">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#piatMatrix" role="tab"
                                aria-selected="true" wire:ignore>
                                <i class="fal fa-user text-primary"></i>
                                <span class="hidden-sm-down ml-1">Matriz Piat</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#reportData" role="tab"
                                aria-selected="false" wire:ignore>
                                <i class="fal fa-address-card"></i>
                                <span class="hidden-sm-down ml-1">Datos del Informe</span>
                            </a>
                        </li>
                        @if($flagShowTabs)
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#agreements" role="tab"
                                aria-selected="false" wire:ignore>
                                <i class="fal fa-address-card"></i>Acuerdos y Compromisos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#beneficiaries" role="tab"
                                aria-selected="false" wire:ignore>
                                <i class="fal fa-address-card"></i>
                                <span class="hidden-sm-down ml-1">Beneficiarios</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                    <div class="tab-content border border-top-0 p-3">
                        <div class="card tab-pane fade show active" id="piatMatrix" role="tabpanel" wire:ignore.self>
                            <div style="margin-top: 1.5rem; margin-left: 1.5rem">
                            <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                <div class="form-group w-65 pr-1">
                                    <label class="form-label fw-700"
                                        for="name">{{ trans('poa.piat_matrix_create_placeholder_name') }}</label>
                                    <input disabled type="text"
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
                                    <input disabled type="text" wire:model="place" class="form-control"
                                        placeholder="{{ trans('poa.piat_matrix_create_placeholder_place') }}" />
                                    <div class="invalid-feedback">{{ $errors->first('place') }}</div>
                                </div>
                                <div class="form-group w-50">
                                    <label class="form-label fw-700"
                                        for="date">{{ trans('poa.piat_matrix_create_placeholder_date') }}</label>
                                    <input disabled type="date" wire:model="date"
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
                                    <input disabled type="time" wire:model="initTime"
                                        class="form-control bg-transparent  @error('initTime') is-invalid @enderror"
                                        placeholder="{{ trans('poa.piat_matrix_create_placeholder_initial_time') }}" />
                                    <div class="invalid-feedback">{{ $errors->first('initTime') }}</div>
                                </div>
                                <div class="form-group w-50">
                                    <label class="form-label fw-700 timepicker"
                                        for="endTime">{{ trans('poa.piat_matrix_create_placeholder_end_time') }}</label>
                                    <input disabled type="time" wire:model="endTime"
                                        class="form-control bg-transparent  @error('endTime') is-invalid @enderror"
                                        placeholder="{{ trans('poa.piat_matrix_create_placeholder_end_time') }}" />
                                    <div class="invalid-feedback">{{ $errors->first('endTime') }}</div>
                                </div>
                                <div class="form-group"></div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between mr-2">
                                <div class="form-group w-30 pr-1">
                                    <label class="form-label fw-700"
                                        for="province">{{ trans('poa.piat_matrix_create_placeholder_province') }}</label>
                                    <select disabled wire:model="province"
                                        class="custom-select bg-transparent  @error('province') is-invalid @enderror"
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
                                    <select disabled wire:model="canton"
                                        class="custom-select bg-transparent  @error('canton') is-invalid @enderror">
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
                                    <select disabled wire:model="parish"
                                        class="custom-select bg-transparent  @error('parish') is-invalid @enderror">
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
                                    <input disabled type="number" wire:model="numberMaleResp" class="form-control"
                                        placeholder="Cantidad de responsables hombres" />
                                    <div class="invalid-feedback">{{ $errors->first('numberMaleResp') }}</div>
                                </div>
                                <div class="form-group w-50">
                                    <label class="form-label fw-700"
                                        for="numberFeMaleResp">{{ trans('poa.piat_matrix_create_placeholder_resp_female') }}</label>
                                    <input disabled type="number" wire:model="numberFeMaleResp" class="form-control"
                                        placeholder="Cantidad de responsables mujeres" />
                                    <div class="invalid-feedback">{{ $errors->first('numberFeMaleResp') }}</div>
                                </div>
                                <div class="form-group"></div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                <div class="form-group w-50 pr-1">
                                    <label class="form-label fw-700"
                                        for="maleBenef">{{ trans('poa.piat_matrix_create_placeholder_benef_male') }}</label>
                                    <input disabled type="number" wire:model="maleBenef" class="form-control"
                                        placeholder="Cantidad de beneficiarios hombres" />
                                    <div class="invalid-feedback">{{ $errors->first('maleBenef') }}</div>
                                </div>
                                <div class="form-group w-50">
                                    <label class="form-label fw-700"
                                        for="femaleBenef">{{ trans('poa.piat_matrix_create_placeholder_benef_female') }}</label>
                                    <input disabled type="number" wire:model="femaleBenef" class="form-control"
                                        placeholder="Cantidad de beneficiarios mujeres" />
                                    <div class="invalid-feedback">{{ $errors->first('femaleBenef') }}</div>
                                </div>
                                <div class="form-group"></div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                <div class="form-group w-50 pr-1">
                                    <label class="form-label fw-700"
                                        for="maleVol">{{ trans('poa.piat_matrix_create_placeholder_vol_male') }}</label>
                                    <input disabled type="number" wire:model="maleVol" class="form-control"
                                        placeholder="Cantidad de vlontarios hombres" />
                                    <div class="invalid-feedback">{{ $errors->first('maleVol') }}</div>
                                </div>
                                <div class="form-group w-50">
                                    <label class="form-label fw-700"
                                        for="femaleVol">{{ trans('poa.piat_matrix_create_placeholder_vol_female') }}</label>
                                    <input disabled type="number" wire:model="femaleVol" class="form-control"
                                        placeholder="Cantidad de vlontarios mujeres" />
                                    <div class="invalid-feedback">{{ $errors->first('femaleVol') }}</div>
                                </div>
                                <div class="form-group"></div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                <div class="form-group w-50 pr-1">
                                    <label class="form-label fw-700"
                                        for="createdBy">{{ trans('poa.piat_matrix_create_placeholder_created_by') }}</label>
                                    <select disabled wire:model="createdBy"
                                        class="custom-select bg-transparent  @error('createdBy') is-invalid @enderror">
                                        <option value="" selected>
                                            {{ trans('poa.piat_matrix_create_placeholder_created_by') }}</option>
                                        @foreach ($users as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">{{ $errors->first('createdBy') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="card tab-pane fade" id="reportData" role="tabpanel" wire:ignore.self>
                            <form wire:submit.prevent="submit()" method="post" autocomplete="off" style="margin-top: 1.5rem; margin-left: 1.5rem">
                                <x-label-section>{{ trans('poa.piat_matrix_report_divider') }}</x-label-section>
                                <div class="section-divider"></div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                    <div class="form-group w-50">
                                        <label class="form-label fw-700"
                                            for="accomplished">{{ trans('poa.piat_matrix_report_placeholder_accomplished') }}</label>
                                        <div class="mb-2">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="accomplished_yes"
                                                       name="accomplished" value="true" wire:model="accomplished"
                                                       @if ($accomplished) checked @endif>
                                                <label class="custom-control-label"
                                                       for="accomplished_yes">{{ trans('general.yes') }}</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="accomplished_no"
                                                       name="accomplished" value="false" wire:model="accomplished"
                                                       @if (!$accomplished) checked @endif>
                                                <label class="custom-control-label"
                                                       for="accomplished_no">{{ trans('general.no') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group w-50 pr-1">
                                        <label class="form-label fw-700"
                                               for="description">{{ trans('poa.piat_matrix_report_placeholder_description') }}</label>
                                        <textarea wire:model="description" class="form-control"
                                                  placeholder="{{ trans('poa.piat_matrix_create_placeholder_description') }}"
                                                  rows="3"></textarea>
                                        <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                    </div>
                                    <div class="form-group"></div>
                                </div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                    <div class="form-group w-50">
                                        <label class="form-label fw-700"
                                               for="reportDate">{{ trans('poa.piat_matrix_create_placeholder_date') }}</label>
                                        <input type="date" wire:model="reportDate"
                                               class="form-control bg-transparent @error('reportDate') is-invalid @enderror"
                                               placeholder="{{ trans('general.form.enter', ['field' => trans('poa.piat_matrix_create_placeholder_date')]) }}"/>
                                        <div class="invalid-feedback">{{ $errors->first('reportDate') }}</div>
                                    </div>
                                    <div class="form-group"></div>
                                </div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                    <div class="form-group w-50 pr-1">
                                        <label class="form-label fw-700 timepicker"
                                               for="reportInitTime">{{ trans('poa.piat_matrix_create_placeholder_initial_time') }}</label>
                                        <input type="time" wire:model="reportInitTime"
                                               class="form-control bg-transparent  @error('reportInitTime') is-invalid @enderror"
                                               placeholder="{{ trans('poa.piat_matrix_create_placeholder_initial_time') }}"/>
                                        <div class="invalid-feedback">{{ $errors->first('reportInitTime') }}</div>
                                    </div>
                                    <div class="form-group w-50">
                                        <label class="form-label fw-700 timepicker"
                                               for="reportEndTime">{{ trans('poa.piat_matrix_create_placeholder_end_time') }}</label>
                                        <input type="time" wire:model="reportEndTime"
                                               class="form-control bg-transparent  @error('reportEndTime') is-invalid @enderror"
                                               placeholder="{{ trans('poa.piat_matrix_create_placeholder_end_time') }}"/>
                                        <div class="invalid-feedback">{{ $errors->first('reportEndTime') }}</div>
                                    </div>
                                    <div class="form-group"></div>
                                </div>
                                <x-label-section>{{ trans('poa.piat_matrix_report_divider_evaluation') }}
                                </x-label-section>
                                <div class="section-divider"></div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between w-65 mr-2">
                                    <div class="form-group w-50 pr-1">
                                        <label class="form-label fw-700"
                                               for="positiveEvaluation">{{ trans('poa.piat_matrix_report_placeholder_positive_evaluation') }}</label>
                                        <textarea id="positive" wire:model="positiveEvaluation" class="form-control"
                                                  placeholder="{{ trans('poa.piat_matrix_report_placeholder_positive_evaluation') }}"
                                                  rows="3"></textarea>
                                        <div class="invalid-feedback">{{ $errors->first('positiveEvaluation') }}
                                        </div>
                                    </div>
                                    <div class="form-group w-50">
                                        <label class="form-label fw-700"
                                               for="evalForInprove">{{ trans('poa.piat_matrix_report_placeholder_inprove_evaluation') }}</label>
                                        <textarea wire:model="evalForInprove" class="form-control"
                                                  placeholder="{{ trans('poa.piat_matrix_report_placeholder_inprove_evaluation') }}"
                                                  rows="3"></textarea>
                                        <div class="invalid-feedback">{{ $errors->first('evalForInprove') }}</div>
                                    </div>
                                    <div class="form-group"></div>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <x-form.modal.footer data-dismiss="modal"></x-form.modal.footer>
                                </div>
                            </form>
                        </div>
                        <div class="card tab-pane fade" id="agreements" role="tabpanel" wire:ignore.self>
                            <form wire:submit.prevent="submitAgreementsCommitments()" method="post" autocomplete="off" style="margin-top: 1.5rem; margin-left: 1.5rem">
                                <x-label-section>
                                    {{ trans('poa.piat_matrix_report_divider_agreement_commitment') }}
                                </x-label-section>
                                <div class="section-divider"></div>
                                @forelse($piatReportAgreComm as $item)
                                    <div class="d-flex flex-wrap align-items-center justify-content-between mr-2">
                                        <div class="form-group w-30 pr-1">
                                            <label class="form-label fw-700"
                                                   for="description">{{ trans('poa.piat_matrix_report_divider_agreement_commitment') }}</label>
                                            <input type="text"
                                                   id="piatReportAgreComm.{{ $loop->index }}.description"
                                                   class="form-control"
                                                   placeholder="{{ trans('poa.piat_matrix_report_divider_agreement_commitment') }}"
                                                   value="{{ $item['description'] }}"
                                                   wire:model="piatReportAgreComm.{{ $loop->index }}.description">
                                        </div>
                                        <div class="form-group w-30">
                                            <label class="form-label fw-700"
                                                   for="responsable">{{ trans('poa.piat_matrix_create_placeholder_responsable') }}</label>
                                            <select
                                                    wire:model="piatReportAgreComm.{{ $loop->index }}.responsable"
                                                    class="custom-select bg-transparent">
                                                <option value="{{ $item['responsable'] }}" selected>
                                                    {{ trans('poa.piat_matrix_create_placeholder_responsable') }}
                                                </option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group w-30">
                                            <button class="mr-2 border-0"
                                                    wire:click.prevent="deleteAgreementsCommitments({{ $item['id'] }})"
                                                    style="border: 0 !important; background-color: transparent !important; margin-top: 1.5rem;">
                                                <i class="fas fa-trash mr-1 text-danger"></i>
                                            </button>
                                        </div>
                                        <div class="form-group"></div>
                                    </div>
                                @empty
                                @endforelse

                                <div class="d-flex flex-wrap align-items-center justify-content-between mr-2">
                                    <div class="form-group w-30 pr-1">
                                        <label class="form-label fw-700"
                                               for="agreCommDescription">{{ trans('poa.piat_matrix_report_divider_agreement_commitment') }}</label>
                                        <input type="text" id="agreCommDescription" class="form-control"
                                               placeholder="{{ trans('poa.piat_matrix_report_divider_agreement_commitment') }}"
                                               wire:model="agreCommDescription">
                                    </div>
                                    <div class="form-group w-30">
                                        <label class="form-label fw-700"
                                               for="agreCommResponsable">{{ trans('poa.piat_matrix_create_placeholder_responsable') }}</label>
                                        <select wire:model="agreCommResponsable"
                                                class="custom-select bg-transparent  @error('agreCommResponsable') is-invalid @enderror">
                                            <option value="" selected>
                                                {{ trans('poa.piat_matrix_create_placeholder_responsable') }}
                                            </option>
                                            @foreach ($users as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group w-30">
                                        <button class="mr-2 border-0"
                                                wire:click.prevent="addAgreementsCommitments"
                                                style="border: 0 !important; background-color: transparent !important; margin-top: 1.5rem;">
                                            <i class="fas fa-plus mr-1 text-success"></i>
                                        </button>
                                    </div>
                                    <div class="form-group"></div>
                                </div>
                                @if (count($piatReportAgreComm) > 0)
                                    <div class="modal-footer justify-content-center">
                                        <x-form.modal.footer data-dismiss="modal"></x-form.modal.footer>
                                    </div>
                                @endif
                            </form>
                        </div>
                        <div class="card tab-pane fade" id="beneficiaries" role="tabpanel" wire:ignore.self>
                            <div style="margin-top: 1.5rem; margin-left: 1.5rem">
                                <x-label-section>{{ trans('poa.piat_matrix_report_divider_beneficiaries') }}
                                </x-label-section>
                                <div class="section-divider"></div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between mr-2">
                                    <div class="form-group w-30 pr-1">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_men') }}:
                                        </x-label-section>
                                        <span aria-hidden="true">{{ $contMen }}</span>
                                    </div>
                                    <div class="form-group w-30">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_women') }}:
                                        </x-label-section>
                                        <span aria-hidden="true">{{ $contWomen }}</span>
                                    </div>
                                    <div class="form-group w-30">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_total') }}:
                                        </x-label-section>
                                        <span aria-hidden="true"
                                              style="color: red; font-weight: bold;">{{ $contMen + $contWomen }}</span>
                                    </div>
                                    <div class="form-group"></div>
                                </div>
                                <x-label-section>
                                    {{ trans('poa.piat_matrix_report_divider_beneficiaries_disabilities') }}
                                </x-label-section>
                                <div class="section-divider"></div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between mr-2">
                                    <div class="form-group w-30 pr-1">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_disabilities_yes') }}:
                                        </x-label-section>
                                        <span aria-hidden="true" style="color: red; font-weight: bold;">
                                        @if ($contDisability > 0)
                                                X
                                            @endif
                                    </span>
                                    </div>
                                    <div class="form-group w-30">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_disabilities_total') }}:
                                        </x-label-section>
                                        <span aria-hidden="true">
                                        @if ($contDisability > 0)
                                                {{ $contDisability }}
                                            @endif
                                    </span>
                                    </div>
                                    <div class="form-group w-30">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_disabilities_no') }}:
                                        </x-label-section>
                                        <span aria-hidden="true" style="color: red; font-weight: bold;">
                                        @if ($contDisability < 1)
                                                X
                                            @endif
                                    </span>
                                    </div>
                                    <div class="form-group"></div>
                                </div>
                                <x-label-section>
                                    {{ trans('poa.piat_matrix_report_divider_beneficiaries_age_group') }}
                                </x-label-section>
                                <div class="section-divider"></div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between mr-2">
                                    <div class="form-group w-10">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_age_group_under_6') }}:
                                        </x-label-section>
                                        <span aria-hidden="true"
                                              style="color: red; font-weight: bold;">{{ $under6 }}</span>
                                    </div>
                                    <div class="form-group w-10">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_age_group_between_6_12') }}:
                                        </x-label-section>
                                        <span aria-hidden="true"
                                              style="color: red; font-weight: bold;">{{ $btw6And12 }}</span>
                                    </div>
                                    <div class="form-group w-10">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_age_group_between_13_17') }}:
                                        </x-label-section>
                                        <span aria-hidden="true"
                                              style="color: red; font-weight: bold;">{{ $btw13And17 }}</span>
                                    </div>

                                    <div class="form-group w-10">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_age_group_between_18_29') }}:
                                        </x-label-section>
                                        <span aria-hidden="true"
                                              style="color: red; font-weight: bold;">{{ $btw18And29 }}</span>
                                    </div>
                                    <div class="form-group w-10">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_age_group_between_30_39') }}:
                                        </x-label-section>
                                        <span aria-hidden="true"
                                              style="color: red; font-weight: bold;">{{ $btw30And39 }}</span>
                                    </div>
                                    <div class="form-group w-10">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_age_group_between_40_49') }}:
                                        </x-label-section>
                                        <span aria-hidden="true"
                                              style="color: red; font-weight: bold;">{{ $btw40And49 }}</span>
                                    </div>

                                    <div class="form-group w-10">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_age_group_between_50_59') }}:
                                        </x-label-section>
                                        <span aria-hidden="true"
                                              style="color: red; font-weight: bold;">{{ $btw50And59 }}</span>
                                    </div>
                                    <div class="form-group w-10">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_age_group_between_60_69') }}:
                                        </x-label-section>
                                        <span aria-hidden="true"
                                              style="color: red; font-weight: bold;">{{ $btw60And69 }}</span>
                                    </div>
                                    <div class="form-group w-10">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_age_group_between_70_79') }}:
                                        </x-label-section>
                                        <span aria-hidden="true"
                                              style="color: red; font-weight: bold;">{{ $btw70And79 }}</span>
                                    </div>

                                    <div class="form-group w-10">
                                        <x-label-section>
                                            {{ trans('poa.piat_matrix_report_divider_beneficiaries_age_group_greater_than_80') }}:
                                        </x-label-section>
                                        <span aria-hidden="true"
                                              style="color: red; font-weight: bold;">{{ $greaterThan80 }}</span>
                                    </div>
                                    <div class="form-group"></div>
                                </div>
                                <form method="post" enctype='multipart/form-data' wire:submit.prevent="getCsvFile()"
                                      id="upload-file">
                                    <div class="row">
                                        <div class="form-group col-12 pl-6 pt-4">
                                            <x-fileupload wire:model.defer="file" allowRevert allowRemove
                                                          allowFileSizeValidation maxFileSize="4mb"></x-fileupload>
                                            @error('file')
                                            <div class="alert alert-danger fade show" role="alert">
                                                {{ __('general.file_required') }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <x-form.modal.footer data-dismiss="modal"></x-form.modal.footer>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @can('approve-piat-report-poa')
                            @if($piatReportApproved==-1)
                                <button wire:click="approveReport" type="button" class="btn btn-success btn-sm m-1">
                                    <span aria-hidden="true">Aprobar</span>
                                </button>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
