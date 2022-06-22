@extends('layouts.admin')

@section('title', trans_choice('general.plan', 2))

@section('subheader-title')
    <i class="fal fa-align-left text-primary"></i> {{ __('general.detail') . ' ' . __('general.structure') . ' ' . trans_choice('general.plan', 1) . ' - ' . $plan->name }}
@endsection
@push('css')
    <style>
        .subheader {
            margin-bottom: 0 !important;
        }
    </style>
@endpush


@section('content')

    <ol class="breadcrumb bg-transparent breadcrumb-sm pl-0 pr-0">
        @foreach($planRegisteredTemplateDetailsBreadcrumbs as $item)
            <li class="breadcrumb-item {{ $item['link'] == '' ? 'active' : '' }}">
                @if($item['link'] == '')
                    @if($item['first']) <i class="fal fa-folder-open mr-1"></i> @endif <a
                            class="fs-2x color-black">{{ $item['name'] }}</a>
                @else
                    <a href="{{ $item['link'] }}" class="fs-2x">@if($item['first']) <i
                                class="fal fa-folder-open mr-1"></i> @endif{{ $item['name'] }}</a>
                @endif
            </li>
        @endforeach
    </ol>
    <div class="row">
        <div class="table-responsive">

            <div class="panel-hdr">
                <h2>
                    {{ $title }}
                </h2>
                @can('strategy-crud-strategy' || 'strategy-plan-crud-strategy')
                    @if($plan->status != \App\Models\Strategy\Plan::ARCHIVED)
                        <div class="panel-toolbar">
                            <a href="#new-modal-element"
                               class="btn btn-success btn-sm"
                               data-toggle="modal"
                               data-target="#new-modal-element"
                               data-level-id="{{ $level }}"
                               data-registered-id="{{ $planRegisteredTemplateDetail->id }}"
                               data-detail-id="{{ $planDetailId }}">
                                <span class="fas fa-plus mr-1"></span>
                                {{ trans('general.add_new') }}
                            </a>
                        </div>
                    @endif
                @endcan
            </div>
            @if(count($planDetails)<1)
                <div class="container">
                    <div class="row">
                        <div class="col text-center">
                            <x-empty-content>
                                <x-slot name="title">
                                    No existen elementos
                                </x-slot>
                            </x-empty-content>
                        </div>
                    </div>
                </div>
            @else

                <table class="table m-0">
                    <thead class="bg-primary-50">
                    <tr>
                        <th class="w-5 color-primary-500 ">@sortablelink('code', trans('general.code'))</th>
                        <th class="color-primary-500 ">@sortablelink('name', $planRegisteredTemplateDetail->name)</th>

{{--                        <th>{{$planRegisteredTemplateDetail->name}}</th>--}}
                        @foreach($planRegisteredTemplateDetail->childs->where('plan_id',$plan->id) as $child)
                            <th class="text-center w-5">{{$child->name}}</th>
                        @endforeach
                        @if($planRegisteredTemplateDetail->indicators)
                            @php $hasIndicators=true; @endphp
                            <th class="text-center w-5">{{trans_choice('general.tactical_indicators',2)}}</th>
                            <th class="text-center w-5">{{trans_choice('general.operational_indicators',2)}}</th>
                        @endif
                        @foreach($articulations as $index => $articulation)
                            <th class="text-center w-5">{{$plans->where('id',$index)->first()->name}}</th>
                        @endforeach
                        @can('strategy-crud-strategy' || 'strategy-plan-crud-strategy')
                            <th class="text-center color-primary-500 w-15">{{ trans('general.actions') }}</th>
                        @endcan
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($planDetails as $item1)
                        <tr>
                            <td class="w-5"># {{ $item1->full_code }}</td>
                            <td>{{ $item1->name }}</td>
                            @foreach($planRegisteredTemplateDetail->childs->where('plan_id',$plan->id)  as $child)

                                <td class="text-center w-5">
                                    <a href="{{ route('plans.detail', [ 'plan' => $child->id,'planDetailId'=>$item1->id, 'detail' => $child->id]) }}"
                                       class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="tooltip" data-placement="top" title=""
                                       data-original-title="{{$item1->children->where('plan_registered_template_detail_id', $child->id)->count().' '. $child->name}}">
                                        {{$item1->children->where('plan_registered_template_detail_id', $child->id)->count()>0 ?
                                           $item1->children->where('plan_registered_template_detail_id', $child->id)->count() :'0'}}
                                    </a>
                                </td>

                            @endforeach

                            @isset($hasIndicators)
                                <td class="text-center w-5">
                                    <a href="{{ route('plan_details.indicators', ['planDetailId' => $item1->id,'type'=>\App\Models\Indicators\Indicator\Indicator::CATEGORY_TACTICAL,
                                                'navigation'=>$planRegisteredTemplateDetailsBreadcrumbs]) }}"
                                       class="btn btn-info btn-sm btn-icon waves-effect waves-themed"
                                       data-toggle="tooltip" data-placement="top" title=""
                                       data-original-title="{{trans_choice('general.tactical_indicators',2)}}"
                                    >
                                        {{$item1->indicators->where('category',\App\Models\Indicators\Indicator\Indicator::CATEGORY_TACTICAL)->count()>0?
                                     $item1->indicators->where('category',\App\Models\Indicators\Indicator\Indicator::CATEGORY_TACTICAL)->count() :'0'}}
                                    </a>
                                </td>
                                <td class="text-center w-5">
                                    <a href="{{ route('plan_details.indicators', ['planDetailId' => $item1->id,
                                            'type'=>\App\Models\Indicators\Indicator\Indicator::CATEGORY_OPERATIVE,
                                            'navigation'=>$planRegisteredTemplateDetailsBreadcrumbs]) }}"
                                       class="btn btn-info btn-sm btn-icon waves-effect waves-themed"
                                       data-toggle="tooltip" data-placement="top" title=""
                                       data-original-title="{{trans_choice('general.operational_indicators',2)}}"
                                    >
                                        {{$item1->indicators->where('category',\App\Models\Indicators\Indicator\Indicator::CATEGORY_OPERATIVE)->count()>0?
                                    $item1->indicators->where('category',\App\Models\Indicators\Indicator\Indicator::CATEGORY_OPERATIVE)->count():'0'}}
                                    </a>
                                </td>
                            @endisset
                            @foreach($articulations as $index => $articulation)
                                @if(in_array($item1->id,$articulation))
                                    <td class="text-center w-5">
                                        <i class="fal fa-check fa-2x" style="color: green"></i>
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            @endforeach
                            @can('strategy-crud-strategy' || 'strategy-plan-crud-strategy')
                                <td class="w-15 text-center">
                                    @if($plan->status != \App\Models\Strategy\Plan::ARCHIVED)
                                        <a href="#edit-modal-plan-detail"
                                           data-toggle="modal"
                                           data-target="#edit-modal-plan-detail"
                                           data-id="{{ $item1->id  }}"
                                           class="">
                                            <i class="fas fa-edit mr-1 text-info"
                                               data-toggle="tooltip" data-placement="top" title=""
                                               data-original-title="Editar"></i>
                                        </a>
                                        <x-delete-link-icon
                                                action="{{ route('plans.delete', ['plan' => $item1->id]) }}"
                                                id="{{ $item1->id }}">
                                        </x-delete-link-icon>
                                    @endif
                                </td>
                            @endcan
                        </tr>

                    @endforeach

                    </tbody>
                </table>
                <x-pagination :items="$planDetails"/>

            @endif
        </div>
    </div>
    <livewire:strategy.strategy-edit-modal-plan-detail/>
    <livewire:strategy.plan-detail-create/>
@endsection

@push('page_script')
    <script>
        $('#new-modal-element').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let levelId = $(e.relatedTarget).data('level-id');
            let registeredTemplateId = $(e.relatedTarget).data('registered-id');
            let planDetailId = $(e.relatedTarget).data('detail-id');
            Livewire.emit('loadDetailInfo', levelId, registeredTemplateId, planDetailId);
        });
        $('#edit-modal-plan-detail').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let id = $(e.relatedTarget).data('id');
            Livewire.emit('loadPlanDetail', id);
        });

        $("#edit-modal-plan-detail").on("hidden.bs.modal", function () {
            // Aquí va el código a disparar en el evento
            location.reload()
        });

        $("#new-modal-element").on("hidden.bs.modal", function () {
            // Aquí va el código a disparar en el evento
            location.reload()
        });

    </script>
@endpush
