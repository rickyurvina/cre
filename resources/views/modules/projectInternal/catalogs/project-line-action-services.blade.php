@extends('layouts.admin')

@section('title', trans_choice('project.line_action_services', 2))

@section('subheader')
    <h1 class="subheader-title">
        <i class="fal fa-folder text-primary"></i> <span
                class="fw-300">{{ trans_choice('project.line_action_services', 2) }}</span>
    </h1>

    <button type="button"
            class="btn btn-success btn-sm mb-2 mr-2"
            data-toggle="modal"
            data-target="#createModal">
        <span class="fas fa-plus mr-1"></span> &nbsp;{{ trans('general.add_new') }}
    </button>
@endsection

@section('content')

    @livewire('projects.catalogs.project-line-action-services')

@endsection


