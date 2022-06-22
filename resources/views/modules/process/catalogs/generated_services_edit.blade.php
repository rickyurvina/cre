@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans('general.generated_service')]))

@section('subheader-title')
    <i class="fal fa-plus text-primary"></i> {{ trans('general.title.edit', ['type' => trans('general.generated_service')]) }}
    - {{ $generatedService->name }}
@endsection

@section('content')
    <div class="card">

        <form action="{{ route('generated_services.update', $generatedService->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-6 required">
                        <label class="form-label" for="code">{{ trans('general.code') }}</label>
                        <div class="input-group bg-white shadow-inset-2">
                            <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-right-0">
                                        <i class="fal fa-sort-numeric-down-alt"></i>
                                    </span>
                            </div>
                            <input type="text" required name="code" id="code" class="form-control border-left-0 bg-transparent pl-0 @error('code') is-invalid @enderror"
                                   value="{{ old('code', $generatedService->code) }}" placeholder="{{ trans('general.form.enter', ['field' => trans('general.code')]) }}">
                            <div class="invalid-feedback">{{ $errors->first('code',':message') }} </div>
                        </div>
                    </div>

                    <div class="form-group col-6 required">
                        <label class="form-label" for="name">{{ trans('general.name') }}</label>
                        <div class="input-group bg-white shadow-inset-2">
                            <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-right-0">
                                        <i class="fal fa-sort-numeric-down-alt"></i>
                                    </span>
                            </div>
                            <input type="text" required name="name" id="name" class="form-control border-left-0 bg-transparent pl-0 @error('name') is-invalid @enderror"
                                   value="{{ old('name', $generatedService->name) }}" placeholder="{{ trans('general.form.enter', ['field' => trans('general.name')]) }}">
                            <div class="invalid-feedback">{{ $errors->first('name',':message') }} </div>
                        </div>
                    </div>

                    <div class="form-group col-6 required">
                        <label class="form-label" for="description">{{ trans('general.description') }}</label>
                        <div class="input-group bg-white shadow-inset-2">
                            <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-right-0">
                                        <i class="fal fa-sort-numeric-down-alt"></i>
                                    </span>
                            </div>
                            <input type="text" required name="description" id="description" class="form-control border-left-0 bg-transparent pl-0 @error('description') is-invalid @enderror"
                                   value="{{ old('name', $generatedService->description) }}" placeholder="{{ trans('general.form.enter', ['field' => trans('general.description')]) }}">
                            <div class="invalid-feedback">{{ $errors->first('description',':message') }} </div>
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
@endsection