@extends('layouts.admin')

@section('title', trans('general.title.show', ['type' => trans_choice('general.sources', 1)]))
@inject('IndicatorSource','\App\Models\Indicators\Sources\IndicatorSource')
@section('subheader-title')
    <i class="fal fa-plus text-primary"></i> {{ trans('general.title.show', ['type' => trans_choice('general.sources', 1)]) }}
    - {{ $source->name }}
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-6 required">
                    <label class="form-label" for="name">{{ trans('general.name') }}</label>
                    <div class="input-group bg-white shadow-inset-2">
                        <div class="input-group-prepend">
                                <span class="input-group-text bg-transparent border-right-0">
                                    <i class="fal fa-font"></i>
                                </span>
                        </div>
                        <input type="text" class="form-control border-left-0 bg-transparent pl-0" disabled value="{{$source->name }}">
                    </div>
                </div>

                <div class="form-group col-6 required">
                    <label class="form-label" for="institution">{{ trans('general.institution') }}</label>
                    <div class="input-group bg-white shadow-inset-2">
                        <div class="input-group-prepend">
                                <span class="input-group-text bg-transparent border-right-0">
                               <i class="fal fa-container-storage"></i>
                                </span>
                        </div>
                        <input type="text" class="form-control border-left-0 bg-transparent pl-0" disabled value="{{$source->institution }}">
                    </div>
                </div>

                <div class="form-group col-6 required">
                    <label class="form-label" for="description">{{ trans('general.description') }}</label>
                    <div class="input-group bg-white shadow-inset-2">
                        <div class="input-group-prepend">
                                <span class="input-group-text bg-transparent border-right-0">
                                   <i class="fal fa-align-justify"></i>
                                </span>
                        </div>
                        <textarea class="form-control border-left-0 bg-transparent pl-0" disabled rows="2">{{$source->description }}</textarea>
                    </div>
                </div>

                <div class="form-group col-6 required">
                    <label class="form-label" for="type">{{ trans('general.type') }}</label>
                    <div class="input-group bg-white shadow-inset-2">
                        <div class="input-group-prepend">
                                <span class="input-group-text bg-transparent border-right-0">
                                   <i class="fal fa-arrows-v"></i>
                                </span>
                        </div>
                        <input type="text" class="form-control border-left-0 bg-transparent pl-0" disabled
                               value="{{$source->type==$IndicatorSource::TYPE_SURVEY?trans('indicators.indicator.TYPE_'.$IndicatorSource::TYPE_SURVEY):'' }}{{$source->type==$IndicatorSource::TYPE_ADMINISTRATIVE_RECORD?trans('indicators.indicator.TYPE_'.$IndicatorSource::TYPE_ADMINISTRATIVE_RECORD):'' }}{{$source->type==$IndicatorSource::TYPE_TRANSACTIONAL?trans('indicators.indicator.TYPE_'.$IndicatorSource::TYPE_TRANSACTIONAL):'' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection