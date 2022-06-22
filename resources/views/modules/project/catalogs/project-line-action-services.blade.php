@extends('layouts.admin')

@section('title', trans_choice('project.line_action_services', 2))

@section('subheader')
    <h1 class="subheader-title">
        <i class="fal fa-folder text-primary"></i> <span
                class="fw-300">{{ trans_choice('project.line_action_services', 2) }}</span>
        <ol class="breadcrumb bg-transparent breadcrumb-sm pl-0 pr-0">
            <li class="breadcrumb-item active">
                <a href="{{ route('projects.catalogs') }}" class="fs-2x"><i class="fal fa-folder-open mr-1"></i>{{trans_choice('general.catalog',2)}}</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('line-action.services.index') }}" class="fs-2x"><i class="fal fa-folder-open mr-1"></i>{{ trans('general.services') }}</a>
            </li>
        </ol>
    </h1>
    @can('project-crud-project')
    <button type="button"
            class="btn btn-success btn-sm mb-2 mr-2"
            data-toggle="modal"
            data-target="#createModal">
        <span class="fas fa-plus mr-1"></span> &nbsp;{{ trans('general.add_new') }}
    </button>
    @endcan
@endsection

@section('content')

    @livewire('projects.catalogs.project-line-action-services')

@endsection


