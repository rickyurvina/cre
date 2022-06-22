<div>
    <ol class="breadcrumb bg-transparent breadcrumb-sm pl-0 pr-0">
        @foreach($planRegisteredTemplateDetailsBreadcrumbs as $item)
            <li class="breadcrumb-item {{ $item['link'] == '' ? 'active' : '' }}">
                @if($item['link'] == '')
                    @if($item['first']) <i class="fal fa-folder-open mr-1"></i> @endif <a class="fs-2x color-black"> {{ $item['name'] }}</a>
                @else
                    <a href="{{ $item['link'] }}" class="fs-2x">@if($item['first']) <i class="fal fa-folder-open mr-1"></i> @endif{{ $item['name'] }}</a>
                @endif
            </li>
        @endforeach
    </ol>
    <div>
        <div class="panel-hdr">
            @can('strategy-crud-strategy' || 'strategy-plan-crud-strategy')
                @if($planDetail->plan->status != \App\Models\Strategy\Plan::ARCHIVED)
                    <div class="panel-toolbar ml-auto">
                        <a href="#indicator-create-modal"
                           class="btn btn-success btn-sm"
                           data-toggle="modal"
                           data-target="#indicator-create-modal"
                           data-detail-id="{{$planDetailId}}"
                           data-detail-type="{{$type}}">
                            <span class="fas fa-plus mr-1"></span>
                            {{ trans('general.add_new') }}
                        </a>
                    </div>
                @endif
            @endcan
        </div>
        <div class="card-header">
            <div class="d-flex position-relative ml-auto w-100">
                <i class="spinner-border spinner-border-sm position-absolute pos-left mx-3" style="margin-top: 0.75rem"
                   wire:target="search" wire:loading></i>
                <i class="fal fa-search position-absolute pos-left fs-lg mx-3" style="margin-top: 0.75rem"
                   wire:loading.remove></i>
                <input type="text" wire:model.debounce.300ms="search" class="form-control bg-subtlelight pl-6"
                       placeholder="Buscar por nombre">
            </div>
        </div>
        <table class="table m-0">
            <thead class="bg-primary-50">
            <tr>
                <th>@sortablelink('code', trans('general.code'))</th>
                <th>@sortablelink('name', trans('general.name'))</th>
                <th>{{trans('general.type')}}</th>
                <th>@sortablelink('total_goal_value', trans('general.goal'))</th>
                @can('strategy-crud-strategy' || 'strategy-plan-crud-strategy')
                    <th class="text-center color-primary-500 w-15">{{ trans('general.actions') }}</th>
                @endcan
            </tr>
            </thead>
            <tbody>
            @foreach($indicators as $indicator)
                <tr>
                    <td># {{$indicator->code}}</td>
                    <td>{{$indicator->name}}</td>
                    <td>{{$indicator->category}}</td>
                    <td>{{$indicator->total_goal_value}}</td>
                    @can('strategy-crud-strategy' || 'strategy-plan-crud-strategy')
                        <td class="text-center">
                            @if($planDetail->plan->status != \App\Models\Strategy\Plan::ARCHIVED)
                                <a href="#indicator-edit-modal"
                                   class=""
                                   data-toggle="modal"
                                   data-target="#indicator-edit-modal"
                                   data-indicator-id="{{$indicator->id}}">
                                    <i class="fas fa-edit mr-1 text-info"
                                       data-toggle="tooltip" data-placement="top" title=""
                                       data-original-title="Editar"></i>
                                </a>
                                <x-delete-link-icon
                                        action="{{ route('indicators.destroy',$indicator->id) }}"
                                        id="{{ $indicator->id }}"></x-delete-link-icon>
                            @endif
                        </td>
                    @endcan
                </tr>
            @endforeach
            </tbody>
        </table>
        <x-pagination :items="$indicators"/>
    </div>

    <div wire:ignore.self>
        <livewire:indicators.indicator-create/>
        <livewire:indicators.indicator-edit/>
    </div>

</div>

@push('page_script')
    <script>
        $('#indicator-create-modal').on('show.bs.modal', function (e) {
            let model = "App\\Models\\Strategy\\PlanDetail";
            let id = $(e.relatedTarget).data('detail-id');
            let type = $(e.relatedTarget).data('detail-type');
            Livewire.emit('show', model, id, type);
        });
        $('#indicator-edit-modal').on('show.bs.modal', function (e) {
            let indicatorId = $(e.relatedTarget).data('indicator-id');
            Livewire.emit('openEditModal', indicatorId);
        });
    </script>
@endpush
