@extends('layouts.admin')

@section('title', trans('auth.login'))

@push('css')
    <style>
        .subheader{
            margin-bottom: 8px!important;
        }
    </style>
@endpush

@section('subheader')
@endsection

@section('content')

    <livewire:poa.poa-program :poaId="$poaId"/>
    <livewire:poa.activity.poa-create-activity :poaId="$poaId"/>
    <livewire:poa.poa-assign-weights :poaId="$poaId"/>
    <livewire:poa.poa-assign-goals />
    <livewire:poa.poa-activity-goal-edit/>
    <livewire:poa.poa-activity-progress-edit/>
    <livewire:poa.poa-activity-weight-edit/>
    <livewire:poa.poa-activity-edit/>
    <livewire:poa.poa-approved :poaId="$poaId"/>
    <livewire:poa.poa-approved-edit :poaId="$poaId"/>

@endsection

@push('page_script')
    <script>
        Livewire.on('toggleAssignWeight', () => $('#poa-assign-weights').modal('toggle'));
        Livewire.on('toggleModalGoalsProgram', () => $('#poa-assign-goals').modal('toggle'));
        Livewire.on('toggleModalCreateActivity', () => $('#poa-create-activity-modal').modal('toggle'));
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



        $('#poa-approve-poa').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let poaId = $(e.relatedTarget).data('poa-id');
            //Livewire event trigger
            Livewire.emit('loadPoa', poaId);
        });

        $('#poa-approve-poa-edit').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let poaId = $(e.relatedTarget).data('poa-id');
            //Livewire event trigger
            Livewire.emit('loadPoa', poaId);
        });
    </script>
@endpush