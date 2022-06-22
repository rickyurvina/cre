<div wire:ignore.self class="modal fade in" id="poa-create-activity-modal" tabindex="-1" role="dialog"
     aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4">{{ __('general.poa_create_activity_title') }}</h5>
                <button wire:click="resetForm" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column w-80">
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

                        <div class="dropdown-menu" style="right: 0; left:0;">
                            @foreach($programs as $program)
                                <div class="dropdown-item" wire:click="$set('poaProgramId', '{{ $program['id'] }}')"
                                     style="white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                                    <span>{{ $program->planDetail->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div style="color: #fd3995;">
                        @error('poaProgramId') {{ $message }} @enderror
                    </div>
                </div>
                <div class="d-flex flex-column w-80 mt-3">
                    <label class="form-label required">{{ trans_choice('general.indicators', 1) }}</label>
                    <div class="btn-group">
                        <button class="btn btn-outline-secondary dropdown-toggle"
                                type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if($poaActivityIndicatorName != '')
                                {{ $poaActivityIndicatorName }}
                            @else
                                {{ trans('general.select') }}
                            @endif
                        </button>
                        <div class="dropdown-menu" style="right: 0; left:0;">
                            @foreach($programIndicators as $programIndicator)
                                <div class="dropdown-item"
                                     wire:click="$set('poaActivityIndicatorId', '{{ $programIndicator->indicator->id }}')"
                                     style="white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                                    <span style="white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                                        {{ $programIndicator->indicator->name }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div style="color: #fd3995;">
                        @error('poaActivityIndicatorId') {{ $message }} @enderror
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="form-group col-12" x-data="{}">
                        <label class="form-label required"
                               for="name-f">{{ trans_choice('general.activities', 1) }}</label>
                        <div class="input-group" x-on:click.away="$wire.resetTemplate()">
                            <input type="text" class="form-control col-2 @error('code') is-invalid @enderror"
                                   placeholder="{{ trans('general.code') }}" id="name-f"
                                   wire:model="code"
                                   wire:keydown.escape="resetTemplate"
                                   autocomplete="off">
                            <div class="invalid-feedback">
                                @error('code') {{ $message }} @enderror
                            </div>
                            <input type="text" class="form-control @error($poaActivityName) is-invalid @enderror"
                                   placeholder="{{ trans('general.name') }}" id="name-l"
                                   wire:model="poaActivityName"
                                   wire:keydown.escape="resetTemplate"
                                   autocomplete="off">
                            <div class="invalid-feedback">
                                @error('poaActivityName') {{ $message }} @enderror
                            </div>
                            @if(!empty($activityTemplates))
                                <div class="dropdown mb-2" style="will-change: top, left;top: 37px;left: 0;">
                                    @foreach($activityTemplates as $item)
                                        <div class="dropdown-item cursor-pointer"
                                             wire:click="selectTemplateActivity('{{ $item->id }}')">
                                            <span>{{ $item->code }} - {{ $item->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-12">
                        <label class="form-label required"
                               for="radio-group-1">{{ __('general.poa_activity_impact') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="impact1" name="impact" value="1" class="custom-control-input
                                                @error($poaActivityImpact) is-invalid @enderror"
                                               wire:model.defer="poaActivityImpact">
                                        <label class="custom-control-label"
                                               for="impact1">{{ __('general.poa_activity_category_low') }}</label>
                                    </div>
                                </div>
                                <div class="input-group-text">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="impact2" name="impact" value="2" class="custom-control-input
                                                @error($poaActivityImpact) is-invalid @enderror"
                                               wire:model.defer="poaActivityImpact">
                                        <label class="custom-control-label"
                                               for="impact2">{{ __('general.poa_activity_category_medium') }}</label>
                                    </div>
                                </div>
                                <div class="input-group-text">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="impact3" name="impact" value="3" class="custom-control-input
                                                @error($poaActivityImpact) is-invalid @enderror"
                                               wire:model.defer="poaActivityImpact">
                                        <label class="custom-control-label"
                                               for="impact3">{{ __('general.poa_activity_category_high') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-12">
                        <label class="form-label required"
                               for="radio-group-1">{{ __('general.poa_activity_complexity') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="complexity1" name="complexity" value="1" class="custom-control-input
                                                @error($poaActivityComplexity) is-invalid @enderror"
                                               wire:model.defer="poaActivityComplexity">
                                        <label class="custom-control-label"
                                               for="complexity1">{{ __('general.poa_activity_category_low') }}</label>
                                    </div>
                                </div>
                                <div class="input-group-text">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="complexity2" name="complexity" value="2" class="custom-control-input
                                                @error($poaActivityComplexity) is-invalid @enderror"
                                               wire:model.defer="poaActivityComplexity">
                                        <label class="custom-control-label"
                                               for="complexity2">{{ __('general.poa_activity_category_medium') }}</label>
                                    </div>
                                </div>
                                <div class="input-group-text">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="complexity3" name="complexity" value="3" class="custom-control-input
                                                @error($poaActivityComplexity) is-invalid @enderror"
                                               wire:model.defer="poaActivityComplexity">
                                        <label class="custom-control-label"
                                               for="complexity3">{{ __('general.poa_activity_category_high') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback order-last">
                                @error('poaActivityComplexity') {{ $message }} @enderror
                            </div>
                        </div>
                    </div>

                    <x-form.modal.select id="poaActivityUserInChargeId"
                                         label="{{ __('general.responsible') }}"
                                         class="form-group col-12 required">
                        <option value="">{{ __('general.form.select.field', ['field' => __('general.responsible')]) }}</option>
                        @foreach($users as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                        <div class="invalid-feedback order-last">
                            @error('poaActivityUserInChargeId') {{ $message}} @enderror
                        </div>
                    </x-form.modal.select>

                    <x-form.modal.text id="poaActivityCost" label="{{ __('general.poa_activity_cost') }}"
                                       class="form-group col-12"
                                       placeholder="{{ __('general.form.enter', ['field' => __('general.poa_activity_cost')]) }}">
                        <div class="invalid-feedback">
                            @error('poaActivityCost') {{ $message }} @enderror
                        </div>
                    </x-form.modal.text>

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

                        <div class="position-relative" x-data="{ open: false }">
                            <button class="btn btn-outline-secondary dropdown-toggle-custom" x-on:click="open = ! open"
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
            </div>
            <div class="modal-footer justify-content-center">
                <x-form.modal.footer wirecancelevent="resetForm" wiresaveevent="submitActivity"></x-form.modal.footer>
            </div>
        </div>
    </div>
</div>
