@extends('modules.project.project')

@section('project-page')
        <div class="p-2">
                <livewire:projects.formulation.project-show-monitoring :project="$project"/>
        </div>

@endsection

