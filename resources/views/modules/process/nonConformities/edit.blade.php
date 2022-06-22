@extends('modules.process.processes.process')
@section('title', __('general.edit'))
@section('process-page')

    <livewire:process.non-conformities.edit-non-conformity :idNonConformity="$nonConformities->id" :subMenu="$subMenu" :page="$page"/>

@endsection