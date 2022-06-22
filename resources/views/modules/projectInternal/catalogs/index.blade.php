@extends('layouts.admin')

@section('title', trans_choice('general.catalog', 2))

@section('subheader')
    <h1 class="subheader-title">
        <i class="fal fa-folder text-primary"></i> <span class="fw-300">{{ trans_choice('general.catalog', 2) }}</span>
    </h1>
    <div class="subheader-block d-lg-flex align-items-center">
        @can('create-auth-users')
            <a href="{{ route('companies.create') }}" class="btn btn-success btn-sm"><span
                        class="fas fa-plus mr-1"></span>
                &nbsp;{{ trans('general.add_new') }}</a>
        @endcan
    </div>
@endsection

@section('content')

    <div class="row row-cols-1 row-cols-md-3 justify-content-center">
        <div class="col mb-4">
            <a href="{{ route('line-actions.index') }}" class="card border-dashed btn-select">
                <div class="card-body d-flex align-items-center">
                    <h5 class="card-title mx-auto my-3">
                    <span class="fs-xl fw-700 color-fusion-700 d-block">
                        {{ trans_choice('project.line_actions',2) }}
                    </span>
                    </h5>
                </div>
            </a>
        </div>
        <div class="col mb-4">
            <a href="{{ route('line-action.services.index') }}" class="card border-dashed btn-select">
                <div class="card-body d-flex align-items-center">
                    <h5 class="card-title mx-auto my-3">
                    <span class="fs-xl fw-700 color-fusion-700 d-block">
                        {{ trans_choice('project.line_action_services', 2) }}
                    </span>
                    </h5>
                </div>
            </a>
        </div>
        <div class="col mb-4">
            <a href="{{ route('line-action.service.activities.index') }}" class="card border-dashed btn-select">
                <div class="card-body d-flex align-items-center">
                    <h5 class="card-title mx-auto my-3">
                    <span class="fs-xl fw-700 color-fusion-700 d-block">
                        {{ trans_choice('project.line_action_service_activities',2) }}
                    </span>
                    </h5>
                </div>
            </a>
        </div>
        <div class="col mb-4">
            <a href="{{ route('founders.index') }}" class="card border-dashed btn-select">
                <div class="card-body d-flex align-items-center">
                    <h5 class="card-title mx-auto my-3">
                    <span class="fs-xl fw-700 color-fusion-700 d-block">
                        {{ trans_choice('project.funders',2) }}
                    </span>
                    </h5>
                </div>
            </a>
        </div>
        <div class="col mb-4">
            <a href="{{ route('assistants.index') }}" class="card border-dashed btn-select">
                <div class="card-body d-flex align-items-center">
                    <h5 class="card-title mx-auto my-3">
                    <span class="fs-xl fw-700 color-fusion-700 d-block">
                        {{ trans_choice('project.assistants',2) }}
                    </span>
                    </h5>
                </div>
            </a>
        </div>
    </div>

@endsection