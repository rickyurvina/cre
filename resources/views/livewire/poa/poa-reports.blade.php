<div>
    <div class="d-flex align-items-center mb-2">
        <div class="btn-group">
            <button type="button" class="btn btn-info" style="background-color: #0046AD">  {{trans('common.filters')}}
                <i class="fal fa-filter"></i></button>
            <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split"
                    style="background-color: #0046AD" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                <span class="sr-only"></span>
            </button>
            <div wire:ignore.self class="dropdown-menu" id="dropdown-filter" style="width: 35rem !important;">
                <div class="d-flex align-items-start">
                    <div class="d-flex flex-column p-3">
                        <h5 class="mb-0"> {{trans('general.provincial_boards')}}</h5>
                        <div class="dropdown-divider"></div>
                        @foreach($provinces as $province)
                            <div class="custom-control custom-checkbox cursor-pointer">
                                <input type="checkbox" class="custom-control-input" id="defaultUnchecked.{{ $province->id }}"
                                       value="{{ $province->id }}"
                                       wire:model="selectedProvince.{{ $province->id }}">
                                <label class="custom-control-label"
                                       for="defaultUnchecked.{{ $province->id }}">{{ strlen($province->name)>25?substr($province->name,0,25)."...": $province->name  }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex flex-column p-3">
                        <h5 class="mb-0"> {{trans('general.canton_boards')}}</h5>
                        <div class="dropdown-divider"></div>
                        @if(count($this->selectedProvince)>0)
                            @foreach($cantones as $canton)
                                <div class="custom-control custom-checkbox cursor-pointer">
                                    <input type="checkbox" class="custom-control-input" id="defaultUnchecked1.{{ $canton->id }}"
                                           value="{{$canton->id}}"
                                           wire:model.defer="selectedCanton.{{ $canton->id }}">
                                    <label class="custom-control-label"
                                           for="defaultUnchecked1.{{ $canton->id }}">{{ $canton->name }}</label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="d-flex flex-column p-3">
                        <h5 class="mb-0"> {{trans('general.years')}}</h5>
                        <div class="dropdown-divider"></div>
                        @if(count($this->years)>0)
                            @foreach($this->years as $year)
                                <div class="custom-control custom-checkbox cursor-pointer">
                                    <input type="checkbox" class="custom-control-input" id="defaultUnchecked2.{{$year}}"
                                           value="{{$year}}"
                                           wire:model.defer="selectedYears.{{$year}}">
                                    <label class="custom-control-label"
                                           for="defaultUnchecked2.{{$year}}">{{ $year  }}</label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="d-flex flex-column p-3">
                        <h5 class="mb-0"> {{trans('general.state')}}</h5>
                        <div class="dropdown-divider"></div>
                        @foreach($this->states as $state)
                            <div class="custom-control custom-checkbox cursor-pointer">
                                <input type="checkbox" class="custom-control-input" id="defaultUnchecked3.{{$state}}"
                                       value="{{$state}}"
                                       wire:model.defer="selectedStates">
                                <label class="custom-control-label"
                                       for="defaultUnchecked3.{{$state}}">{{ $state  }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <div class="text-right mr-3">
                    <button type="button" class="btn btn-xs btn-outline-primary fs-xl shadow-0"
                            wire:click="filter()"><span class="fal fa-filter mr-1"></span> {{trans('common.filter')}}
                    </button>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-light ml-2" style="background-color: #D52B1E; color: white;"
                wire:click="cleanAllFilters()">  {{trans('common.clean_filters')}}</button>
        @if(count($filtersSelected)>0)
            @foreach($filtersSelected as $f)
                <div class="alert alert-primary alert-dismissible fade show ml-1 mb-0" role="alert"
                     style="padding: 0.5rem 1.25rem !important;color: dimgray; background-color: #f3f1f5; border-color: #d6d3da; padding-right: 3.7rem !important; ">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                            style="padding: 0.5rem 1.25rem !important;"
                            wire:click="cleanFilter('{{ $f['type'] }}')">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong> {{ strlen($f['name'])>15?substr($f['name'],0,15)."...": $f['name']  }}</strong>
                </div>
            @endforeach
        @endif
    </div>
    <div class="card">
        <div class="card-header pr-2 d-flex align-items-center flex-wrap">
            <div class="d-flex position-relative ml-auto w-100">
                <i class="spinner-border spinner-border-sm position-absolute pos-left mx-3" style="margin-top: 0.75rem" wire:target="search" wire:loading></i>
                <i class="fal fa-search position-absolute pos-left fs-lg mx-3" style="margin-top: 0.75rem" wire:loading.remove></i>
                <input type="text" wire:model.debounce.300ms="search" class="form-control bg-subtlelight pl-6"
                       placeholder="Buscar por Nombre de la Actividad...">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-sm m-0">
                <thead class="bg-primary-50">
                <tr>
                    <th class="w-20">{{trans('general.program')}}</th>
                    <th class="w-20">{{trans('general.indicator')}}</th>
                    <th style="width: 8px !important; padding: 1px !important;"></th>
                    <th class="w-20">{{trans('general.activity')}}</th>
                    <th class="w-10">{{trans('general.goal')}}</th>
                    <th class="w-10">{{trans('general.progress')}}</th>
                    <th class="w-15"> {{trans('general.community')}}</th>
                    <th class="w-10"> {{trans('general.state')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($poas as $poa)
                    @if($poa->company->level == 3)
                        <tr style="background-color: #e8ecec" class="text-center">
                            <td colspan="8" style="font-size: 17px; font-weight: 600">
                                <div class="d-flex justify-content-center chart">
                                    <span class="p-3">J.C {{ $poa->company->name }}</span>
                                    <div
                                            class="js-easy-pie-chart color-success-500 position-relative d-inline-flex align-items-center justify-content-center"
                                            id="chartExecution"
                                            data-percent="{{number_format($poa->progress,1)}}" data-piesize="50"
                                            data-linewidth="5" data-linecap="butt">
                                        <div
                                                class="d-flex flex-column align-items-center justify-content-center position-absolute pos-left pos-right pos-top pos-bottom fw-300 fs-lg">
                                            <span class="js-percent d-block text-dark"></span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                    @if($poa->company->level == 2)
                        <tr style="background-color: #e8ecec" class="text-center align-items-center">
                            <td colspan="8" style="font-size: 17px; font-weight: 600">
                                <div class="d-flex justify-content-center chart">
                                    <span class="p-3">J.P {{ $poa->company->name }}</span>
                                    <div
                                            class="js-easy-pie-chart color-success-500 position-relative d-inline-flex align-items-center justify-content-center"
                                            id="chartExecution"
                                            data-percent="{{number_format($poa->progress,1)}}" data-piesize="50"
                                            data-linewidth="5" data-linecap="butt">
                                        <div
                                                class="d-flex flex-column align-items-center justify-content-center position-absolute pos-left pos-right pos-top pos-bottom fw-300 fs-lg">
                                            <span class="js-percent d-block text-dark"></span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                    @if($poa->company->level == 1)
                        <tr style="background-color: #e8ecec" class="text-center align-items-center">
                            <td colspan="8" style="font-size: 17px; font-weight: 600">
                                <div class="d-flex justify-content-center chart">
                                    <span class="p-3">
                                      S.C {{ $poa->company->name }}
                                    </span>
                                    <div
                                            class="js-easy-pie-chart color-success-500 position-relative d-inline-flex align-items-center justify-content-center"
                                            id="chartExecution"
                                            data-percent="{{number_format($poa->progress,1)}}" data-piesize="50"
                                            data-linewidth="5" data-linecap="butt">
                                        <div
                                                class="d-flex flex-column align-items-center justify-content-center position-absolute pos-left pos-right pos-top pos-bottom fw-300 fs-lg">
                                            <span class="js-percent d-block text-dark"></span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                    @foreach($poa->programs as $program)
                        @foreach($program->poaActivities as $activity)
                            @if(count($selectedStates)>0 )
                                @if(in_array($activity->state, $selectedStates))
                                    <tr>
                                        <td>{{ $program->planDetail->name }}</td>
                                        <td>{{ $activity->indicator->name }}</td>
                                        <td style="width: 8px !important;padding: 1px !important; background-color: {{ $activity->getStatus()[1] ?? '#FFFFFF' }};"></td>
                                        <td>
                                            <a href="javascript:void(0);" aria-expanded="false"
                                               style="color:{{ $activity->getStatus()[2] }} "
                                               wire:click="$emitTo('poa.poa-show-activity', 'open', {{ $activity->id }})">
                                                {{ $activity->name }}
                                            </a>
                                        </td>
                                        <td>{{ $activity->poaActivityIndicator->where('period','<=',date("m"))->sum('goal') }}</td>
                                        @if($activity->poaActivityIndicator->where('period','<=',date("m"))->sum('goal')>0)
                                            <td>{{ $activity->progress }}
                                                <small>
                                                    ({{ number_format($activity->progress / $activity->poaActivityIndicator
                                                            ->where('period','<=',date("m"))->sum('goal')*100, 1)  }} %)</small>
                                            </td>
                                        @else
                                            <td>{{ $activity->progress }} </td>
                                        @endif
                                        <td>{{ $activity->location->description ?? 'N/A' }} / {{ $activity->community }}</td>
                                        <td>{{ $activity->getStatus()[0] }} </td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td>{{ $program->planDetail->name }}</td>
                                    <td>{{ $activity->indicator->name }}</td>
                                    <td style="width: 8px !important;padding: 1px !important; background-color: {{ $activity->getStatus()[1] ?? '#FFFFFF' }};"></td>
                                    <td>
                                        <a href="javascript:void(0);" aria-expanded="false" style="color:{{ $activity->getStatus()[2] }} "
                                           wire:click="$emitTo('poa.poa-show-activity', 'open', {{ $activity->id }})">
                                            {{ $activity->name }}
                                        </a>
                                    </td>
                                    <td>{{ $activity->poaActivityIndicator->where('period','<=',date("m"))->sum('goal') }}</td>
                                    @if($activity->poaActivityIndicator->where('period','<=',date("m"))->sum('goal')>0)
                                        <td>{{ $activity->progress }}
                                            <small>({{ number_format($activity->progress/$activity->poaActivityIndicator->where('period','<=',date("m"))->sum('goal')*100, 1)  }}
                                                %)</small></td>
                                    @else
                                        <td>{{ $activity->progress }} </td>
                                    @endif
                                    <td>{{ $activity->location->description ?? 'N/A' }} / {{ $activity->community }}</td>
                                    <td>{{ $activity->getStatus()[0] }} </td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
        {{--            <x-pagination :items="$poa"/>--}}
    </div>
</div>


<div wire:ignore>
    <livewire:poa.poa-show-activity/>
</div>

@push('page_script')
    <script>
        Livewire.on('toggleShowModal', () => $('#poa-show-activity-modal').modal('toggle'));

        Livewire.on('toggleDropDownFilter', () => $("#dropdown-filter").removeClass("show"));
    </script>
@endpush