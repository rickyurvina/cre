@extends('modules.process.processes.process')

@section('process-page')
    <div style="margin-top: -3%">
        <livewire:risks.index-risks :modelId="$process->id"
                                    class="{{\App\Models\Process\Process::class}}"/>
    </div>
@endsection
