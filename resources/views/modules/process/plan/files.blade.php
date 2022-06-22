@extends('modules.process.processes.process')

@section('process-page')
        <livewire:components.files :modelId="$process->id"
                                   model="{{\App\Models\Process\Process::class}}"
                                   folder="process"
                                   event="fileAdded"
        />
@endsection
