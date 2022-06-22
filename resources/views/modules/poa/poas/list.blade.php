@extends('layouts.admin')

@section('title', __('poa.list_poas'))

@section('subheader')
    <h1 class="subheader-title">
        <i class="fal fa-align-left text-primary"></i> {{ __('poa.list_poas') }}
    </h1>
    @if($poaExits < 2)
        @can('poa-crud-poa')
            <a href="{{ route('poa.create') }}" class="btn btn-success btn-sm">
                <span class="fas fa-plus mr-1"></span>
                {{ trans('general.add_new') }}
            </a>
        @endcan
    @endif
@endsection

@section('content')
    @if($poas->count()>0)
        <div class="table-responsive">
            <table class="table table-hover m-0">
                <thead class="bg-primary-50">
                <tr>
                    <th class="w-5 table-th">@sortablelink('year', __('poa.year'))</th>
                    <th class="w-25 table-th">@sortablelink('name', __('poa.name'))</th>
                    <th class="w-15 table-th">@sortablelink('responsible.name', __('poa.responsible'))</th>
                    <th class="w-10 table-th"><a href="#">{{ trans('general.phase') }}</th>
                    <th class="w-10 table-th"><a href="#">{{ trans('poa.status') }}</th>
                    <th class="w-10 table-th"><a href="#">{{ trans('poa.reviewed') }}</th>
                    <th class="w-10 table-th">@sortablelink('progress', __('poa.progress'))</th>
                    @can('poa-crud-poa')
                        <th class="w-15 table-th"><a href="#">{{ trans('general.actions') }}</a></th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @foreach($poas as $item)
                    <tr>
                        <td>{{ $item->year }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->responsible->name }}</td>
                        <td>
                            <span class="badge {{ $item->phase->color() }}">{{ $item->phase->label() }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $item->status->color() }} badge-pill">{{ $item->status->label() }}</span>
                        </td>
                        <td>
                            @if($item->reviewed)
                                <span class="badge badge-success badge-pill">{{ __('general.yes') }}</span>
                            @else
                                <span class="badge badge-danger badge-pill">{{ __('general.no') }}</span>
                            @endif
                        </td>
                        <td>{{ number_format($item->progress, 0, '.', ',') }} %</td>
                        @can('poa-crud-poa')
                            <td>
                                <span data-toggle="modal" data-target="#edit-modal-poa" data-id="{{ $item->id }}">
                                    <a class="mr-2" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title=""
                                       data-original-title="Editar">
                                        <i class="fas fa-edit mr-1 text-info"></i>
                                    </a>
                                </span>
                                <a class="mr-2" href="{{ route('poas.activities.index', ['poa' => $item->id]) }}"
                                   data-toggle="tooltip" data-placement="top" title=""
                                   data-original-title="Ver Actividades">
                                    <i class="fas fa-arrow-alt-right"></i>
                                </a>
                                <a class="mr-2" href="{{ route('poa.config', ['poaId' => $item->id]) }}"
                                   data-toggle="tooltip" data-placement="top" title=""
                                   data-original-title="{{trans('general.settings')}}">
                                    <i class="fas fa-cog text-success"></i>
                                </a>
                                @if($poaExits < 2)
                                    <a class="mr-2" href="{{ route('poa.replicate', ['poaId' => $item->id]) }}"
                                       data-toggle="tooltip" data-placement="top" title=""
                                       data-original-title="Duplicar POA"
                                    >
                                        <i class="fas fa-check-double text-secondary"></i>
                                    </a>
                                @endif
                                @if($item->status instanceof \App\States\Poa\InProgress)
                                    <a class="mr-2" href="{{ route('poa.goal_change_request', ['poaId' => $item->id]) }}"
                                       data-toggle="tooltip" data-placement="top" title=""
                                       data-original-title="Solicitudes de cambio de metas"
                                    >
                                        <i class="fas fa-file-signature text-danger"></i>
                                    </a>
                                @endif
                                {{--                            @can('delete-auth-users')--}}
                                {{--                            <x-delete-link action="{{ route('poas.destroy', $item->id) }}" id="{{ $item->id }}"/>--}}
                                {{--                            @endcan--}}

                            </td>
                        @endcan
                    </tr>
                @endforeach
                @else

                    <x-empty-content>
                        <x-slot name="title">
                            {{trans('general.there_are_no_poas')}}
                        </x-slot>
                    </x-empty-content>
                @endif
                </tbody>
            </table>
        </div>
        <x-pagination :items="$poas"/>

        <livewire:poa.poa-edit/>
        <livewire:poa.poa-approved/>
        <livewire:poa.poa-approved-edit/>
@endsection

@push('page_script')
    <script>
        $('#edit-modal-poa').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let poaId = $(e.relatedTarget).data('id');
            //Livewire event trigger
            Livewire.emit('loadForm', poaId);
        });

        $('#poa-approve-poa').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let poaId = $(e.relatedTarget).data('poa-id');
            //Livewire event trigger
            Livewire.emit('loadPoa', poaId);
        });

        $('#poa-approve-poa-edit').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let poaId = $(e.relatedTarget).data('poa-id');
            //Livewire event trigger
            Livewire.emit('loadPoa', poaId);
        });
    </script>
@endpush