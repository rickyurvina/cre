@extends('layouts.admin')
@inject('IndicatorSource','\App\Models\Indicators\Sources\IndicatorSource')
@section('title', trans_choice('general.sources', 2))

@section('subheader')
    <h1 class="subheader-title">
        <i class="fal fa-clipboard-check text-primary"></i> {{ trans_choice('general.sources', 2) }}
    </h1>
    @can('admin-crud-admin')
        <a href="{{ route('indicator_sources.create') }}" class="btn btn-success btn-sm"><span class="fas fa-plus mr-1"></span> &nbsp;{{ trans('general.add_new') }}</a>
    @endcan
@endsection

@section('content')


    <div class="card">
        <x-search route="{{ route('indicator_sources.index') }}"/>
        <div class="table-responsive">
            <table class="table m-0" id="table_sources">
                <thead class="bg-primary-50">
                <tr>
                    <th class="d-none">@sortablelink('id', trans('general.id'))</th>
                    <th>@sortablelink('name', trans('general.name'))</th>
                    <th>@sortablelink('institution', trans('general.institution'))</th>
                    <th>@sortablelink('description', trans('general.description'))</th>
                    <th>@sortablelink('type', trans('general.type'))</th>
                    <th class="text-center color-primary-500">{{ trans('general.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sources as $item)
                    <tr>
                        <th class="d-none"></th>
                        <td><a href="{{ route('indicator_sources.show', $item->id) }}">{{ $item->name }}</a></td>
                        <td>{{ $item->institution }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ trans('indicators.indicator.TYPE_'.$item->type) }}</td>
                        <td class="text-center w-20">
                                <form action="{{ route('indicator_sources.destroy',$item->id) }}" method="POST">
                                    @can('admin-crud-admin')
                                        <a class="mr-2" href="{{ route('indicator_sources.edit', $item->id) }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar"><i
                                                    class="fas fa-edit mr-1 text-info"></i>
                                        </a>
                                    @endcan
                                    @csrf
                                    @method('DELETE')
                                    @can('admin-crud-admin')
                                        <button class="mr-2 border-0" style="border: 0 !important; background-color: transparent !important;" type="submit" data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar"><i class="fas fa-trash mr-1 text-danger"></i></button>
                                    @endcan
                                </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <x-pagination :items="$sources" />
    </div>
@endsection