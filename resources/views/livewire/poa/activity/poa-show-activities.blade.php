<div>
    <div class="d-flex mb-3">
        <div class="input-group bg-white shadow-inset-2 w-25 mr-2">
            <input type="text" class="form-control border-right-0 bg-transparent pr-0" placeholder="{{ trans('general.filter') . ' ' . trans_choice('general.activities', 2) }} ..."
                   wire:model="search">
            <div class="input-group-append">
                <span class="input-group-text bg-transparent border-left-0">
                    <i class="fal fa-search"></i>
                </span>
            </div>
        </div>

        @if(count($programs) > 0)
            <div class="btn-group">
                <button class="btn btn-outline-secondary dropdown-toggle @if(count($selectedPrograms) > 0) filtered @endif"
                        type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ trans_choice('general.programs', 2) }}
                    @if(count($selectedPrograms) > 0)
                        <span class="badge bg-white ml-2">{{ count($selectedPrograms) }}</span>
                    @endif
                </button>
                <div class="dropdown-menu">
                    @foreach($programs as $program)
                        <div class="dropdown-item">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="i-program-{{ $program['id'] }}" wire:model="selectedPrograms" value="{{ $program['id'] }}">
                                <label class="custom-control-label" for="i-program-{{ $program['id'] }}">{{ $program->planDetail->name }}</label>
                            </div>
                        </div>
                    @endforeach
                    <div class="dropdown-divider"></div>
                    <div class="dropdown-item">
                        <span wire:click="$set('selectedPrograms', [])">{{ trans('general.delete_selection') }}</span>
                    </div>
                </div>
            </div>
        @endif
        @if(count($selectedPrograms) > 0 || $search != '')
            <a href="javascript:void(0);" class="btn btn-outline-default ml-2" wire:click="clearFilters">{{ trans('common.clean_filters') }}</a>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table table-light table-hover">
            <thead>
            <tr>
                <th class="w-15">@sortablelink('planDetail.name', trans_choice('general.programs', 1))</th>
                <th class="w-15">@sortablelink('indicator.name', trans_choice('general.indicators', 1))</th>
                <th class="w-25">@sortablelink('name', __('poa.name'))</th>
                <th class="w-10">@sortablelink('poa_weight', __('general.weight'))</th>
                <th class="w-15">@sortablelink('responsible.name', __('general.responsible'))</th>
                <th class="w-10">@sortablelink('goal', __('general.goal'))</th>
                <th class="w-5"><a href="#">{{ trans('general.actions') }} </a></th>
            </tr>
            </thead>
            <tbody>
            @if(count($programs) > 0)
                @forelse($activities as $item)
                    <tr class="tr-hover" wire:loading.class.delay="opacity-50">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="w-35">
                                    <span class="color-item shadow-hover-5 mr-2 cursor-default" style="background-color: {{ $item->program->color }}"></span>

                                </div>
                                <div class="w-65">
                                    <span>{{ $item->planDetail->name }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $item->indicator->name }}</td>
                        <td><a href="{{ route('activities.show', ['activity' => $item->id]) }}">{{ $item->code ? $item->code . ' - ':'' }}{{ $item->name }}</a></td>
                        <td>{{ number_format($item->poa_weight, 2) }}</td>
                        <td>
                            @if($item->responsible)
                                <span class="mr-2">
                                    <img src="{{ asset_cdn('img/user.svg') }}" class="rounded-circle width-1">
                                </span>
                                {{ $item->responsible->name }}
                            @else
                                <span class="mr-2">
                                    <img src="{{ asset_cdn('img/user_off.png') }}" class="rounded-circle width-1">
                                </span>
                                {{ trans('general.not_assigned') }}
                            @endif
                        </td>
                        <td>
                            {{ $item->goal }}
                        </td>
                        <td>
                            <a href="{{ route('activities.show', ['activity' => $item->id]) }}" class="mr-2"
                            data-toggle="tooltip"
                            data-placement="top" title=""
                            data-original-title="{{ trans('general.show') }}">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="color-fusion-500 fs-3x py-3"><i class="fas fa-exclamation-triangle color-warning-900"></i> No se encontraron actividades</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            @else
                <tr>
                    <td colspan="7">
                        <div class="d-flex align-items-center justify-content-center">
                            <span class="color-fusion-500 fs-3x py-3"><i class="fas fa-exclamation-triangle color-warning-900"></i> Los idicadores del programa están desabilitados</span>
                        </div>
                    </td>
                </tr>
            @endif
            </tbody>

        </table>
    </div>
    <x-pagination :items="$activities"/>
</div>
