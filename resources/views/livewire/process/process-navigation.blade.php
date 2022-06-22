<div>
    <div class="p-3 mb-6">
        <div class="row">
            <div class="col-12">
                <ol class="breadcrumb bg-transparent pl-0 pr-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('processes.index') }}">
                            {{ trans('process.list') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active"> {{ trans_choice('process.process',0) .': '.$process->name }}</li>
                </ol>
            </div>
            <div class="col-12">
                <div class="d-flex flex-wrap">
                    <div class="btn-group">
                        <a href="{{ route('process.showInformation',  [$process->id, \App\Models\Process\Process::PHASE_PLAN]) }}"
                           class="btn @if($page==\App\Models\Process\Process::PHASE_PLAN) btn-primary @else  btn-secondary @endif mr-2">{{trans('process.plan')}}</a>
                        <a href="{{ route('process.showConformities', [$process->id, \App\Models\Process\Process::PHASE_ACT]) }}"
                           class="btn @if($page==\App\Models\Process\Process::PHASE_ACT) btn-primary @else  btn-secondary @endif mr-2">{{trans('process.act')}}</a>
                        <a href="{{ route('process.showFiles', [$process->id, \App\Models\Process\Process::PHASE_DO_PROCESS]) }}"
                           class="btn @if($page==\App\Models\Process\Process::PHASE_DO_PROCESS) btn-primary @else  btn-secondary @endif mr-2">{{trans('process.do')}}</a>
                        <a href="{{ route('process.showIndicators', [$process->id, \App\Models\Process\Process::PHASE_CHECK]) }}"
                           class="btn @if($page==\App\Models\Process\Process::PHASE_CHECK) btn-primary @else  btn-secondary @endif mr-2">{{trans('process.check')}}</a>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex d-flex-row w-100 mt-2">
                    @switch($page)
                        @case(\App\Models\Process\Process::PHASE_PLAN)
                        @include('livewire.process.plan.menu')
                        @break
                        @case(\App\Models\Process\Process::PHASE_ACT)
                        @include('livewire.process.act.menu')
                        @break
                        @case(\App\Models\Process\Process::PHASE_DO_PROCESS)
                        @include('livewire.process.do.menu')
                        @break
                        @case(\App\Models\Process\Process::PHASE_CHECK)
                        @include('livewire.process.check.menu')
                        @break
                    @endswitch
                </div>
            </div>
        </div>
    </div>
</div>
