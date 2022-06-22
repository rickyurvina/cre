@extends('modules.projectInternal.project')

@section('project-page')

    <div id="panel-6">
        <div class="d-flex flex-row-reverse">
            <livewire:risks.create-risk :projectId="$projectId" :project="$project" :messages="$messages"/>
        </div>
        <div class="panel-container show">
            <div class="panel-content">
                <ul class="nav nav-tabs nav-tabs-clean" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-home" role="tab">Lista de Riesgos</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-profile" role="tab">Mapa de Riesgos</a></li>
                </ul>
                <div class="tab-content p-3">
                    <div class="tab-pane fade show active" id="tab-home" role="tabpanel" aria-labelledby="tab-home">
                        <div class="col-12">
                            @if($risks->count()>0)

                                <br>
                                <x-search route="{{ route('projects.risks',$project) }}"/>

                                <div class="table-responsive">
                                    <table class="table m-0">
                                        <thead class="bg-primary-50">
                                        <tr>
                                            <th class="d-none">@sortablelink('id', trans('general.id'))</th>
                                            <th>@sortablelink('name', trans('general.name'))</th>
                                            <th>@sortablelink('description', trans('general.description'))</th>

                                            <th>@sortablelink('state', trans('general.state'))</th>
                                            <th>@sortablelink('created_at', trans('general.created'))</th>
                                            <th>@sortablelink('enabled', trans('general.enabled'))</th>
                                            <th class="text-center color-primary-500">{{ trans('general.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($risks as $item)
                                            <tr>
                                                <td class="d-none"></td>
                                                <td class="text-truncate text-truncate-lg">
                                                    {{ $item->name }}
                                                </td>
                                                <td class="text-truncate text-truncate-lg">{{ $item->description }}</td>

                                                <td>
{{--                                                    @if ($item->state->description == \App\Models\Risk\Risk::RISK_STATE_OPEN)--}}
{{--                                                        <span class="badge badge-success">{{ $item->state->description }}</span>--}}
{{--                                                    @else--}}
{{--                                                        <span class="badge badge-danger">{{ $item->state->description }}</span>--}}
{{--                                                    @endif--}}
                                                </td>
                                                <td>@date($item->created_at)</td>
                                                <td>
                                                    @if ($item->enabled)
                                                        <badge rounded type="success" class="mw-60">{{ trans('general.yes') }}</badge>
                                                    @else
                                                        <badge rounded type="danger" class="mw-60">{{ trans('general.no') }}</badge>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                                                                        @if($project->phase!=\App\Models\Projects\Project::PHASE_START_UP)
                                                    <a href="#" data-toggle="modal" data-risk-id="{{ $item->id }}"
                                                       data-target="#edit-modal-risk">
                                                        <i class="fas fa-edit mr-1 text-info"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="Editar"></i></a>
                                                                                                        @endif
                                                    @can('delete-module-risk')
                                                        <x-delete-link action="{{ route('risks.destroy', $item->id) }}" id="{{ $item->id }}"></x-delete-link>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <livewire:risks.updated-risk :project="$project"/>
                                </div>
                                <x-pagination :items="$risks"/>


                            @else
                                <x-empty-content>
                                    <x-slot name="title">
                                        {{trans_choice('general.risks',2)}}
                                    </x-slot>
                                </x-empty-content>

                            @endif
                        </div>
                        .
                    </div>
                    <div class="tab-pane fade" id="tab-profile" role="tabpanel" aria-labelledby="tab-profile">
                        <div class="row">
                            <div class="col-12">
                                <div id="chartHeadMapRisks" style="width: 100% !important; height: 600px !important;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('page_script')
    <script>
        window.addEventListener('deleteAlertAction', event => {
            Swal.fire({
                target: document.getElementById('edit-modal-risk'),
                title: '{{ trans('messages.warning.sure') }}',
                text: '{{ trans('messages.warning.delete') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                confirmButtonText: '<i class="fas fa-trash"></i> {{ trans('general.yes') . ', ' . trans('general.delete') }}',
                cancelButtonText: '<i class="fas fa-times"></i> {{ trans('general.no') . ', ' . trans('general.cancel') }}'
            }).then((result) => {
                if (result.value) {
                    var id = event.detail.id;
                    window.livewire.emitTo('risks.create-action-risk', 'deleteAction', {id: id});
                }
            });

        });
        $('#edit-modal-risk').on('show.bs.modal', function (e) {
            //get level ID & plan registered template detail ID
            let riskId = $(e.relatedTarget).data('risk-id');
            //Livewire event trigger
            Livewire.emit('loadUpdateForm', riskId);
        });

        am4core.ready(function () {

            am4core.disposeAllCharts();
            // Apply chart themes
            am4core.useTheme(am4themes_animated);

            let chart = am4core.create("chartHeadMapRisks", am4charts.XYChart);
            chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

            chart.maskBullets = true;

            let xAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            let yAxis = chart.yAxes.push(new am4charts.CategoryAxis());

            xAxis.dataFields.category = "x";
            yAxis.dataFields.category = "y";
            xAxis.renderer.labels.template.rotation = -315;

            xAxis.renderer.grid.template.disabled = true;
            xAxis.renderer.minGridDistance = 40;

            yAxis.renderer.grid.template.disabled = true;
            yAxis.renderer.inversed = true;
            yAxis.renderer.minGridDistance = 30;

            let series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.categoryX = "x";
            series.dataFields.categoryY = "y";
            series.dataFields.value = "value";
            series.sequencedInterpolation = true;
            series.defaultState.transitionDuration = 3000;

            let column = series.columns.template;
            column.strokeWidth = 2;
            column.strokeOpacity = 1;
            column.stroke = am4core.color("#FFFFFF");
            column.tooltipText = "Impacto : {impact} \n Probabilidad {probability}";
            series.tooltip.getFillFromObject = false;
            series.tooltip.background.fill = am4core.color("#FFFFFF");
            series.tooltip.label.fill = am4core.color("#000");
            column.width = am4core.percent(100);
            column.height = am4core.percent(100);
            column.column.cornerRadius(6, 6, 6, 6);
            column.propertyFields.fill = "color";

            // Set up bullet appearance
            var bullet1 = series.bullets.push(new am4charts.CircleBullet());
            bullet1.circle.propertyFields.radius = 'radius';
            bullet1.circle.fill = am4core.color("#FFFFFF");
            bullet1.circle.strokeWidth = 0;
            bullet1.circle.fillOpacity = 0.7;
            bullet1.interactionsEnabled = false;

            var bullet2 = series.bullets.push(new am4charts.LabelBullet());
            bullet2.label.text = "{radius}";
            bullet2.label.fill = am4core.color("#000000");
            bullet2.zIndex = 1;
            bullet2.fontSize = 15;
            bullet2.interactionsEnabled = false;

            series.columns.template.cursorOverStyle = am4core.MouseCursorStyle.pointer;

            chart.data = @json($scales)

        });

    </script>
@endpush