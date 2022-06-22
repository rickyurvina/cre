@extends('modules.process.processes.process')
@section('title', __('general.edit'))
@section('process-page')
    <ol class="breadcrumb bg-transparent pl-0 pr-0 mb-0" style="margin-top: -3%">
        <li class="breadcrumb-item">
            <a href="{{ route('process.showPlanChanges',[$process->id, $page]) }}">
                {{ trans('process.plan_changes') }}
            </a>
        </li>
        <li class="breadcrumb-item active">{{ $processPlanChanges->code}}</li>
    </ol>
    <div class="d-flex flex-column">
        <div class="d-flex flex-nowrap">
            <div class="flex-grow-1 w-100" style="overflow: hidden auto">
                <div>
                    <ul class="nav nav-tabs nav-tabs-clean" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-general" role="tab"
                               aria-selected="true">{{ trans('general.general') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-risks" role="tab"
                               aria-selected="false">{{ trans_choice('general.activities',2) }}</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="tab-general" role="tabpanel">
                            <div class="pl-2 content-detail mt-2">
                                <div class="d-flex flex-wrap">
                                    <x-label-detail>Elaborado por:</x-label-detail>
                                    <div class="detail">
                                        <livewire:components.dropdown-user :modelId="$processPlanChanges->id"
                                                                           modelClass="\App\Models\Process\ProcessPlanChanges"
                                                                           field="user_id"
                                                                           :key="time().$processPlanChanges->id"
                                                                           :user="$processPlanChanges->responsible"/>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap w-90">
                                    <x-label-detail>{{__('general.code')}}:</x-label-detail>
                                    <div class="detail">
                                        <livewire:components.input-inline-edit :modelId="$processPlanChanges->id"
                                                                               class="\App\Models\Process\ProcessPlanChanges"
                                                                               field="code"
                                                                               :rules="'required|max:5|alpha_num|alpha_dash|unique:process_plan_changes,code,' . $processPlanChanges->id . ',id,process_id,' . $processPlanChanges->process_id. ',deleted_at,NULL'"
                                                                               type="text"
                                                                               defaultValue="{{ $processPlanChanges->code ?? ''}}"
                                                                               :key="time().$processPlanChanges->id"/>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap w-100 mt-2">
                                    <x-label-detail>{{trans('general.date')}}</x-label-detail>
                                    <div class="detail">
                                        <livewire:components.date-inline-edit :modelId="$processPlanChanges->id"
                                                                              class="\App\Models\Process\ProcessPlanChanges"
                                                                              field="date" type="date"
                                                                              :rules="'required|date'"
                                                                              defaultValue="{{$processPlanChanges->date ? $processPlanChanges->date->format('Y M d'): 'Seleccione Fecha'}}"
                                                                              :key="time().$processPlanChanges->id"
                                        />
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap w-90">
                                    <x-label-detail>{{__('general.description')}}:</x-label-detail>
                                    <div class="detail">
                                        <livewire:components.input-inline-edit :modelId="$processPlanChanges->id"
                                                                               class="\App\Models\Process\ProcessPlanChanges"
                                                                               field="description"
                                                                               :rules="'required|max:500'"
                                                                               type="textarea"
                                                                               defaultValue="{{ $processPlanChanges->description ?? ''}}"
                                                                               :key="time().$processPlanChanges->id"/>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap w-90">
                                    <x-label-detail>{{__('general.objective')}}:</x-label-detail>
                                    <div class="detail">
                                        <livewire:components.input-inline-edit :modelId="$processPlanChanges->id"
                                                                               class="\App\Models\Process\ProcessPlanChanges"
                                                                               field="objective"
                                                                               :rules="'required|max:500'"
                                                                               type="textarea"
                                                                               defaultValue="{{ $processPlanChanges->objective ?? ''}}"
                                                                               :key="time().$processPlanChanges->id"/>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap">

                                    <x-label-detail>{{ trans('general.consequence') }}</x-label-detail>
                                    <div class="detail">
                                        <livewire:components.input-inline-edit :modelId="$processPlanChanges->id"
                                                                               class="\App\Models\Process\ProcessPlanChanges"
                                                                               field="consequence"
                                                                               :rules="'required|max:500'"
                                                                               type="textarea"
                                                                               defaultValue="{{ $processPlanChanges->consequence ?? ''}}"
                                                                               :key="time().$processPlanChanges->id"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-risks" role="tabpanel">
                            <livewire:process.plan-changes.changes-activities.changes-activities-index :changeId="$processPlanChanges->id"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection