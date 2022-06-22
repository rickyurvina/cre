@extends('modules.project.project')

@section('project-page')
    @can('view-administrativeTasks-project'||'manage-administrativeTasks-project')
        <div class="w-100">
            @can('manage-administrativeTasks-project')
                <div class="d-flex flex-row-reverse">
                    <button type="button" class="btn btn-success btn-sm mb-2 mr-2 ml-auto waves-effect waves-themed"
                            data-toggle="modal"
                            data-target="#project-create-administrative-task"><i
                                class="fas fa-plus mr-1"></i>{{ trans('general.create')}} Actividad
                    </button>
                </div>
            @endcan
            <div class="table-responsive m-3 p-2">
                <table class="table table-light table-hover">
                    <thead>
                    <tr>
                        <th class="w-20 table-th">{{__('general.name')}}</th>
                        <th class="w-20 table-th">{{__('general.responsable')}}</th>
                        <th class="w-20 table-th">Estado</th>
                        <th class="w-10 table-th">Prioridad</th>
                        <th class="w-10 table-th">{{__('general.end_date')}}</th>
                        <th class="w-10 table-th"><a href="#">{{ trans('general.actions') }} </a></th>
                    </tr>
                    </thead>
                    <tbody class="m-3 p-2">
                    @foreach($administrativeTasks as $item)
                        <tr class="tr-hover" wire:loading.class.delay="opacity-50">
                            <td>
                                <div class="d-flex align-items-center">
                                    {{$item->name }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->responsible)
                                        {{$item->responsible->getFullName() }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge {{\App\Models\AdministrativeTasks\AdministrativeTask::STATUSES_BG[$item->status]}} badge-pill">{{$item->status }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge {{\App\Models\AdministrativeTasks\AdministrativeTask::PRIORITIES_BG[$item->priority]}} badge-pill">{{$item->priority }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{$item->end_date}}
                                </div>
                            </td>
                            <td>
                                @can('manage-administrativeTasks-project')
                                    <a href="javascript:void(0)"
                                       data-toggle="modal"
                                       data-target="#project-edit-administrative-task"
                                       data-item-id="{{$item->id}}">
                                        <i class="fas fa-edit mr-1 text-info"
                                           data-toggle="tooltip" data-placement="top" title=""
                                           data-original-title="Editar"></i>
                                    </a>
                                    <x-delete-link action="{{ route('admintask.delete', $item->id) }}" id="{{ $item->id }}"/>
                                @endcan
                            </td>

                    @endforeach
                    </tbody>
                </table>
            </div>
            @can('manage-administrativeTasks-project')
                <livewire:administrative-tasks.create-administrative-task :project="$project"/>
                <div wire:ignore>
                    <livewire:administrative-tasks.edit-administrative-task :project="$project"/>
                </div>
            @endcan
        </div>
    @endcan
@endsection
@push('page_script')
    <script>
        $('#project-edit-administrative-task').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let id = $(e.relatedTarget).data('item-id');
            //Livewire event trigger
            Livewire.emit('openEditAdminTask', id);
        });
    </script>
@endpush