<div>
    <div class="panel-container show" style="margin-top: -2%;">
        @if($existIndicators)
            <div class="row" style="height: 3rem">
                <div class="frame-wrap">
                    <div class="demo demo-h-spacing">
                        <div class="btn-group">
                            <button type="button" class="btn btn-info" style="background-color: #0046AD">  {{trans('common.filters')}} <i class="fal fa-filter"></i></button>
                            <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split" style="background-color: #0046AD" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                <span class="sr-only"></span>
                            </button>
                            <div wire:ignore.self class="dropdown-menu dropdown-xl" id="dropdown-filter" style="width: 50rem !important;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 class="dropdown-item">  {{trans_choice('general.plan',0)}}</h3>
                                        <div class="dropdown-divider"></div>
                                        <div class="dropdown-item">
                                            <div class="frame-wrap">
                                                <div class="demo">
                                                    @foreach($plansFilter as $plan)
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="defaultUnchecked1.{{$plan->id}}" value="{{$plan->id}}"
                                                                   wire:model="selectedPlans.{{$plan->id}}">
                                                            <label class="custom-control-label"
                                                                   for="defaultUnchecked1.{{$plan->id}}">{{ strlen($plan->name)>25?substr($plan->name,0,25)."...": $plan->name  }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h3 class="dropdown-item"> Elementos con Indicadores</h3>
                                        <div class="dropdown-divider"></div>
                                        <div class="dropdown-item">
                                            <div class="frame-wrap">
                                                <div class="demo">
                                                    @if($listOfObjectives)
                                                        @forelse($listOfObjectives as $objective)
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="defaultUnchecked.{{$objective->id}}"
                                                                       value="{{$objective->id}}"
                                                                       wire:model="selectedObjectives.{{$objective->id}}">
                                                                <label class="custom-control-label"
                                                                       for="defaultUnchecked.{{$objective->id}}">{{ strlen($objective->name)>25?substr($objective->name,0,25)."...": $objective->name  }}</label>
                                                            </div>
                                                        @empty
                                                            <div>
                                                                Vacio
                                                            </div>
                                                        @endforelse
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-divider"></div>
                                        <div class="dropdown-item">
                                            <button type="button" class="btn btn-lg btn-block btn-outline-info waves-effect waves-themed" style="margin-top: 7%;"
                                                    wire:click="filter()"><span class="fal fa-filter mr-1"></span> {{trans('general.close')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin-left: 1%;">
                    <button type="button" class="btn btn-light" style="background-color: #D52B1E; color: white;"
                            onClick="window.location.reload();">  {{trans('common.clean_filters')}}</button>
                </div>
            </div>
            <div class="card-header pr-2 d-flex align-items-center flex-wrap">
                <div class="d-flex position-relative ml-auto w-100">
                    <i class="spinner-border spinner-border-sm position-absolute pos-left mx-3" style="margin-top: 0.75rem" wire:target="search" wire:loading></i>
                    <i class="fal fa-search position-absolute pos-left fs-lg mx-3" style="margin-top: 0.75rem" wire:loading.remove></i>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control bg-subtlelight pl-6"
                           placeholder="Buscar por nombre del indicador...">
                </div>
            </div>
            <div class="card">
                <div class="table-responsive">
                    <table class="table  m-0">
                        <thead class="bg-primary-50">
                        <tr>
                            <th class="text-primary">{{ trans('general.name')}}</th>
                            <th class="text-primary">{{ trans('general.indicator_unit')}}</th>
                            <th class="text-primary">{{ trans('general.goal')}}</th>
                            <th class="text-primary">{{ trans('general.advance')}}</th>
                            <th class="text-primary">{{ trans('general.progress')}}</th>
                            <th class="text-primary">{{ trans('general.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($plans as $plan)
                            @foreach($plan->planDetails as $planDetail)
                                @if(count($planDetail->indicators)>0)
                                    <tr style="background-color: #e8ecec" class="text-center">
                                        <td colspan="6" style="font-size: 17px; font-weight: 600">{{$planDetail->name}}-{{$planDetail->plan->name}}</td>
                                    </tr>
                                    @foreach($planDetail->indicators as $indicator)
                                        <tr>
                                            <td>{{ $indicator->name }}</td>
                                            <td>  {{ $indicator->indicatorUnit->name }}</td>
                                            <td>{{ $indicator->total_goal_value>0 ?  $indicator->total_goal_value : '0.00'}}</td>
                                            <td>{{ $indicator->total_actual_value>0 ?  $indicator->total_actual_value : '0.00'}}</td>
                                            <td>
                                                <span class="form-label badge {{$indicator->getStateIndicator()[0]?? null}}  badge-pill">{{$indicator->getStateIndicator()[1]?? null}}</span>
                                            </td>
                                            <th class="w-10 text-center table-th">
                                                <div class="d-flex flex-wrap"
                                                     wire:key="{{ 'r.i.' . $loop->index }}">
                                                    <div class="w-25 cursor-pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver"
                                                         wire:click="$emitTo('indicators.indicator-show', 'open', {{ $indicator->id }})">
                                                                            <span class="color-info-700"><i
                                                                                        class="far fa-eye"></i></span>
                                                    </div>
                                                    <a href="javascript:void(0);" class="mr-2" aria-expanded="false" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar"
                                                       wire:click="$emitTo('indicators.indicator-edit', 'open', {{ $indicator->id }})"> <i
                                                                class="fas fa-edit mr-1 text-info"></i>
                                                    </a>
                                                </div>
                                            </th>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <x-empty-content>
                <x-slot name="title">
                    No existen indicadores asociados
                </x-slot>
            </x-empty-content>

        @endif
    </div>
    <div wire:ignore.self>
        <div class="modal fade fade" id="indicator-show-modal" tabindex="-1" style="display: none;" role="dialog"
             aria-hidden="true">
            <livewire:indicators.indicator-show/>
        </div>
    </div>
    <div wire:ignore.self>
        <livewire:indicators.indicator-edit/>
    </div>
</div>

@push('page_script')
    <script>
        Livewire.on('toggleDropDownFilter', () => $("#dropdown-filter").removeClass("show"));
    </script>
    <script>
        Livewire.on('toggleIndicatorShowModal', () => $('#indicator-show-modal').modal('toggle'));
    </script>
    <script>
        Livewire.on('toggleIndicatorEditModal', () => $('#indicator-edit-modal').modal('toggle'));

    </script>

@endpush
