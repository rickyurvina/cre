@extends('layouts.admin')

@section('title', trans('general.title.show', ['type' => trans_choice('general.thresholds', 1)]))

@section('subheader-title')
    <i class="fal fa-plus text-primary"></i> {{ trans('general.title.show', ['type' => trans_choice('general.thresholds', 1)]) }}
    - {{ $threshold->name }}
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
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
                                    <span>{{$threshold->properties[0][3]}}</span>

                                </td>
                            </tr>
                            <tr id="1" role="row" class="add">
                                <td class="dtr-control">
                                    <span class="form-label badge badge-warning badge-pill">{{trans('threshold.form.alert')}}</span>
                                </td>
                                <td class="dtr-control">
                                    <span>{{$threshold->properties[1][3]}}</span>
                                </td>
                                <td class="dtr-control">
                                    <span>{{$threshold->properties[2][3]}}</span>
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
                                    <span>{{$threshold->properties[3][3]}}</span>
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
                                    <span>{{$threshold->properties[4][3]}}</span>
                                </td>
                            </tr>
                            <tr id="1" role="row" class="add">
                                <td class="dtr-control">
                                    <span class="form-label badge badge-warning badge-pill">{{trans('threshold.form.alert')}}</span>
                                </td>
                                <td class="dtr-control">
                                    <span>{{$threshold->properties[5][3]}}</span>
                                </td>
                                <td class="dtr-control">
                                    <span>{{$threshold->properties[6][3]}}</span>
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
                                    <span>{{$threshold->properties[7][3]}}</span>
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
                                    <span>{{$threshold->properties[8][3]}}</span>
                                </td>
                            </tr>
                            <tr id="1" role="row" class="add">
                                <td class="dtr-control">
                                    <span class="form-label badge badge-warning badge-pill">{{trans('threshold.form.alert')}}</span>
                                </td>
                                <td class="dtr-control">
                                    <span>{{$threshold->properties[9][3]}}</span>
                                </td>
                                <td class="dtr-control">
                                    <span>{{$threshold->properties[10][3]}}</span>
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
                                    <span>{{$threshold->properties[11][3]}}</span>
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
                    <a class="btn btn-success" href="{{ route('thresholds.edit', $threshold->id) }}">
                        <i class="fas fa-save pr-2"></i> {{ trans('general.edit') }}
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection