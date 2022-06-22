<div>
    <div class="row mb-1">
        <h1 class="mr-3">
            <i class="subheader-icon fal fa-list-ul"></i> <span class="fw-300">Proyectos</span>
        </h1>
        @if($projects->count()>0)

            <div class="mr-3">
                <button class="btn btn-primary btn-sm" wire:click="verifyVisibility"><i
                            class="fas fa-list fa-lg"></i>
                </button>
                <button class="btn btn-primary btn-sm" wire:click="verifyVisibility"><i class="fas fa-th fa-lg"></i>
                </button>
            </div>
        @endif
        <div class="subheader-block d-lg-flex align-items-center ml-auto">
            @can('project-crud-project')
                <livewire:projects.c-r-u-d.create-project/>
            @endcan
        </div>
    </div>
    @if($projects->count()>0)
        <div class="card-header mb-2">
            <div class="d-flex position-relative ml-auto w-100">
                <i class="spinner-border spinner-border-sm position-absolute pos-left mx-3"
                   style="margin-top: 0.75rem"
                   wire:target="search" wire:loading></i>
                <i class="fal fa-search position-absolute pos-left fs-lg mx-3" style="margin-top: 0.75rem"
                   wire:loading.remove></i>
                <input type="text" wire:model.debounce.300ms="search" class="form-control bg-subtlelight pl-6"
                       placeholder="Buscar por Nombre">
            </div>
        </div>
        @if(!$cardView)
            <div class="row">
                @foreach($types as $type)
                    <div class="col-3">
                        <div class="card border mb-g">
                            <div class="card-header pr-3 d-flex align-items-center flex-wrap"
                                 style="background-color: #3955bc">
                                <div class="card-title color-white">{{trans('general.'.$type)}}</div>
                            </div>
                            <div class="card-body">
                                @can('project-crud-project'||'project-view-project')
                                    @foreach($projects->where('type',$type) as $item)
                                        <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                                            <a href="{{ route('projects.showSummary', $item->id) }}"
                                               class="card border border-info shadow-hover-5">
                                                <div class="card-header border-0 pb-0 bg-white d-flex align-items-center flex-wrap">
                                                    <div class="card-title">
                                                <span>
                                                    @if (is_object($item->picture))
                                                        <img src="{{ Storage::url($item->picture->id) }}"
                                                             class="rounded-circle width-2" alt="{{ $item->name }}">
                                                    @else
                                                        <img src="{{ asset_cdn("img/user.svg") }}"
                                                             class="rounded-circle width-2" alt="{{ $item->name }}">
                                                    @endif
                                                </span>
                                                    </div>
                                                    <div class="ml-auto">
                                                        <span class="badge {{ $item->phase->color() }}">{{ $item->phase->label() }}</span>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <span class="fs-xl font-weight-bolder color-black">{{ $item->name }}</span>
                                                    <div class="progress progress-xs mt-3">
                                                        <div class="progress-bar bg-danger-300 bg-warning-gradient"
                                                             role="progressbar"
                                                             style="width: {{ intval($item->tasks->where('parent','root')->first()->progress*100)}}%"
                                                             aria-valuenow="30"
                                                             aria-valuemin="0"
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <div class="profile-image-group mt-4">
                                                        @foreach($item->members->take(2) as $member)
                                                            @if (is_object($member->user->picture))
                                                                <div class="img-item rounded-circle">
                                                                    @if($loop->iteration == 2 && $item->members->count() > 2)
                                                                        <span data-hasmore="+{{ $item->members->count() - 2 }}"
                                                                              class="profile-image-md rounded-circle">
                                                                            <img src="{{ Storage::url($member->user->picture->id) }}"
                                                                                 class="profile-image-md"
                                                                                 alt="{{ $member->user->getFullName()  }}">
                                                                        </span>
                                                                    @else
                                                                        <img src="{{ Storage::url($member->user->picture->id) }}"
                                                                             class="profile-image-md"
                                                                             alt="{{ $member->user->getFullName()  }}">
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <div class="img-item rounded-circle">
                                                                    @if($loop->iteration == 2 && $item->members->count() > 2)
                                                                        <span data-hasmore="+{{ $item->members->count() - 2 }}"
                                                                              class="profile-image-md rounded-circle">
                                                                    <img src="{{ asset_cdn("img/user.svg") }}"
                                                                         class="profile-image-md"
                                                                         alt="{{ $member->user->getFullName() }}">
                                                                </span>
                                                                    @else
                                                                        <img src="{{ asset_cdn("img/user.svg") }}"
                                                                             class="profile-image-md"
                                                                             alt="{{ $member->user->getFullName() }}">
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="table-responsive">
                <table class="table m-0">
                    <thead class="bg-primary-50">
                    <tr>
                        <th class="w-5">@sortablelink('code', trans('general.code'))</th>
                        <th class="w-15 text-truncate-lg text-truncate-md text-truncate-xs">@sortablelink('name',
                            trans('general.name'))
                        </th>
                        <th class="text-center color-primary-500">{{ trans('general.referential_budget') }}</th>
                        <th class="text-center color-primary-500">Unidad Ejecutora</th>
                        <th class="text-center color-primary-500">{{ trans('general.type') }}</th>
                        <th class="text-center color-primary-500">{{ trans('general.responsible') }}</th>
                        <th class="text-center color-primary-500">{{ trans('general.location') }}</th>
                        <th class="text-center color-primary-500">{{ trans('general.start_date') }}</th>
                        <th class="text-center color-primary-500">{{ trans('general.end_date') }}</th>
                        <th class="text-center color-primary-500">{{ trans('general.phase') }}</th>
                        <th class="text-center color-primary-500">{{ trans('general.status') }}</th>
                        <th class="text-center color-primary-500 w-20">{{ trans('general.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($projects as $item)

                        @if(in_array($companyActive,$item->subsidiaries->pluck('company_id')->toArray()) )
                            <tr>
                                <td>{{$item->code}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{'$'.number_format($item->estimated_amount, 2) ??'$0.00'}}</td>
                                <td class="text-center"><i class="fal fa-minus color-danger-700 fs-2x"></i></td>
                                <td>{{trans('general.'.$item->type)}}</td>
                                @isset($item->responsible->name)
                                    <td>{{ $item->responsible->getFullName() ??''}}</td>
                                @else
                                    <td class="text-center"><i class="fal fa-minus color-danger-700 fs-2x"></i></td>
                                @endisset
                                @if($item->locations->count()>0)
                                    <td>

                                        {{$item->locations->first() ? $item->locations->first()->getPath() :''}}
                                    </td>
                                @else
                                    <td class="text-center"><i class="fal fa-minus color-danger-700 fs-2x"></i></td>

                                @endif
                                <td>{{$item->start_date}}</td>
                                <td>{{$item->end_date}}</td>
                                <td>
                                    <span class="badge {{ $item->phase->color() }}">{{ $item->phase->label() }}</span>
                                </td>
                                @if($item->type!=\App\Models\Projects\Project::TYPE_INTERNAL_DEVELOPMENT)
                                    <td>
                                        <span class="badge {{ $item->status->color() }} badge-pill">{{ $item->status->label() }}</span>
                                    </td>
                                @else
                                    <td class="text-center"><i class="fal fa-minus color-danger-700 fs-2x"></i></td>
                                @endif
                                @can('project-crud-project')
                                    <td>
                                        <div class="d-flex flex-lg-fill w-100">
                                            @if($item->company_id===$companyActive)
                                                @if($item->type == \App\Models\Projects\Project::TYPE_MISSIONARY_PROJECT || $item->type == \App\Models\Projects\Project::TYPE_EMERGENCY)
                                                    @if($item->phase instanceof \App\States\Project\StartUp)
                                                        @can('view-indexCard-project'||'manage-indexCard-project')
                                                            <div class="w-10 p-3 text-center mr-1">
                                                                <a href="{{ route('projects.showIndex', $item->id) }}"
                                                                   aria-expanded="false"
                                                                   data-toggle="tooltip" data-placement="top" title=""
                                                                   data-original-title="Ficha del Proyecto">
                                                                    <i class="fas fa-eye text-info"></i>
                                                                </a>
                                                            </div>

                                                        @endcan
                                                    @else
                                                        <div class="w-10 p-3 text-center mr-1">
                                                            <a href="{{ route('projects.activities_results', $item->id) }}"
                                                               aria-expanded="false"
                                                               data-toggle="tooltip" data-placement="top" title=""
                                                               data-original-title="Actividades">
                                                                <i class="fas fa-arrow-alt-from-top text-info"></i>
                                                            </a>
                                                        </div>

                                                        <div class="w-10 p-3 text-center mr-1">
                                                            <a href="{{ route('projects.activities', $item->id) }}"
                                                               aria-expanded="false"
                                                               data-toggle="tooltip" data-placement="top" title=""
                                                               data-original-title=Cronograma>
                                                                <i class="fas fa-analytics text-info"></i>
                                                            </a>
                                                        </div>

                                                        <div class="w-10 p-3 text-center mr-1">
                                                            <a href="{{ route('projects.showReferentialBudget', $item->id) }}"
                                                               aria-expanded="false"
                                                               data-toggle="tooltip" data-placement="top" title=""
                                                               data-original-title="Presupuesto del Proyecto">
                                                                <i class="fas fa-dollar-sign text-info"></i>
                                                            </a>
                                                        </div>

                                                    @endif
                                                    <div class="w-10 p-3 text-center mr-1">
                                                        <a href="{{ route('projects.logic-frame', $item->id) }}"
                                                           aria-expanded="false"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="Marco Lógico">
                                                            <i class="fas fa-file-archive text-info"></i>
                                                        </a>
                                                    </div>

                                                    <div class="w-10 p-3 text-center mr-1">
                                                        <a href="{{ route('projects.showSummary', $item->id) }}"
                                                           aria-expanded="false"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="Resumen del Proyeccto">
                                                            <i class="fas fa-folder-open text-info"></i>
                                                        </a>
                                                    </div>

                                                    <div class="w-10 p-3 text-center mr-1">
                                                        <a href="{{ route('projects.files', $item->id) }}"
                                                           aria-expanded="false"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="Archivos">
                                                            <i class="fas fa-paperclip text-info"></i>
                                                        </a>
                                                    </div>

                                                    <div class="w-10 p-3 text-center mr-1">
                                                        <button class="border-0 bg-transparent"
                                                                wire:click="$emit('deleteProject', '{{ $item->id }}')"
                                                                data-toggle="tooltip"
                                                                data-placement="top" title="Eliminar"
                                                                data-original-title="Eliminar"><i
                                                                    class="fas fa-trash text-danger"></i>
                                                        </button>
                                                    </div>

                                                @else
                                                    @can('view-indexCard-project'||'manage-indexCard-project')
                                                        <div class="w-10 p-3 text-center mr-1">
                                                            <a href="{{ route('projects.showIndexInternal', $item->id) }}"

                                                               aria-expanded="false"
                                                               data-toggle="tooltip" data-placement="top" title=""
                                                               data-original-title="Ficha del Proyecto">
                                                                <i class="fas fa-eye text-info"></i>
                                                            </a>
                                                        </div>

                                                    @endcan
                                                    <div class="w-10 p-3 text-center mr-1">
                                                        <a href="{{ route('projects.activities_resultsInternal', $item->id) }}"
                                                           aria-expanded="false"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="Actividades">
                                                            <i class="fas fa-arrow-alt-from-top text-info"></i>
                                                        </a>
                                                    </div>

                                                    <div class="w-10 p-3 text-center mr-1">
                                                        <a href="{{ route('projects.showReferentialBudgetInternal', $item->id) }}"
                                                           aria-expanded="false"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="Presupuesto del Proyecto">
                                                            <i class="fas fa-dollar-sign text-info"></i>
                                                        </a>
                                                    </div>

                                                    <div class="w-10 p-3 text-center mr-1">
                                                        <a href="{{ route('projects.showSummaryInternal', $item->id) }}"
                                                           aria-expanded="false"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="Resumen del Proyeccto">
                                                            <i class="fas fa-folder-open  text-info"></i>
                                                        </a>
                                                    </div>

                                                    <div class="w-10 p-3 text-center mr-1">
                                                        <a href="{{ route('projects.filesInternal', $item->id) }}"
                                                           aria-expanded="false"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="Archivos">
                                                            <i class="fas fa-paperclip text-info"></i>
                                                        </a>
                                                    </div>

                                                    <div class="w-10 p-3 text-center mr-1">
                                                        <button class="border-0 bg-transparent"
                                                                wire:click="$emit('deleteProject', '{{ $item->id }}')"
                                                                data-toggle="tooltip"
                                                                data-placement="top" title="Eliminar"
                                                                data-original-title="Eliminar"><i
                                                                    class="fas fa-trash text-danger"></i>
                                                        </button>
                                                    </div>

                                                @endif
                                            @else
                                                @if($item->phase instanceof \App\States\Project\Implementation && $item->status instanceof \App\States\Project\Execution)
                                                    <div class="w-10 p-3 text-center mr-1">
                                                        <a href="{{ route('projects.activities_results', $item->id) }}"
                                                           aria-expanded="false"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="Actividades">
                                                            <i class="fas fa-arrow-alt-from-top text-info"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                @endcan
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>

            </div>
        @endif
    @else
        <x-empty-content>
            <x-slot name="title">
                No existen proyectos creados
            </x-slot>
        </x-empty-content>

    @endif
</div>

@push('page_script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        @this.on('deleteProject', id => {
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
        });
        })
    </script>
@endpush