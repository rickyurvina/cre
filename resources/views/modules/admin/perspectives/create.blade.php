@extends('layouts.admin')

@section('title', trans('general.title.create',['type' => trans_choice('general.perspectives', 1)]))

@section('subheader-title')
    <i class="fal fa-plus text-primary"></i> {{ trans('general.title.create') }}
@endsection

@section('content')
    <div class="card">
        <form action="{{ route('perspectives.store') }}" method="post">
            @csrf
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
                                   value="{{ old('name') }}" placeholder="{{ trans('general.form.enter', ['field' => trans('general.name')]) }}">
                            <div class="invalid-feedback">{{ $errors->first('name',':message') }} </div>
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