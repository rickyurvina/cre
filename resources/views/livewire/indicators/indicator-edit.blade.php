<div wire:ignore.self class="modal fade" id="indicator-edit-modal" tabindex="-1" role="dialog" aria-hidden="true"
     data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-right modal-lg">
        <div class="modal-content">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4"><i class="fas fa-plus-circle text-success"></i> {{ trans('indicators.indicator.edit_indicator') }}</h5>
                    <button type="button" wire:click="resetForm" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <fieldset {{ $progress > 0 ? 'disabled':''}}>
                            <input type="hidden" name="id" id="id" wire:model="indicatorIdEdit">
                            <div class="card-body">
                                <div class="row">
                                    {{--                                tipo--}}
                                    <div class="form-group col-lg-4 col-sm-12 required">
                                        <label class="form-label" for="type">{{ trans('general.type') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <h3>{{ trans('indicators.indicator.'.$type) }}</h3>
                                        </div>
                                    </div>
                                    {{--                                categoria--}}
                                    <div class="form-group col-lg-4 required">
                                        <label class="form-label" for="category">{{ trans('general.category') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                    <i class="fal fa-user-circle"></i>
                                                </span>
                                            </div>
                                            <select class="custom-select @error('category') is-invalid @enderror" id="category" name="category" wire:model.defer="category">
                                                <option value="">-{{ trans('general.category') }}-</option>
                                                <option value="Táctico">Táctico</option>
                                                <option value="Operativo">Operativo</option>
                                            </select>
                                        </div>
                                        <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('category',':message') }} </div>
                                    </div>
                                    {{--                                codigo--}}
                                    <div class="form-group col-lg-4 required">
                                        <label class="form-label" for="code">{{ trans('general.code') }} {{ trans_choice('general.indicators', 1) }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                <span class="input-group-text bg-transparent border-right-0">
                                    <i class="fal fa-barcode"></i>
                                </span>
                                            </div>
                                            <input type="text" name="code" id="code" class="form-control border-left-0 bg-transparent pl-0 @error('code') is-invalid @enderror"
                                                   placeholder="{{ trans('general.form.enter', ['field' => trans_choice('general.code', 1)]) }}" wire:model.defer="code">
                                            <div class="invalid-feedback">{{ $errors->first('code',':message') }} </div>
                                        </div>
                                    </div>
                                    {{--                                nombre--}}
                                    <div class="form-group col-lg-4 required">
                                        <label class="form-label" for="name">{{ trans('general.name') }} {{ trans_choice('general.indicators', 1) }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                <span class="input-group-text bg-transparent border-right-0">
                                    <i class="fal fa-address-card"></i>
                                </span>
                                            </div>
                                            <input type="text" name="name" id="name"
                                                   class="form-control border-left-0 bg-transparent pl-0 @error('name') is-invalid @enderror"
                                                   placeholder="{{ trans('general.form.enter', ['field' => trans_choice('general.indicators', 1)]) }}" wire:model.defer="name">
                                            <div class="invalid-feedback">{{ $errors->first('name',':message') }} </div>
                                        </div>
                                    </div>
                                    {{--                                responsable--}}
                                    <div class="form-group col-lg-4 required">
                                        <label class="form-label" for="responsible">{{ trans('general.responsible') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                    <i class="fal fa-user-circle"></i>
                                                </span>
                                            </div>
                                            <select class="custom-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" wire:model.defer="user_id">
                                                <option value=""> {{ trans('general.responsible') }}</option>
                                                @if(isset($users))
                                                    @foreach($users as $user)
                                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('user_id',':message') }} </div>
                                    </div>
                                    {{--                                resultados--}}
                                    <div class="form-group col-lg-4 required">
                                        <label class="form-label" for="results">{{ trans('indicators.indicator.results') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                   <i class="fal fa-align-justify"></i>
                                                </span>
                                            </div>
                                            <textarea class="form-control border-left-0 bg-transparent pl-0 @error('results') is-invalid @enderror"
                                                      id="results" name="results" rows="1"
                                                      placeholder="{{ trans('general.form.enter', ['field' => trans('indicators.indicator.results')]) }}"
                                                      wire:model.defer="results"></textarea>
                                            <div class="invalid-feedback">{{ $errors->first('results',':message') }} </div>
                                        </div>
                                    </div>
                                    {{--                                unidad de media--}}
                                    <div class="form-group col-lg-4 required">
                                        <label class="form-label" for="indicator_units_id">{{ trans('indicators.indicator.unit_of_measurement') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                <span class="input-group-text bg-transparent border-right-0">
                                    <i class="fal fa-balance-scale-left"></i>
                                </span>
                                            </div>
                                            <select name="indicator_units_id" class="custom-select @error('indicator_units_id') is-invalid @enderror" id="indicator_units_id"
                                                    wire:model.defer="indicator_units_id">
                                                <option value=""> {{ trans('indicators.indicator.unit_of_measurement') }}</option>
                                                @if(isset($indicatorUnits))
                                                    @foreach($indicatorUnits as $unit)
                                                        <option value="{{$unit->id}}">{{$unit->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('indicator_units_id',':message') }} </div>
                                    </div>
                                    {{--                                tipo de agregacion--}}
                                    <div class="form-group col-lg-4 required">
                                        <label class="form-label" for="type_of_aggregation">{{ trans('indicators.indicator.type_of_aggregation') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                    <i class="fal fa-wave-sine"></i>
                                                </span>
                                            </div>
                                            <select class="custom-select" id="type_of_aggregation" name="type_of_aggregation" wire:model.defer="type_of_aggregation">
                                                <option value="">{{ trans('indicators.indicator.type_of_aggregation') }}</option>
                                                <option value="sum">{{trans('indicators.indicator.TYPE_AGGREGATION_sum') }}</option>
                                                <option value="weighted">{{trans('indicators.indicator.TYPE_AGGREGATION_weighted') }}</option>
                                                <option value="weighted_sum">{{trans('indicators.indicator.TYPE_AGGREGATION_weighted_sum') }}</option>
                                            </select>
                                        </div>
                                        <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('type_of_aggregation',':message') }} </div>
                                    </div>
                                    @if(isset($this->indicator))
                                        @if(count($this->indicator->indicatorParents)>0)
                                            <div class="form-group  required col-lg-4">
                                                <label class="form-label" for="abbreviation">{{ trans('general.type') }}</label>
                                                <div class="input-group bg-white shadow-inset-2">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0">
                                                       <i class="fal fa-arrows-v"></i>
                                                    </span>
                                                    </div>
                                                    <select class="custom-select" wire:model="selectedType" id="threshold_type" name="threshold_type">
                                                        <option value="">{{trans('general.choose')}}{{ trans('general.type') }}</option>
                                                        <option value="Tolerance">{{trans('indicators.indicator.TYPE_tolerance') }}</option>
                                                        <option value="Ascending">{{trans('indicators.indicator.TYPE_ascending') }}</option>
                                                        <option value="Descending">{{trans('indicators.indicator.TYPE_descending') }}</option>
                                                    </select>
                                                </div>
                                                <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('threshold_type',':message') }} </div>
                                            </div>
                                            <div class="form-group col-lg-4 required">
                                                <label class="form-label" for="frequency">{{ trans('indicators.indicator.frequency_update') }}</label>
                                                <div class="input-group bg-white shadow-inset-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-transparent border-right-0">
                                                            <i class="fal fa-wave-sine"></i>
                                                        </span>
                                                    </div>
                                                    <select class="custom-select" id="frequency" name="frequency" wire:model="frequency">
                                                        <option value=""> {{ trans('indicators.indicator.frequency_update') }}</option>
                                                        <option value="12">{{trans('indicators.indicator.FREQUENCY_monthly') }}</option>
                                                        <option value="4">{{trans('indicators.indicator.FREQUENCY_quarterly') }}</option>
                                                        <option value="3">{{trans('indicators.indicator.FREQUENCY_four-monthly') }}</option>
                                                        <option value="2">{{trans('indicators.indicator.FREQUENCY_biannual') }}</option>
                                                        <option value="1">{{trans('indicators.indicator.FREQUENCY_annual') }}</option>
                                                    </select>
                                                </div>
                                                <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('frequency',':message') }} </div>
                                            </div>
                                            <div class="form-group col-lg-4 required">
                                                <label class="form-label" for="start_date">{{ trans('general.start_date') }}</label>
                                                <div class="input-group bg-white shadow-inset-2">
                                                    <input class="form-control" id="start_date" type="month" name="start_date" wire:model.defer="start_date">
                                                </div>
                                                <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('start_date',':message') }} </div>
                                            </div>
                                            <div class="form-group col-lg-4 required">
                                                <label class="form-label" for="end_date">{{ trans('general.end_date') }}</label>
                                                <div class="input-group bg-white shadow-inset-2">
                                                    <input class="form-control" id="end_date" type="month" name="end_date" wire:model.defer="end_date">
                                                </div>
                                                <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('end_date',':message') }} </div>
                                            </div>
                                            {{--                                            <x-form.inputs.select2 id="indicators" label="{{ trans_choice('general.indicators', 0) }}" class="col-6 required" multiple="true"--}}
                                            {{--                                                                   wire:model="indicatorsSelectedEdit">--}}
                                            {{--                                                @foreach($indicatorsEdit as $key => $name)--}}
                                            {{--                                                    <option value="{{ $key}}" {{ in_array($key, $this->arr)? 'selected':'' }}>{{ $name }}</option>--}}
                                            {{--                                                @endforeach--}}
                                            {{--                                            </x-form.inputs.select2>--}}

                                            <div class="d-flex w-100 m-2">
                                                <div class="form-group col-12 required" wire:ignore.self>
                                                    <label class="form-label" for="select-indicators">{{ trans_choice('general.indicators', 0) }}</label>
                                                    <select class="form-control select2-hidden-accessible" multiple="" name="select-indicators" id="select-indicators">
                                                        @foreach($indicatorsEdit as $key => $name)
                                                            <option value="{{ $key}}" {{ in_array($key, $this->arr)? 'selected':'' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <hr>
                                            <dl class="row">
                                                <dt class="col-sm-10"><h5><strong>{{trans('indicators.indicator.total_goal_value') }}</strong></h5></dt>
                                                <dd class="col-sm-2">
                                                    {{ $goalValueTotalEdit }}
                                                </dd>
                                            </dl>

                                        @else
                                            {{--                                linea base--}}
                                            <div class="form-group col-lg-4">
                                                <label class="form-label" for="base_line">{{ trans('indicators.indicator.base_line') }}</label>
                                                <div class="input-group bg-white shadow-inset-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-transparent border-right-0">
                                                            <i class="fal fa-analytics"></i>
                                                        </span>
                                                    </div>
                                                    <input type="number" name="base_line" id="base_line" min="0"
                                                           class="form-control border-left-0 bg-transparent pl-0 @error('base_line') is-invalid @enderror"
                                                           placeholder="{{ trans('general.form.enter', ['field' => trans('indicators.indicator.base_line')]) }}"
                                                           wire:model.defer="base_line">
                                                    <div class="invalid-feedback">{{ $errors->first('base_line',':message') }} </div>
                                                </div>
                                            </div>
                                            {{--                                ano linea base--}}
                                            <div class="form-group col-lg-4">
                                                <label class="form-label" for="baseline_year">{{ trans('indicators.indicator.baseline_year') }}</label>
                                                <div class="input-group bg-white shadow-inset-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-transparent border-right-0">
                                                            <i class="fal fa-calendar-check"></i>
                                                        </span>
                                                    </div>
                                                    <input type="number" name="baseline_year" id="baseline_year" min="0"
                                                           class="form-control border-left-0 bg-transparent pl-0 @error('baseline_year') is-invalid @enderror"
                                                           placeholder="{{ trans('general.form.enter', ['field' => trans('indicators.indicator.base_line')]) }}"
                                                           wire:model.defer="baseline_year">
                                                </div>
                                                <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('baseline_year',':message') }} </div>
                                            </div>
                                            {{--                                fuente--}}
                                            <div class="form-group col-lg-4 required">
                                                <label class="form-label" for="source">{{ trans('general.source') }}</label>
                                                <div class="input-group bg-white shadow-inset-2">
                                                    <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                    <i class="fal fa-address-card"></i>
                                                </span>
                                                    </div>
                                                    <select class="custom-select @error('indicator_sources_id') is-invalid @enderror" id="indicator_sources_id"
                                                            name="indicator_sources_id"
                                                            wire:model.defer="indicator_sources_id">
                                                        <option value="" selected> {{ trans('general.source') }}</option>
                                                        @if(isset($indicatorSource))
                                                            @foreach($indicatorSource as $key)
                                                                <option value="{{$key->id}}">{{$key->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('indicator_sources_id',':message') }} </div>
                                            </div>
                                            @if(isset($thresholds))
                                                <div class="form-group  required col-lg-4">
                                                    <label class="form-label"
                                                           for="thresholds_id">{{trans('indicators.indicator.threshold') }}</label>
                                                    <div class="input-group bg-white shadow-inset-2">
                                                        <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                   <i class="fal fa-ballot-check"></i>
                                                </span>
                                                        </div>
                                                        <select class="custom-select" wire:model.defer="selectedThreshold" id="thresholds_id"
                                                                name="thresholds_id">
                                                            <option value=""> {{ trans('indicators.indicator.choose_threshold') }}</option>
                                                            @foreach($thresholds as $threshold)
                                                                <option value="{{$threshold->id}}">{{$threshold->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('thresholds_id',':message') }} </div>
                                                </div>

                                                <div class="form-group  required col-lg-4">
                                                    <label class="form-label" for="abbreviation">{{ trans('general.type') }}</label>
                                                    <div class="input-group bg-white shadow-inset-2">
                                                        <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                   <i class="fal fa-arrows-v"></i>
                                                </span>
                                                        </div>
                                                        <select class="custom-select" wire:model="selectedType" id="threshold_type"
                                                                name="threshold_type">
                                                            <option value="">{{trans('general.choose')}}{{ trans('general.type') }}</option>
                                                            <option value="Tolerance">{{trans('indicators.indicator.TYPE_tolerance') }}</option>
                                                            <option value="Ascending">{{trans('indicators.indicator.TYPE_ascending') }}</option>
                                                            <option value="Descending">{{trans('indicators.indicator.TYPE_descending') }}</option>
                                                        </select>
                                                    </div>
                                                    <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('threshold_type',':message') }} </div>

                                                </div>

                                                @if (!is_null($selectedType))
                                                    @if($selectedType=='Ascending')
                                                        <div class="required col-lg-12">
                                                            <table id="dt-basic-example"
                                                                   class="table table-bordered table-striped w-100 dataTable no-footer dtr-inline"
                                                                   role="grid"
                                                                   aria-describedby="dt-basic-example_info" style="width: 1760px;">
                                                                <thead>
                                                                <tr>
                                                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="5"
                                                                        style="width: 111px">
                                                                        <h3>{{trans('indicators.indicator.threshold_details')}}</h3></th>
                                                                </tr>
                                                                <tr role="row">
                                                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1"
                                                                        style="width: 111px">
                                                                        <span class="">{{trans('indicators.indicator.state')}}</span></th>
                                                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1"
                                                                        style="width: 111px">{{trans('indicators.indicator.goal_description')}}</th>
                                                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1"
                                                                        style="width: 111px">{{trans('indicators.indicator.minimum')}}</th>
                                                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1"
                                                                        style="width: 111px">{{trans('indicators.indicator.maximum')}}</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                <tr id="1" role="row" class="add">
                                                                    <td class="dtr-control text-center">
                                                                        <span class="d-inline-block rounded-circle mr-2 bg-warning bg-warning"
                                                                              style="width: 15px; height: 15px"></span>
                                                                    </td>
                                                                    <td class="dtr-control text-center">
                                                                        <span class="">{{trans('indicators.indicator.between')}}</span>
                                                                    </td>
                                                                    <td class="dtr-control text-center">
                                                                        <input type="number" name="minAW" min="0" pattern="^[0-9]+" id="minAW"
                                                                               class=" form-control border-left-0 bg-transparent pl-0 text-center"
                                                                               wire:model.defer="minAW"
                                                                               placeholder="{{ trans('general.form.enter', ['field' => trans('general.min')]) }}">
                                                                    </td>
                                                                    <td class="dtr-control text-center">
                                                                        <input type="number" name="maxAW" min="0" pattern="^[0-9]+" id="maxAW"
                                                                               class=" form-control border-left-0 bg-transparent pl-0 text-center"
                                                                               wire:model.defer="maxAW"
                                                                               placeholder="{{ trans('general.form.enter', ['field' => trans('general.max')]) }}">
                                                                    </td>
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    @endif
                                                    @if($selectedType=='Descending')
                                                        <div class="col-lg-12 required">
                                                            <table id="dt-basic-example"
                                                                   class="table table-bordered table-striped w-100 dataTable no-footer dtr-inline"
                                                                   role="grid"
                                                                   aria-describedby="dt-basic-example_info" style="width: 1760px;">
                                                                <thead>
                                                                <tr>
                                                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="5"
                                                                        style="width: 111px">
                                                                        <h3>{{trans('indicators.indicator.threshold_details')}}</h3></th>
                                                                </tr>
                                                                <tr role="row">
                                                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1"
                                                                        style="width: 111px">
                                                                        <span class="">{{trans('indicators.indicator.state')}}</span></th>
                                                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1"
                                                                        style="width: 111px">{{trans('indicators.indicator.goal_description')}}</th>
                                                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1"
                                                                        style="width: 111px">{{trans('indicators.indicator.minimum')}}</th>
                                                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1"
                                                                        style="width: 111px">{{trans('indicators.indicator.maximum')}}</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                <tr id="1" role="row" class="add">
                                                                    <td class="dtr-control text-center">
                                                                        <span class="d-inline-block rounded-circle mr-2 bg-warning bg-warning"
                                                                              style="width: 15px; height: 15px"></span>
                                                                    </td>
                                                                    <td class="dtr-control text-center">
                                                                        <span class="">{{trans('indicators.indicator.between')}}</span>
                                                                    </td>
                                                                    <td class="dtr-control text-center">
                                                                        <input type="number" name="minDW" min="0" pattern="^[0-9]+" id="minDW"
                                                                               class="form-control border-left-0 bg-transparent pl-0 text-center"
                                                                               wire:model.defer="minDW"
                                                                               placeholder="{{ trans('general.form.enter', ['field' => trans('general.min')]) }}">
                                                                    </td>
                                                                    <td class="dtr-control text-center">
                                                                        <input type="number" name="maxDW" min="0" pattern="^[0-9]+" id="maxDW"
                                                                               class="form-control border-left-0 bg-transparent pl-0 text-center"
                                                                               wire:model.defer="maxDW"
                                                                               placeholder="{{ trans('general.form.enter', ['field' => trans('general.max')]) }}">
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    @endif
                                                    @if($selectedType=='Tolerance')
                                                        <div class="col-lg-12 required">
                                                            <table id="dt-basic-example"
                                                                   class="table table-bordered  table-striped w-100 dataTable no-footer dtr-inline"
                                                                   role="grid"
                                                                   aria-describedby="dt-basic-example_info" style="width: 1760px;">
                                                                <thead>
                                                                <tr>
                                                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="5"
                                                                        style="width: 111px">
                                                                        <h3>{{trans('indicators.indicator.threshold_details')}}</h3></th>
                                                                </tr>
                                                                <tr role="row">
                                                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1"
                                                                        style="width: 111px">
                                                                        <span class="">{{trans('indicators.indicator.state')}}</span></th>
                                                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1"
                                                                        style="width: 111px">{{trans('indicators.indicator.goal_description')}}</th>
                                                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1"
                                                                        style="width: 111px">{{trans('indicators.indicator.minimum')}}</th>
                                                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1"
                                                                        style="width: 111px">{{trans('indicators.indicator.maximum')}}</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr id="1" role="row" class="add">
                                                                    <td class="dtr-control text-center">
                                                                        <span class="d-inline-block rounded-circle mr-2 bg-warning bg-warning"
                                                                              style="width: 15px; height: 15px"></span>
                                                                    </td>
                                                                    <td class="dtr-control text-center">
                                                                        <span class="">{{trans('indicators.indicator.between')}}</span>
                                                                    </td>
                                                                    <td class="dtr-control text-center">
                                                                        <input type="number" name="minTW" min="0" pattern="^[0-9]+" id="minTW"
                                                                               class=" form-control border-left-0 bg-transparent pl-0 text-center text-center"
                                                                               wire:model.defer="minTW"
                                                                               placeholder="{{ trans('general.form.enter', ['field' => trans('general.min')]) }}">
                                                                    </td>
                                                                    <td class="dtr-control text-center">
                                                                        <input type="number" name="maxTW" min="0" pattern="^[0-9]+" id="maxTW"
                                                                               class=" form-control border-left-0 bg-transparent pl-0 text-center"
                                                                               wire:model.defer="maxTW"
                                                                               placeholder="{{ trans('general.form.enter', ['field' => trans('general.max')]) }}">
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @endif
                                                @endif
                                                <hr>

                                                <div class="form-group col-lg-4 required">
                                                    <label class="form-label" for="start_date">{{ trans('general.start_date') }}</label>
                                                    <div class="input-group bg-white shadow-inset-2">
                                                        <input class="form-control" id="start_date" type="month" name="start_date" wire:model.lazy="start_date">
                                                    </div>
                                                    <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('start_date',':message') }} </div>
                                                </div>
                                                <div class="form-group col-lg-4 required">
                                                    <label class="form-label" for="end_date">{{ trans('general.end_date') }}</label>
                                                    <div class="input-group bg-white shadow-inset-2">
                                                        <input class="form-control" id="end_date" type="month" name="end_date" wire:model.lazy="end_date">
                                                    </div>
                                                    <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('end_date',':message') }} </div>
                                                </div>
                                                <div class="form-group col-lg-4 required">
                                                    <label class="form-label" for="frequency">{{ trans('indicators.indicator.frequency_update') }}</label>
                                                    <div class="input-group bg-white shadow-inset-2">
                                                        <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0">
                                                        <i class="fal fa-wave-sine"></i>
                                                    </span>
                                                        </div>
                                                        <select class="custom-select" id="frequency" name="frequency" wire:model="frequency">
                                                            <option value=""> {{ trans('indicators.indicator.frequency_update') }}</option>
                                                            <option value="12">{{trans('indicators.indicator.FREQUENCY_monthly') }}</option>
                                                            <option value="4">{{trans('indicators.indicator.FREQUENCY_quarterly') }}</option>
                                                            <option value="3">{{trans('indicators.indicator.FREQUENCY_four-monthly') }}</option>
                                                            <option value="2">{{trans('indicators.indicator.FREQUENCY_biannual') }}</option>
                                                            <option value="1">{{trans('indicators.indicator.FREQUENCY_annual') }}</option>
                                                        </select>
                                                    </div>
                                                    <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('frequency',':message') }} </div>
                                                </div>
                                                <div class="row fs-b fw-300 mr-2">
                                                    <div class="col">
                                                        <div class="custom-control custom-checkbox custom-checkbox-circle">
                                                            <input type="checkbox" name="self_managed" class="custom-control-input" id="self_managed"
                                                                   wire:model="self_managed">
                                                            <label class="custom-control-label" for="self_managed">{{trans('indicators.indicator.self_managed')}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($self_managed==true)
                                                    <div class="form-group col-lg-12 required">
                                                        <div class="input-group d-flex flex-row">
                                                            @switch($selectedType)
                                                                @case('Tolerance')
                                                                @for($i =0; $i < count($this->periods); $i++)
                                                                    <div class="p-2">
                                                                        <x-form.inputs.text type="number" id="min[]" name="min[]" label="{{$data[$i]['frequency']}}"
                                                                                            class="mb-0"
                                                                                            value="{{$min[$i+1]??0}}" wire:model.defer="min.{{$i}}"/>
                                                                        <x-form.inputs.text type="number" id="max[]" name="max[]" value="{{$max[$i+1]??0}}"
                                                                                            wire:model="max.{{$i}}"/>
                                                                    </div>
                                                                @endfor

                                                                @break
                                                                @case('Ascending'||'Descending')
                                                                @for($i =0; $i < count($this->periods); $i++)
                                                                    <div class="col-2 mb-1">
                                                                        <x-form.inputs.text type="number" label="{{$data[$i]['frequency']}}" id="freq-{{ $i+1 }}"
                                                                                            wire:model.defer="freq.{{ $i+1  }}" value="{{$freq[$i+1]??0}}"/>
                                                                    </div>
                                                                @endfor
                                                                @break
                                                                @default
                                                            @endswitch
                                                        </div>
                                                    </div>
                                                @endif

                                            @endif
                                        @endif
                                    @endif
                                </div>
                                {{--                            umbrales--}}

                            </div>
                            <div class="row fs-b fw-300">
                                <div class="col">
                                    <div class="custom-control custom-checkbox custom-checkbox-circle">
                                        <input type="checkbox" class="custom-control-input" id="pdot-obj-edit"
                                               wire:model.defer="state" value="{{$state}}">
                                        <label class="custom-control-label" for="pdot-obj-edit">{{trans('indicators.indicator.goals_closing')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group  required col-lg-4">
                                <div class="input-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="national_edit" wire:model.defer="national">
                                        <label class="custom-control-label" for="national_edit">{{ trans('indicators.indicator.indicator_national') }}</label>
                                        <div class="invalid-feedback">{{ $errors->first('national',':message') }} </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <div class="row">
                                    <div class="col-12">
                                        <a wire:click="resetForm" href="javascript:void(0);" class="btn btn-outline-secondary mr-1" data-dismiss="modal">
                                            <i class="fas fa-times"></i> {{ trans('general.cancel') }}
                                        </a>
                                        <button class="btn btn-success" wire:click="editIndicator">
                                            <i class="fas fa-save pr-2"></i> {{ trans('general.save') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@push('page_script')
    <script>
        Livewire.on('toggleIndicatorEditModal', () => $('#indicator-edit-modal').modal('toggle'));
    </script>
    <script>
        $(document).ready(function () {
            $('#select-indicators').select2({
                dropdownParent: $("#indicator-edit-modal"),
                placeholder: "{{ trans('general.select') }}"
            }).on('change', function (e) {
            @this.set('indicatorsSelectedEdit', $(this).val());
            });

        });
        window.addEventListener('loadIndicators', event => {
            $('#select-indicators').select2({
                dropdownParent: $("#indicator-edit-modal"),
                placeholder: "{{ trans('general.select') }}"
            }).on('change', function (e) {
            @this.set('indicatorsSelectedEdit', $(this).val());

            });
        });
    </script>
@endpush