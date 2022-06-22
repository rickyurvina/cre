@extends('layouts.admin')

@section('title', trans('general.title.show', ['type' => trans_choice('general.units', 1)]))

@section('subheader-title')
    <i class="fal fa-plus text-primary"></i> {{ trans('general.title.show', ['type' => trans_choice('general.units', 1)]) }}
    - {{ $unit->name }}
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-6">
                    <label class="form-label" for="name">{{ trans('general.name') }}</label>
                    <div class="input-group bg-white shadow-inset-2">
                        <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-right-0">
                                        <i class="fal fa-sort-numeric-down-alt"></i>
                                    </span>
                        </div>
                        <input type="text" class="form-control border-left-0 bg-transparent pl-0" disabled value="{{$unit->name }}">
                    </div>
                </div>
                <div class="form-group col-6">
                    <label class="form-label" for="abbreviation">{{  trans('indicators.indicator.abbreviation')  }}</label>
                    <div class="input-group bg-white shadow-inset-2">
                        <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-right-0">
                                        <i class="fal fa-sort-numeric-down-alt"></i>
                                    </span>
                        </div>
                        <input type="text" class="form-control border-left-0 bg-transparent pl-0" disabled value="{{$unit->abbreviation }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection