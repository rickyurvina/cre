@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.sources', 1)]))
@inject('IndicatorSource','\App\Models\Indicators\Sources\IndicatorSource')
@section('subheader-title')
    <i class="fal fa-plus text-primary"></i> {{ trans('general.title.edit', ['type' => trans_choice('general.sources', 1)]) }}
    - {{ $source->name }}
@endsection

@section('content')
    <div class="card">

        <form action="{{ route('indicator_sources.update', $source->id) }}" method="post">
            @csrf
            @method('PUT')
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
                            <input type="text" name="name" id="name" class="form-control border-left-0 bg-transparent pl-0 @error('name') is-invalid @enderror"
                                   value="{{ old('name', $source->name) }}" placeholder="{{ trans('general.form.enter', ['field' => trans('general.name')]) }}">
                            <div class="invalid-feedback">{{ $errors->first('name',':message') }} </div>
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
                            <input type="text" name="institution" id="institution"
                                   class="form-control border-left-0 bg-transparent pl-0 @error('institution') is-invalid @enderror"
                                   value="{{ old('institution',$source->institution) }}" placeholder="{{ trans('general.form.enter', ['field' => trans('general.institution')]) }}">
                            <div class="invalid-feedback">{{ $errors->first('institution',':message') }} </div>
                        </div>
                    </div>

                    <div class="form-group col-6">
                        <label class="form-label" for="description">{{ trans('general.description') }}</label>
                        <div class="input-group bg-white shadow-inset-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-transparent border-right-0">
                                   <i class="fal fa-align-justify"></i>
                                </span>
                            </div>
                            <input type="text" name="description" id="description"
                                   class="form-control border-left-0 bg-transparent pl-0 @error('description') is-invalid @enderror"
                                   value="{{ old('description',$source->description) }}" placeholder="{{ trans('general.form.enter', ['field' => trans('general.description')]) }}">
                            <div class="invalid-feedback">{{ $errors->first('description',':message') }} </div>
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
                            <select class="custom-select @error('type') is-invalid @enderror" id="type" name="type">
                                <option value="">{{trans('general.choose')}}  {{ trans('general.type') }}</option>
                                <option value="{{$IndicatorSource::TYPE_SURVEY}}" {{$source->type==$IndicatorSource::TYPE_SURVEY ? 'selected':''}}>{{trans('indicators.indicator.TYPE_'.$IndicatorSource::TYPE_SURVEY) }}</option>
                                <option value="{{$IndicatorSource::TYPE_ADMINISTRATIVE_RECORD}}" {{$source->type==$IndicatorSource::TYPE_ADMINISTRATIVE_RECORD ? 'selected':''}}>{{trans('indicators.indicator.TYPE_'.$IndicatorSource::TYPE_ADMINISTRATIVE_RECORD) }}</option>
                                <option value="{{$IndicatorSource::TYPE_TRANSACTIONAL}}" {{$source->type==$IndicatorSource::TYPE_TRANSACTIONAL ? 'selected':''}}>{{trans('indicators.indicator.TYPE_'.$IndicatorSource::TYPE_TRANSACTIONAL) }}</option>
                            </select>
                        </div>
                        <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('type',':message') }} </div>
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
@endsection