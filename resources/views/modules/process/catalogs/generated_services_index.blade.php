@extends('layouts.admin')

@section('title', trans_choice('general.catalog', 2))

@section('subheader')
    <h1 class="subheader-title">
        <i class="fal fa-folder text-primary"></i> <span
                class="fw-300">{{ trans('general.generated_services') }}</span>
        <ol class="breadcrumb bg-transparent breadcrumb-sm pl-0 pr-0">
            <li class="breadcrumb-item active">
                <a href="{{ route('process.catalogs') }}" class="fs-2x"><i class="fal fa-folder-open mr-1"></i>{{trans_choice('general.catalog',2)}}</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('generated_services.index') }}" class="fs-2x"><i class="fal fa-folder-open mr-1"></i>{{ trans('general.generated_services') }}</a>
            </li>
        </ol>
    </h1>
    <a href="{{ route('generated_services.create') }}" class="btn btn-success btn-sm"><span class="fas fa-plus mr-1"></span> &nbsp;{{ trans('general.add_new') }}</a>

@endsection

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table m-0" id="table_units">
                <thead class="bg-primary-50">
                <tr>
                    <th>@sortablelink('id', trans('general.code'))</th>
                    <th>@sortablelink('name', trans('general.name'))</th>
                    <th>@sortablelink('name', trans('general.description'))</th>
                    <th class="text-center color-primary-500">{{ trans('general.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($generated_services as $item)
                    <tr wire:key="{{time().$item->id}}" wire:ignore>
                        <td>
                            <livewire:components.input-inline-edit :modelId="$item->id"
                                                                   class="\App\Models\Process\Catalogs\GeneratedService"
                                                                   field="code"
                                                                   :rules="'required|max:5|alpha_num|alpha_dash|unique:generated_services,code'"
                                                                   type="text"
                                                                   defaultValue="{{ $item->code ?? ''}}"
                                                                   :key="time().$item->id"/>
                        </td>
                        <td>
                            <livewire:components.input-inline-edit :modelId="$item->id"
                                                                   class="\App\Models\Process\Catalogs\GeneratedService"
                                                                   field="name"
                                                                   :rules="'required|max:200'"
                                                                   type="text"
                                                                   defaultValue="{{ $item->name ?? ''}}"
                                                                   :key="time().$item->id"/>
                        </td>
                        <td>
                            <livewire:components.input-inline-edit :modelId="$item->id"
                                                                   class="\App\Models\Process\Catalogs\GeneratedService"
                                                                   field="description"
                                                                   :rules="'required|max:500'"
                                                                   type="textarea"
                                                                   rows="5"
                                                                   defaultValue="{{ $item->description ?? ''}}"
                                                                   :key="time().$item->id"/>
                        </td>
                        <td class="text-center">
                            <x-delete-link action="{{ route('generated_services.destroy', $item->id) }}"
                                           id="{{ $item->id }}"/>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection