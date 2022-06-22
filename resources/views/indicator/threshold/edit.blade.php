@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.thresholds', 1)]))

@section('subheader-title')
    <i class="fal fa-plus text-primary"></i> {{ trans('general.title.edit', ['type' => trans_choice('general.thresholds', 1)]) }}
    - {{ $threshold->name }}
@endsection

@section('content')
    <div class="card">

        <form action="{{ route('thresholds.update', $threshold->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-12">
                        <div class="form-group col-3">
                            <label class="form-label" for="name">{{ trans('general.name') }}</label>
                            <div class="input-group bg-white shadow-inset-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-right-0">
                                        <i class="fal fa-sort-numeric-down-alt"></i>
                                    </span>
                                </div>
                                <input type="text" name="name" id="name" class="form-control pl-0 @error('name') is-invalid @enderror"
                                       value="{{ old('name', $threshold->name) }}" placeholder="{{ trans('general.form.enter', ['field' => trans('general.name')]) }}">
                                <div class="invalid-feedback">{{ $errors->first('name',':message') }} </div>
                            </div>
                        </div>
                    </div>

                    <div class="row col-lg-4 col-md-4">
                        <div class="col-sm-12 col-lg-12">
                            <table id="dt-basic-example" class="table table-bordered table-striped w-100 dataTable no-footer dtr-inline" role="grid"
                                   aria-describedby="dt-basic-example_info">
                                <thead>
                                <tr>
                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="3">
                                        <h3>{{trans('threshold.form.upward')}}</h3></th>
                                </tr>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1">
                                        <span class="form-label badge badge-primary badge-pill"></span></th>
                                    <th class="sorting" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1">{{ trans('general.min') }}</th>
                                    <th class="sorting" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1">{{ trans('general.max') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr id="1" role="row" class="add">
                                    <td class="dtr-control">
                                        <span class="form-label badge badge-danger badge-pill">{{trans('threshold.form.unacceptable')}}</span>
                                    </td>
                                    <td class="dtr-control">
                                        <label>{{trans('indicators.indicator.less_or_equal_to')}}</label>
                                    </td>
                                    <td class="dtr-control">
                                        <input type="number" value="{{$threshold->properties[0][3]}}" readonly name="maxAD" id="maxAD" class="border-0">
                                    </td>
                                </tr>
                                <tr id="1" role="row" class="add">
                                    <td class="dtr-control">
                                        <span class="form-label badge badge-warning badge-pill">{{trans('threshold.form.alert')}}</span>
                                    </td>
                                    <td class="dtr-control">
                                        <input type="number" name="minAW" min="0" pattern="^[0-9]+" id="minAW" onkeyup="PasarValor();"
                                               class="form-control border-left-0 bg-transparent pl-0 @error('minAW') is-invalid @enderror"
                                               placeholder="{{ trans('general.form.enter', ['field' => trans('general.min')]) }}"
                                               value="{{$threshold->properties[1][3]}}">
                                    </td>
                                    <td class="dtr-control">
                                        <input type="number" name="maxAW" min="0" pattern="^[0-9]+" id="maxAW" onkeyup="PasarValor();"
                                               class="form-control border-left-0 bg-transparent pl-0 @error('maxAW') is-invalid @enderror"
                                               placeholder="{{ trans('general.form.enter', ['field' => trans('general.max')]) }}"
                                               value="{{$threshold->properties[2][3]}}">
                                    </td>
                                </tr>
                                <tr id="1" role="row" class="add">
                                    <td class="dtr-control">
                                        <span class="badge badge-success badge-pill">{{trans('threshold.form.acceptable')}}</span>
                                    </td>
                                    <td class="dtr-control">
                                        <label>{{trans('indicators.indicator.greater_or_equal_to')}}</label>
                                    </td>
                                    <td class="dtr-control">
                                        <input type="number" id="minAS" name="minAS" readonly class="border-0" value="{{$threshold->properties[3][3]}}">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row col-lg-4 col-md-4">
                        <div class="col-sm-12">
                            <table id="dt-basic-example" class="table table-bordered table-striped w-100 dataTable no-footer dtr-inline" role="grid"
                                   aria-describedby="dt-basic-example_info">
                                <thead>
                                <tr>
                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="3">
                                        <h3>{{trans('threshold.form.Falling')}}</h3></th>
                                </tr>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1">
                                        <span class="form-label badge badge-primary badge-pill"></span></th>
                                    <th class="sorting" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1">{{ trans('general.min') }}</th>
                                    <th class="sorting" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1">{{ trans('general.max') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr id="1" role="row" class="add">
                                    <td class="dtr-control">
                                        <span class="form-label badge badge-danger badge-pill">{{trans('threshold.form.unacceptable')}}</span>
                                    </td>
                                    <td class="dtr-control">
                                        <label>{{trans('indicators.indicator.less_or_equal_to')}}</label>
                                    </td>
                                    <td class="dtr-control">
                                        <input type="number" readonly name="maxDD" id="maxDD" class="border-0" value="{{$threshold->properties[4][3]}}">
                                    </td>
                                </tr>
                                <tr id="1" role="row" class="add">
                                    <td class="dtr-control">
                                        <span class="form-label badge badge-warning badge-pill">{{trans('threshold.form.alert')}}</span>
                                    </td>
                                    <td class="dtr-control">
                                        <input type="number" required name="minDW" min="0" pattern="^[0-9]+" id="minDW" onkeyup="PasarValor();"
                                               class="form-control border-left-0 bg-transparent pl-0 @error('minDW') is-invalid @enderror"
                                               placeholder="{{ trans('general.form.enter', ['field' => trans('general.min')]) }}" value="{{$threshold->properties[5][3]}}">
                                    </td>
                                    <td class="dtr-control">
                                        <input type="number" required name="maxDW" min="0" pattern="^[0-9]+" id="maxDW" onkeyup="PasarValor();"
                                               class="form-control border-left-0 bg-transparent pl-0 @error('maxDW') is-invalid @enderror"
                                               placeholder="{{ trans('general.form.enter', ['field' => trans('general.max')]) }}" value="{{$threshold->properties[6][3]}}">
                                    </td>
                                </tr>

                                <tr id="1" role="row" class="add">
                                    <td class="dtr-control">
                                        <span class="badge badge-success badge-pill">{{trans('threshold.form.acceptable')}}</span>
                                    </td>
                                    <td class="dtr-control">
                                        <label>{{trans('indicators.indicator.greater_or_equal_to')}}</label>
                                    </td>
                                    <td class="dtr-control">
                                        <input type="number" readonly name="minDS" id="minDS" class="border-0" value="{{$threshold->properties[7][3]}}">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row col-lg-4 col-md-4">
                        <div class="col-sm-12">
                            <table id="dt-basic-example" class="table table-bordered table-striped w-100 dataTable no-footer dtr-inline" role="grid"
                                   aria-describedby="dt-basic-example_info">
                                <thead>
                                <tr>
                                    <th class="sorting text-center" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="3">
                                        <h3>{{trans('threshold.form.tolerance_band')}}</h3></th>
                                </tr>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1">
                                        <span class="form-label badge badge-primary badge-pill"></span></th>
                                    <th class="sorting" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1">{{ trans('general.min') }}</th>
                                    <th class="sorting" tabindex="0" aria-controls="dt-basic-example" rowspan="1" colspan="1">{{ trans('general.max') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr id="1" role="row" class="add">
                                    <td class="dtr-control">
                                        <span class="form-label badge badge-danger badge-pill">{{trans('threshold.form.unacceptable')}}</span>
                                    </td>
                                    <td class="dtr-control">
                                        <label>{{trans('indicators.indicator.less_or_equal_to')}}</label>
                                    </td>
                                    <td class="dtr-control">
                                        <input type="number" readonly name="maxTD" id="maxTD" class="border-0" value="{{$threshold->properties[8][3]}}">
                                    </td>
                                </tr>
                                <tr id="1" role="row" class="add">
                                    <td class="dtr-control">
                                        <span class="form-label badge badge-warning badge-pill">{{trans('threshold.form.alert')}}</span>
                                    </td>
                                    <td class="dtr-control">
                                        <input type="number" required name="minTW" min="0" pattern="^[0-9]+" id="minTW" onkeyup="PasarValor();"
                                               class="form-control border-left-0 bg-transparent pl-0 @error('minTW') is-invalid @enderror"
                                               placeholder="{{ trans('general.form.enter', ['field' => trans('general.min')]) }}" value="{{$threshold->properties[9][3]}}">
                                    </td>
                                    <td class="dtr-control">
                                        <input type="number" required name="maxTW" min="0" pattern="^[0-9]+" id="maxTW" onkeyup="PasarValor();"
                                               class="form-control border-left-0 bg-transparent pl-0 @error('maxTW') is-invalid @enderror"
                                               placeholder="{{ trans('general.form.enter', ['field' => trans('general.max')]) }}" value="{{$threshold->properties[10][3]}}">
                                    </td>
                                </tr>
                                <tr id="1" role="row" class="add">
                                    <td class="dtr-control">
                                        <span class="badge badge-success badge-pill">{{trans('threshold.form.acceptable')}}</span>
                                    </td>
                                    <td class="dtr-control">
                                        <label>{{trans('indicators.indicator.greater_or_equal_to')}}</label>
                                    </td>
                                    <td class="dtr-control">
                                        <input type="number" readonly name="minTS" id="minTS" class="border-0" value="{{$threshold->properties[11][3]}}">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer text-center">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mr-1">
                            <i class="fas fa-times"></i> {{ trans('general.cancel') }}
                        </a>
                        <button class="btn btn-success">
                            <i class="fas fa-save pr-2"></i> {{ trans('general.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        function PasarValor() {
            //maximo danger ascending= minimo ascending warning
            document.getElementById("maxAD").value = document.getElementById("minAW").value;
            //minimo ascending success = maximo ascending warning
            document.getElementById("minAS").value = document.getElementById("maxAW").value;

            //maximo danger descending= minimo descemding warning
            document.getElementById("maxDD").value = document.getElementById("minDW").value;
            //minimo descending success = maximo descending warning
            document.getElementById("minDS").value = document.getElementById("maxDW").value;

            //maximo danger tolerance= minimo tolerance warning
            document.getElementById("maxTD").value = document.getElementById("minTW").value;
            //minimo tolerance success = maximo toelrance warning
            document.getElementById("minTS").value = document.getElementById("maxTW").value;

        }
    </script>
@endsection