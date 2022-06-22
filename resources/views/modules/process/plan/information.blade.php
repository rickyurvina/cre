@extends('modules.process.processes.process')

@section('process-page')
    <div class="d-flex flex-wrap pl-3" style="margin-top: -1% !important;">
        <div class="w-50">
            <div class="d-flex flex-nowrap">
                <div class="flex-grow-1 w-100" style="overflow: hidden auto">
                    <div class="card pl-3">
                        <div class="p-3">
                            <x-label-section>Información del Proceso</x-label-section>
                        </div>
                        <div class="content-detail">
                            <div class="d-flex justify-content-center w-50">
                                <x-label-detail>{{trans('general.code')}}</x-label-detail>
                                <x-content-detail> {{$process->code}}</x-content-detail>
                            </div>
                            <div class="d-flex justify-content-center w-50">
                                <x-label-detail>{{trans('general.name')}}</x-label-detail>
                                <x-content-detail> {{$process->name }}</x-content-detail>
                            </div>
                            <div class="d-flex justify-content-center w-50">
                                <x-label-detail>{{trans('general.description')}}</x-label-detail>
                                <x-content-detail> {{$process->description}}</x-content-detail>
                            </div>
                            <div class="d-flex justify-content-center w-50">
                                <x-label-detail>{{trans_choice('general.department',1)}}</x-label-detail>
                                <x-content-detail>{{$process->department->name }}</x-content-detail>
                            </div>
                            <div class="d-flex justify-content-center w-50">
                                <x-label-detail>{{ trans('general.process_owner') }}</x-label-detail>
                                <x-content-detail> {{$process->owner->getFullName() }}</x-content-detail>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 w-100 mt-4 p-2">
                        <x-label-section>{{ trans('general.comments') }}</x-label-section>
                        <livewire:components.comments :modelId="$process->id" class="\App\Models\Process\Process" identifier="information"
                                                      :key="time().$process->id"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-50 text-center">
            <div style="width: 95% !important;">
                <div class="card pl-2 ml-1">
                    <div class="pb-5 pt-3 pl-2">
                        <div class="row">
                            <a class="col-6 col-xl-3 d-sm-flex align-items-center" href="{{ route('process.showActivities',[$process->id, $page]) }}">
                                <div class="p-2 mr-3 bg-info-200 rounded">
                                    <span class="peity-bar" data-peity="{&quot;fill&quot;: [&quot;#fff&quot;], &quot;width&quot;: 27, &quot;height&quot;: 27 }"
                                          style="display: none;">3,4,5,8,2</span>
                                    <svg class="peity" height="27" width="27">
                                        <rect data-value="3" fill="#fff" x="0.539772" y="16.867875" width="4.318176" height="10.120725"></rect>
                                        <rect data-value="4" fill="#fff" x="5.937492000000001" y="13.4943" width="4.318175999999999" height="13.4943"></rect>
                                        <rect data-value="5" fill="#fff" x="11.335212000000002" y="10.120725" width="4.318175999999999" height="16.867875"></rect>
                                        <rect data-value="8" fill="#fff" x="16.732932" y="0" width="4.318176000000001" height="26.9886"></rect>
                                        <rect data-value="2" fill="#fff" x="22.130652" y="20.24145" width="4.318176000000001" height="6.747150000000001"></rect>
                                    </svg>
                                </div>
                                <div>
                                    <label class="fs-sm mb-0">Actividades</label>
                                    <h4 class="font-weight-bold mb-0">{{$process->activitiesProcess->count()}}</h4>
                                </div>
                            </a>
                            <a class="col-6 col-xl-3 d-sm-flex align-items-center" href="{{ route('process.showIndicators',[$process->id, $page]) }}">
                                <div class="p-2 mr-3 bg-success-300 rounded">
                                    <span class="peity-bar" data-peity="{&quot;fill&quot;: [&quot;#fff&quot;], &quot;width&quot;: 27, &quot;height&quot;: 27 }"
                                          style="display: none;">3,4,3,5,5</span>
                                    <svg class="peity" height="27" width="27">
                                        <rect data-value="3" fill="#fff" x="0.539772" y="10.795440000000003" width="4.318176" height="16.19316"></rect>
                                        <rect data-value="4" fill="#fff" x="5.937492000000001" y="5.39772" width="4.318175999999999" height="21.590880000000002"></rect>
                                        <rect data-value="3" fill="#fff" x="11.335212000000002" y="10.795440000000003" width="4.318175999999999" height="16.19316"></rect>
                                        <rect data-value="5" fill="#fff" x="16.732932" y="0" width="4.318176000000001" height="26.9886"></rect>
                                        <rect data-value="5" fill="#fff" x="22.130652" y="0" width="4.318176000000001" height="26.9886"></rect>
                                    </svg>
                                </div>
                                <div>
                                    <label class="fs-sm mb-0">Indicadores</label>
                                    <h4 class="font-weight-bold mb-0">{{$process->indicators->count()}}</h4>
                                </div>
                            </a>
                            <a class="col-6 col-xl-3 d-sm-flex align-items-center" href="{{ route('process.showConformities',[$process->id, \App\Models\Process\Process::PHASE_ACT]) }}">
                                <div class="mr-3 bg-warning-300 rounded">
                                    <span class="peity-bar" data-peity="{&quot;fill&quot;: [&quot;#fff&quot;], &quot;width&quot;: 27, &quot;height&quot;: 27 }"
                                          style="display: none;">3,4,3,5,5</span>
                                    <svg class="peity" height="27" width="27">
                                        <rect data-value="3" fill="#fff" x="0.539772" y="10.795440000000003" width="4.318176" height="16.19316"></rect>
                                        <rect data-value="4" fill="#fff" x="5.937492000000001" y="5.39772" width="4.318175999999999" height="21.590880000000002"></rect>
                                        <rect data-value="3" fill="#fff" x="11.335212000000002" y="10.795440000000003" width="4.318175999999999" height="16.19316"></rect>
                                        <rect data-value="5" fill="#fff" x="16.732932" y="0" width="4.318176000000001" height="26.9886"></rect>
                                        <rect data-value="5" fill="#fff" x="22.130652" y="0" width="4.318176000000001" height="26.9886"></rect>
                                    </svg>
                                </div>
                                <div>
                                    <label class="fs-sm mb-0">No conformidades</label>
                                    <h4 class="font-weight-bold mb-0">{{$process->nonConformities->count()}}</h4>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
