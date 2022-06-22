<div>
    <div class="panel-container show" style="margin-top: -2%;">
        <div class="card-header pr-2 d-flex flex-wrap w-100">
            <div class="d-flex position-relative mr-auto w-75">
                <i class="spinner-border spinner-border-sm position-absolute pos-left mx-3" style="margin-top: 0.75rem" wire:target="search" wire:loading></i>
                <i class="fal fa-search position-absolute pos-left fs-lg mx-3" style="margin-top: 0.75rem" wire:loading.remove></i>
                <input type="text" wire:model.debounce.300ms="search" class="form-control bg-subtlelight pl-6"
                       placeholder="Buscar...">
            </div>
            @if($pageIndex==\App\Models\Process\Process::PHASE_CHECK)
                <div class="ml-auto w-20">
                    <a class="btn btn-success border-0 shadow-0 "
                       style="color:white;"
                       wire:click="$emit('show', 'App\\Models\\Process\\Process', '{{ $process->id }}')">
                        {{ trans('general.create') }} Indicador
                    </a>
                </div>
            @endif
        </div>
        @if($indicators->count()>0)
            <div class="card">
                <div class="table-responsive">
                    <table class="table  m-0">
                        <thead class="bg-primary-50">
                        <tr>
                            <th>@sortablelink('name', trans('general.name'))</th>
                            <th>@sortablelink('indicator_units_id', trans('general.indicator_unit'))</th>
                            <th>@sortablelink('total_goal_value', trans('general.goal'))</th>
                            <th>@sortablelink('total_actual_value', trans('general.advance'))</th>
                            <th class="text-primary">{{ trans('general.progress')}}</th>
                            <th class="text-primary">{{ trans('general.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($indicators as $indicator)
                            <tr wire:key="{{time().$indicator->code}}">
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
                                        @if($pageIndex==\App\Models\Process\Process::PHASE_CHECK)
                                            <a href="javascript:void(0);" class="mr-2" aria-expanded="false" data-toggle="tooltip" data-placement="top" title=""
                                               data-original-title="Editar"
                                               wire:click="$emitTo('indicators.indicator-edit', 'open', {{ $indicator->id }})"> <i
                                                        class="fas fa-edit mr-1 text-info"></i>
                                            </a>
                                            <div class="w-25 cursor-pointer"
                                                 wire:click="$emit('triggerDeleteIndicator', '{{ $indicator->id }}')">
                                                                            <span class="color-danger-700"><i
                                                                                        class="fas fa-trash-alt" aria-expanded="false"
                                                                                        data-toggle="tooltip" data-placement="top" title=""
                                                                                        data-original-title="Eliminar"></i></span>
                                            </div>
                                        @endif
                                    </div>
                                </th>
                            </tr>
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
</div>
<div wire:ignore>
    <div class="modal fade fade" id="indicator-show-modal" tabindex="-1" style="display: none;" role="dialog"
         aria-hidden="true">
        <livewire:indicators.indicator-show/>
    </div>
</div>
<div wire:ignore>
    <livewire:indicators.indicator-edit/>
</div>
<div wire:ignore>
    <livewire:indicators.indicator-create/>
</div>
@push('page_script')
    <script>
        Livewire.on('toggleIndicatorShowModal', () => $('#indicator-show-modal').modal('toggle'));
    </script>
    <script>
        Livewire.on('toggleIndicatorEditModal', () => $('#indicator-edit-modal').modal('toggle'));
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

        @this.on('triggerDeleteIndicator', id => {
            Swal.fire({
                title: '{{ trans('messages.warning.sure') }}',
                text: '{{ trans('messages.warning.delete') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                confirmButtonText: '<i class="fas fa-trash"></i> {{ trans('general.yes') . ', ' . trans('general.delete') }}',
                cancelButtonText: '<i class="fas fa-times"></i> {{ trans('general.no') . ', ' . trans('general.cancel') }}'
            }).then((result) => {
                //if user clicks on delete
                if (result.value) {
                    // calling destroy method to delete
                @this.call('deleteIndicator', id);
                }
            });
        });
        });
    </script>
@endpush
