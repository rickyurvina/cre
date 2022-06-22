@extends('layouts.admin')

@section('title', __('poa.activities_poa'))

@push('css')
    <style>
        .subheader {
            margin-bottom: 8px !important;
        }
    </style>
@endpush

@section('subheader')
    <h1 class="subheader-title">
        <i class="fal fa-align-left text-primary"></i> {{ __('poa.activities_poa') }}
    </h1>
    <a href="{{ route('poa.poas') }}" class="btn btn-info btn-sm">
        <span class="fas fa-arrow-alt-left"></span>
        {{ trans('general.go_back') }}
    </a>
@endsection

@section('content')
    <div class="text-info ml-4 mt-2">
        @if(isset($poa))
            <h3>{{ $poa->name }} ({{ $poa->year }})</h3>
        @endif
    </div>
    <livewire:poa.poa-program :poaId="$poaId"/>
    <livewire:poa.activity.poa-create-activity :poaId="$poaId"/>
    <livewire:poa.poa-assign-weights :poaId="$poaId"/>
    <livewire:poa.poa-assign-goals/>
    <livewire:poa.poa-activity-goal-edit/>
    <livewire:poa.poa-activity-progress-edit/>
    <livewire:poa.poa-activity-weight-edit/>
    <livewire:poa.poa-activity-edit/>
    <livewire:poa.poa-activity-goal-change-request/>
@endsection


@push('page_script')
    <script>
        Livewire.on('toggleAssignWeight', () => $('#poa-assign-weights').modal('toggle'));
        Livewire.on('toggleModalGoalsProgram', () => $('#poa-assign-goals').modal('toggle'));
        Livewire.on('toggleModalCreateActivity', () => $('#poa-create-activity-modal').modal('toggle'));
        Livewire.on('toggleModalIndicatorGoalChangeRequest', () => $('#poa-goal-change-request-modal').modal('toggle'));
        Livewire.on('toggleModalProgressActivity', () => $('#poa-edit-activity-progress-modal').modal('toggle'));
        Livewire.on('toggleModalGoalActivity', () => $('#poa-edit-activity-goal-modal').modal('toggle'));
        Livewire.on('toggleModalActivityWeight', () => $('#poa-edit-activity-weight-modal').modal('toggle'));
        $('#poa-create-activity-modal').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let programId = $(e.relatedTarget).data('program-id');
            let indicatorId = $(e.relatedTarget).data('indicator-id');
            //Livewire event trigger
            Livewire.emit('loadIndicators', programId, indicatorId);
        });

        $('#poa-assign-weights').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let programId = $(e.relatedTarget).data('program-id');
            //Livewire event trigger
            Livewire.emit('loadPrograms', programId);
        });

        $('#poa-assign-goals').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let programId = $(e.relatedTarget).data('program-id');
            //Livewire event trigger
            Livewire.emit('loadProgram', programId);
        });
    </script>
@endpush