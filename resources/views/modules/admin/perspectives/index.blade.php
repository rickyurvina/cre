@extends('layouts.admin')

@section('title', trans('general.perspective'))

@section('subheader')

    <h1 class="subheader-title">
        <i class="fal fa-balance-scale text-primary"></i> {{ trans('general.perspective') }}
    </h1>
    <div class="subheader-block d-lg-flex align-items-center">
        @can('admin-crud-admin')
            <a href="{{ route('perspectives.create') }}" class="btn btn-success btn-sm"><span class="fas fa-plus mr-1"></span> &nbsp;{{ trans('general.add_new') }}</a>
        @endcan
    </div>
@endsection
@section('content')


    <div class="card">
        <x-search route="{{ route('perspectives.index') }}"/>
        <div class="table-responsive">
            <table class="table m-0" id="table_units">
                <thead class="bg-primary-50">
                <tr>
                    <th class="d-none">@sortablelink('id', trans('general.id'))</th>
                    <th>@sortablelink('name', trans('general.name'))</th>
                    <th class="text-center color-primary-500">{{ trans('general.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($perspectives as $item)
                    <tr>
                        <th class="d-none"></th>
                        <td><a href="{{ route('perspectives.show', $item->id) }}">{{ $item->name }}</a></td>
                        <td class="text-center">
                            <form action="{{ route('perspectives.destroy',$item->id) }}" method="POST">
                                @can('admin-crud-admin')
                                    <a class="mr-2" href="{{ route('perspectives.edit', $item->id) }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar"><i
                                                class="fas fa-edit mr-1 text-info"></i>
                                    </a>
                                @endcan
                                @csrf
                                @method('DELETE')
                                @can('admin-crud-admin')
                                    <button class="mr-2 border" style="border: 0 !important; background-color: transparent !important;" type="submit"><i class="fas fa-trash mr-1 text-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar"></i></button>
                                @endcan
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
{{--        <x-pagination :items="$perspectives"/>--}}
    </div>
@endsection