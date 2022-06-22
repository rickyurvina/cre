<div>
    <div class="panel-container show" style="margin-top: -2%;">
        <div class="card-header pr-2 d-flex flex-wrap w-100">
            <div class="d-flex position-relative mr-auto w-75">
                <i class="spinner-border spinner-border-sm position-absolute pos-left mx-3" style="margin-top: 0.75rem" wire:target="search" wire:loading></i>
                <i class="fal fa-search position-absolute pos-left fs-lg mx-3" style="margin-top: 0.75rem" wire:loading.remove></i>
                <input type="text" wire:model.debounce.300ms="search" class="form-control bg-subtlelight pl-6"
                       placeholder="Buscar...">
            </div>
            <div class="ml-auto w-20">
                <button type="button" class="btn btn-success border-0 shadow-0 ml-2" data-toggle="modal"
                        data-target="#plan-create-activity">{{ trans('general.create')}} {{trans('general.activity')}}
                </button>
            </div>
        </div>
        @if($activities->count()>0)
            <div class="card">
                <div class="table-responsive">
                    <table class="table  m-0">
                        <thead class="bg-primary-50">
                        <tr>
                            <th class="w-10 table-th text-primary">
                                <a wire:click.prevent="sortBy('code')" role="button" href="#">
                                    {{trans('general.code')}}
                                    <x-sort-icon sortDirection="{{$sortDirection}}" sortField="code"
                                                 field="{{$sortField}}"></x-sort-icon>
                                </a>
                            </th>
                            <th class="w-20 table-th text-primary">
                                <a wire:click.prevent="sortBy('name')" role="button" href="#">
                                    {{__('general.name')}}
                                    <x-sort-icon sortDirection="{{$sortDirection}}" sortField="name"
                                                 field="{{$sortField}}"></x-sort-icon>
                                </a></th>
                            <th class="w-30 table-th text-primary">
                                <a wire:click.prevent="sortBy('expected_result')" role="button" href="#">
                                    {{trans('general.expected_result')}}
                                    <x-sort-icon sortDirection="{{$sortDirection}}" sortField="expected_result"
                                                 field="{{$sortField}}"></x-sort-icon>
                                </a>
                            </th>
                            <th class="w-10 table-th text-primary">{{trans_choice('general.risks',2)}}</th>
                            <th class="w-10 table-th text-primary"><a href="#">{{ trans('general.actions') }} </a></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($activities as $item)
                            <tr class="tr-hover" wire:ignore wire:key="{{time().$item->id}}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{$item->code }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{$item->name}}
                                    </div>
                                </td>
                                <td>
                                    {{$item->expected_result}}
                                </td>
                                <td>
                                    {{$item->risks->count()}}
                                </td>
                                <td>
                                    <a href="{{route('processActivity.edit',[$item->id,$subMenu, $pageIndex])}}"
                                       data-toggle="tooltip" data-placement="top" title=""
                                       data-original-title="Editar">
                                        <i class="fas fa-edit mr-1 text-info"
                                        ></i>
                                    </a>
                                    <x-delete-link action="{{ route('activityProcess.delete', [$item->id,$pageIndex]) }}"
                                                   id="{{ $item->id }}"/>
                                </td>
                        @endforeach
                        </tbody>
                    </table>
                    <x-pagination :items="$activities"/>
                </div>
            </div>
        @else
            <x-empty-content>
                <x-slot name="title">
                    {{ trans('general.there_are_no_activities') }}
                </x-slot>
            </x-empty-content>
        @endif
    </div>
</div>

<div wire:ignore>
    <livewire:process.activities.update-activity/>
</div>
@push('page_script')
    <script>
        $('#edit-process-activity').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let id = $(e.relatedTarget).data('item-id');
            //Livewire event trigger
            Livewire.emit('openEditActivity', id);
        });
        Livewire.on('toggleEditActivity', () => $('#edit-process-activity').modal('toggle'));
        Livewire.on('toggleCreateActivity', () => $('#plan-create-activity').modal('toggle'));

    </script>
@endpush
