@extends('layouts.admin')

@section('title', trans_choice('general.module_process', 1))

@section('subheader')
    <h1 class="subheader-title">
        <i class="fal fa-balance-scale-right text-primary"></i> <span class="fw-300">{{trans_choice('process.process',1)}}</span>
    </h1>
    <div class="d-flex flex-row-reverse ml-auto ml-2">
        <button type="button" class="btn btn-success border-0 shadow-0" data-toggle="modal"
                data-target="#create-process-modal">{{ trans('general.create')}} {{trans('general.process')}}
        </button>
    </div>
    <div class="subheader-block d-lg-flex align-items-center">
        <livewire:process.create-process/>
    </div>
@endsection
@section('content')
    <livewire:process.index-process/>
@endsection
