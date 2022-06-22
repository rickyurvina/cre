<div>
    <div class="panel-container show">
        <div class="card-header pr-2 d-flex flex-wrap w-100">
            <div class="d-flex position-relative mr-auto w-100">
                <i class="spinner-border spinner-border-sm position-absolute pos-left mx-3" style="margin-top: 0.75rem" wire:target="search" wire:loading></i>
                <i class="fal fa-search position-absolute pos-left fs-lg mx-3" style="margin-top: 0.75rem" wire:loading.remove></i>
                <input type="text" wire:model.debounce.300ms="search" class="form-control bg-subtlelight pl-6"
                       placeholder="Buscar...">
            </div>
        </div>
        @if($processes->count()>0)
            <div class="card">
                <div class="table-responsive">
                    <table class="table  m-0">
                        <thead class="bg-primary-50">
                        <tr>
                            <th class="w-10">
                                <a wire:click.prevent="sortBy('code')" role="button" href="#">
                                    {{trans('general.code')}}
                                    <x-sort-icon sortDirection="{{$sortDirection}}" sortField="code"
                                                 field="{{$sortField}}"></x-sort-icon>
                                </a>
                            </th>
                            <th class="w-20">
                                <a wire:click.prevent="sortBy('name')" role="button" href="#">
                                    {{trans('general.name')}}
                                    <x-sort-icon sortDirection="{{$sortDirection}}" sortField="name"
                                                 field="{{$sortField}}"></x-sort-icon>
                                </a>
                            </th>
                            <th class="w-20">
                                <a wire:click.prevent="sortBy('owner_id')" role="button" href="#">
                                    Dueño del proceso
                                    <x-sort-icon sortDirection="{{$sortDirection}}" sortField="owner_id"
                                                 field="{{$sortField}}"></x-sort-icon>
                                </a>
                            </th>
                            <th class="color-primary-500 w-10">{{trans_choice('general.department',1)}}</th>
                            <th class="color-primary-500 w-10">{{trans_choice('general.indicators',2)}}</th>
                            <th class="color-primary-500 w-10">{{trans('general.nonconformities')}}</th>
                            <th class="color-primary-500 w-10">{{trans_choice('general.activities',2)}}</th>
                            <th class="color-primary-500">{{ trans('general.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($processes as $index=>$item)
                            <tr wire:key="{{time().$index}}">
                                <td class=>{{$item->code}}</td>
                                <td class=>{{$item->name}}</td>
                                <td class=>{{$item->owner ? $item->owner->getFullName() :'-'}}</td>
                                <td class=>{{$item->department_id ? $item->department->name :'-'}}</td>
                                <td class=>{{$item->indicators->count()}}</td>
                                <td class=>{{$item->nonConformities->count()}}</td>
                                <td class=>{{$item->activitiesProcess->count()}}</td>
                                <td class=>

                                    <div class="d-flex flex-lg-fill w-100">
                                        <div class="w-10 p-3 mr-1">
                                            <a href="{{ route('process.showInformation', [$item->id, \App\Models\Process\Process::PHASE_PLAN]) }}"
                                               aria-expanded="false"
                                               data-toggle="tooltip" data-placement="top" title=""
                                               data-original-title=" {{trans('process.plan')}}"><i
                                                        class="fas fa-calendar-check text-success ml-2"></i> </a>
                                        </div>
                                        <div class="w-10 p-3 mr-1">
                                            <a href="{{ route('process.showInformation', [$item->id, \App\Models\Process\Process::PHASE_ACT]) }}"
                                               aria-expanded="false"
                                               data-toggle="tooltip" data-placement="top" title=""
                                               data-original-title=" {{trans('process.act')}}"><i
                                                        class="fas fa-book-open text-info ml-2"></i></a>
                                        </div>
                                        <div class="w-10 p-3 mr-1">
                                            <a href="{{ route('process.showInformation', [$item->id, \App\Models\Process\Process::PHASE_DO_PROCESS]) }}"
                                               aria-expanded="false"
                                               data-toggle="tooltip" data-placement="top" title=""
                                               data-original-title=" {{trans('process.do')}}"><i
                                                        class="fas fa-pen-alt text-warning ml-2"></i></a>
                                        </div>
                                        <div class="w-10 p-3 mr-1">
                                            <a href="{{ route('process.showInformation', [$item->id, \App\Models\Process\Process::PHASE_CHECK]) }}"
                                               aria-expanded="false"
                                               data-toggle="tooltip" data-placement="top" title=""
                                               data-original-title="{{trans('process.check')}}"> <i
                                                        class="fas fa-ballot-check text-dark ml-2"></i></a>
                                        </div>
                                        <div class="w-10 p-3 mr-1">
                                            <a
                                                    href="javascript:void(0)"
                                                    data-toggle="modal"
                                                    data-target="#update-process-modal"
                                                    data-item-id="{{$item->id}}">
                                                <i class="fas fa-edit ml-2 text-info"
                                                   aria-expanded="false"
                                                   data-toggle="tooltip" data-placement="top" title=""
                                                   data-original-title="{{trans('general.edit')}}"></i>
                                            </a>
                                        </div>
                                        @if($item->activitiesProcess->count()<1 && $item->indicators->count()<1)
                                            <div class="w-10 mt-2">
                                                <x-delete-link-livewire id="{{ $item->id }}"/>
                                            </div>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <x-pagination :items="$processes"/>
                </div>
            </div>
        @else
            <x-empty-content>
                <x-slot name="title">
                    No existen procesos
                </x-slot>
            </x-empty-content>
        @endif
    </div>
</div>
<div wire:ignore>
    <livewire:process.update-process/>
</div>
@push('page_script')
    <script>
        Livewire.on('toggleCreateProcess', () => $('#create-process-modal').modal('toggle'));
        Livewire.on('toggleUpdateProcess', () => $('#update-process-modal').modal('toggle'));
        $('#update-process-modal').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let id = $(e.relatedTarget).data('item-id');
            //Livewire event trigger
            Livewire.emit('openEditProcess', id);
        });
    </script>
    <script>
        function deleteModel(id) {
            Swal.fire({
                title: '{{ trans('messages.warning.sure') }}',
                text: '{{ trans('messages.warning.delete') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                confirmButtonText: '<i class="fas fa-trash"></i> {{ trans('general.yes') . ', ' . trans('general.delete') }}',
                cancelButtonText: '<i class="fas fa-times"></i> {{ trans('general.no') . ', ' . trans('general.cancel') }}'
            }).then((result) => {
                if (result.value) {
                @this.call('delete', id);
                }
            });
        }
    </script>
@endpush