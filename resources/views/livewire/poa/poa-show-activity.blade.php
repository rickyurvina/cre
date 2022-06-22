<div  class="modal fade in" id="poa-show-activity-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    @if($poaActivity)
        <div class="modal-dialog modal-dialog-right modal-lg" style="max-width: 60% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="text-info">
                        {{$poaActivity->name}}
                    </h5>
                    <button wire:click="resetForm" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex overflow-auto">
                        <ul class="nav nav-tabs-clean color-fusion-50 font-weight-bolder flex-nowrap">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#js_change_pill_justified-1">{{trans('general.activity')}}</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#js_change_pill_justified-2"> Comentarios</a></li>
                        </ul>
                    </div>
                    <div class="tab-content py-3" style="margin-top: 1%;">
                        <div class="tab-pane fade show active" id="js_change_pill_justified-1" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <dl class="row">
                                        <dt class="col-sm-2"><h5><strong>{{ trans('general.poa') }}</strong></h5></dt>
                                        <dd class="col-sm-4">
                                            {{ $poaActivity->program->poa->name }} : {{ number_format($poaActivity->program->poa->progress,1)  }}%
                                        </dd>
                                        <dt class="col-sm-2"><h5><strong>{{ trans('general.program') }}</strong></h5></dt>
                                        <dd class="col-sm-4">
                                            {{ $poaActivity->program->planDetail->name }} :   {{ number_format($poaActivity->program->progress*100, 1)  }}%
                                        </dd>
                                        <dt class="col-sm-2"><h5><strong>{{ trans('general.indicator') }}</strong></h5></dt>
                                        <dd class="col-sm-4">
                                            {{ $poaActivity->indicator->name }}
                                        </dd>
                                        <dt class="col-sm-2"><h5><strong>{{ trans('general.activity') }}</strong></h5></dt>
                                        <dd class="col-sm-4">
                                            {{ $poaActivity->name }}
                                        </dd>
                                        <dt class="col-sm-2"><h5><strong>{{ trans('general.poa_activity_location') }}</strong></h5></dt>
                                        <dd class="col-sm-4">
                                            {{ $poaActivity->location->name ?? null }}- {{ $poaActivity->community }}
                                        </dd>
                                        <dt class="col-sm-2"><h5><strong>{{ trans('general.responsable') }}</strong></h5></dt>
                                        <dd class="col-sm-4">
                                            {{ $poaActivity->responsible->name}}
                                        </dd>
                                        <dt class="col-sm-2"><h5><strong>{{ trans('general.indicator_unit') }}</strong></h5></dt>
                                        <dd class="col-sm-4">
                                            {{ $poaActivity->indicatorUnit->name}}
                                        </dd>
                                        <dt class="col-sm-2"><h5><strong>{{ trans('general.weight') }}</strong></h5></dt>
                                        <dd class="col-sm-4">
                                            {{ number_format($poaActivity->poa_weight * 100, 1) }}%
                                        </dd>
                                        <dt class="col-sm-2"><h5><strong>{{ trans('general.status') }}</strong></h5></dt>
                                        <dd class="col-sm-4">
                                            {{ $poaActivity->status}}
                                        </dd>
                                        <dt class="col-sm-2"><h5><strong>{{ trans('general.state') }}</strong></h5></dt>
                                        <dd class="col-sm-4">
                                            {{ $poaActivity->state}}
                                        </dd>
                                        <dt class="col-sm-2"><h5><strong>Planificado</strong></h5></dt>
                                        <dd class="col-sm-4">
                                            {{ $poaActivity->goal}}
                                        </dd>
                                        <dt class="col-sm-2"><h5><strong>Ejecutado</strong></h5></dt>
                                        <dd class="col-sm-4">
                                            {{ $poaActivity->progress}}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="text-center">
                                <h3>% de Avance</h3>
                            </div>
                            <div id="indicator-chart-" class="w-100 height-lg" wire:ignore.self>
                            </div>
                            <div id="chartdata-" style="display: none;"></div>
                            <div id="chartAdvanceActivity" class="w-100 height-lg" wire:ignore.self></div>
                        </div>
                        <div class="tab-pane fade" id="js_change_pill_justified-2" role="tabpanel">
                            <span class="fs-2x w-40px"><i class="fal fa-comment-dots"></i></span>
                            <livewire:components.comments :modelId="$poaActivity->id" class="\App\Models\Poa\PoaActivity" :key="time().$poaActivity->id"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@push('page_script')
    <script>
        // end am4core.ready()
        $('#poa-show-activity-modal').on('show.bs.modal', function (e) {
            window.addEventListener('updateChartDataActivity', event => {
                let chart_ = am4core.create("indicator-chart-", am4charts.XYChart);
                chart_.data = event.detail.data;

                // Create axes
                var categoryAxis = chart_.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "frequency";
                categoryAxis.title.text = "Frecuencia";
                categoryAxis.renderer.minGridDistance = 50;
                categoryAxis.renderer.grid.template.location = 0.5;
                categoryAxis.renderer.labels.template.adapter.add("html", function (html, target) {
                    if (target.dataItem.dataContext) {
                        return `<span style="font-weight: bold;color:` + target.dataItem.dataContext.color + `">` + target.dataItem.dataContext.frequency.replace(/ \(.*/, "") + `<br>
                                <span style="opacity: 0.4;">` + target.dataItem.dataContext.year + `</span></span>`;
                    }
                });

                let valueAxis = chart_.yAxes.push(new am4charts.ValueAxis());
                valueAxis.title.text = "Unidad de medida";

                // Create series
                var series = chart_.series.push(new am4charts.LineSeries());
                series.dataFields.valueY = "value";
                series.dataFields.categoryX = "frequency";
                series.name = "Meta";
                series.tooltipText = "{name}: [bold]{valueY}[/]";
                series.strokeWidth = 3;
                series.strokeDasharray = "5,4";
                series.stroke = am4core.color("#9d00ff");
                let circleBullet = series.bullets.push(new am4charts.CircleBullet());
                circleBullet.circle.fill = am4core.color("#fff");
                circleBullet.circle.stroke = am4core.color("#9d00ff");
                circleBullet.circle.strokeWidth = 3;
                series.tooltip.pointerOrientation = "vertical";

                var series1 = chart_.series.push(new am4charts.LineSeries());
                series1.dataFields.valueY = "actual";
                series1.dataFields.categoryX = "frequency";
                series1.name = "Actual";
                series1.tooltipText = "{name}: [bold]{valueY}[/]";
                series1.strokeWidth = 3;
                series1.bullets.push(new am4charts.CircleBullet());
                let circleBullet1 = series1.bullets.push(new am4charts.CircleBullet());
                circleBullet1.circle.fill = am4core.color("#fff");
                circleBullet1.propertyFields.stroke = "color";
                circleBullet1.circle.strokeWidth = 3;

                chart_.legend = new am4charts.Legend();

                // Add cursor
                chart_.cursor = new am4charts.XYCursor();

                chart_.events.on("datavalidated", function (ev) {
                    chart_.exporting.dataFields = {
                        "frequency": "",
                        "value": "Meta",
                        "actual": "Actual",
                        "progress": "%",
                    }
                    chart_.exporting.adapter.add("data", function (data) {
                        for (var i = 0; i < data.data.length; i++) {
                            data.data[i].progress += "%";
                        }
                        return data;
                    });

                    chart_.exporting.getHTML("html", {
                        addColumnNames: true,
                        pivot: true,
                        emptyAs: "",
                        tableClass: "table table-sm m-0"
                    }, false).then(function (html) {
                        var div = document.getElementById("chartdata-");
                        div.innerHTML = html;
                    });
                });

                // A button to toggle the data table
                var button = chart_.createChild(am4core.SwitchButton);
                button.align = "right";
                button.leftLabel.text = "Ver Datos";
                button.isActive = false;

                // Set toggling of data table
                button.events.on("toggled", function (ev) {
                    var div = document.getElementById("chartdata-");
                    if (button.isActive) {
                        div.style.display = "block";
                    } else {
                        div.style.display = "none";
                    }
                });
            });
            window.addEventListener('updateChartDataActivity2', event => {
                am4core.useTheme(am4themes_animated);
                var chartMin = 0;
                var chartMax = 100;
                data = {
                    score: event.detail.data[0]['score'],
                    gradingData: [
                        {
                            color: "#ee1f25",
                            lowScore: 0,
                            highScore: event.detail.data[0]['min']
                        },
                        {
                            color: "#F39C12",
                            lowScore: event.detail.data[0]['min'],
                            highScore: event.detail.data[0]['max']
                        },
                        {
                            color: "#0f9747",
                            lowScore: event.detail.data[0]['max'],
                            highScore: 100
                        }
                    ]
                };

                /**
                 Grading Lookup
                 */
                function lookUpGrade(lookupScore, grades) {
                    // Only change code below this line
                    for (var i = 0; i < grades.length; i++) {
                        if (
                            grades[i].lowScore < lookupScore &&
                            grades[i].highScore >= lookupScore
                        ) {
                            return grades[i];
                        }
                    }
                    return null;
                }

//
                var chart = am4core.create("chartAdvanceActivity", am4charts.GaugeChart);
                chart.hiddenState.properties.opacity = 0;
                chart.fontSize = 11;
                chart.innerRadius = am4core.percent(80);
                chart.resizable = true;
                /**
                 * Normal axis
                 */
                var axis = chart.xAxes.push(new am4charts.ValueAxis());
                axis.min = chartMin;
                axis.max = chartMax;
                axis.strictMinMax = true;
                axis.renderer.radius = am4core.percent(80);
                axis.renderer.inside = true;
                axis.renderer.line.strokeOpacity = 0.1;
                axis.renderer.ticks.template.disabled = false;
                axis.renderer.ticks.template.strokeOpacity = 1;
                axis.renderer.ticks.template.strokeWidth = 0.5;
                axis.renderer.ticks.template.length = 5;
                axis.renderer.grid.template.disabled = true;
                axis.renderer.labels.template.radius = am4core.percent(15);
                axis.renderer.labels.template.fontSize = "0.9em";
                /**
                 * Axis for ranges
                 */
                var axis2 = chart.xAxes.push(new am4charts.ValueAxis());
                axis2.min = chartMin;
                axis2.max = chartMax;
                axis2.strictMinMax = true;
                axis2.renderer.labels.template.disabled = true;
                axis2.renderer.ticks.template.disabled = true;
                axis2.renderer.grid.template.disabled = false;
                axis2.renderer.grid.template.opacity = 0.5;
                axis2.renderer.labels.template.bent = true;
                axis2.renderer.labels.template.fill = am4core.color("#000");
                axis2.renderer.labels.template.fontWeight = "bold";
                axis2.renderer.labels.template.fillOpacity = 0.3;
                /**
                 Ranges
                 */
                for (let grading of data.gradingData) {
                    var range = axis2.axisRanges.create();
                    range.axisFill.fill = am4core.color(grading.color);
                    range.axisFill.fillOpacity = 0.8;
                    range.axisFill.zIndex = -1;
                    range.value = grading.lowScore > chartMin ? grading.lowScore : chartMin;
                    range.endValue = grading.highScore < chartMax ? grading.highScore : chartMax;
                    range.grid.strokeOpacity = 0;
                    range.stroke = am4core.color(grading.color).lighten(-0.1);
                    range.label.inside = true;
                    // range.label.text = grading.title.toUpperCase();
                    range.label.inside = true;
                    range.label.location = 0.5;
                    range.label.inside = true;
                    range.label.radius = am4core.percent(10);
                    range.label.paddingBottom = -5; // ~half font size
                    range.label.fontSize = "0.9em";
                }

                var matchingGrade = lookUpGrade(data.score, data.gradingData);
                var label = chart.radarContainer.createChild(am4core.Label);
                label.isMeasured = false;
                label.fontSize = "6em";
                label.x = am4core.percent(50);
                label.paddingBottom = 15;
                label.horizontalCenter = "middle";
                label.verticalCenter = "bottom";
//label.dataItem = data;
                label.text = data.score.toFixed(2);
//label.text = "{score}";
                label.fill = am4core.color(matchingGrade.color);


                var label2 = chart.radarContainer.createChild(am4core.Label);
                label2.isMeasured = false;
                label2.fontSize = "2em";
                label2.horizontalCenter = "middle";
                label2.verticalCenter = "bottom";
                // label2.text = matchingGrade.title.toUpperCase();
                label2.fill = am4core.color(matchingGrade.color);


                var hand = chart.hands.push(new am4charts.ClockHand());
                hand.axis = axis2;
                hand.innerRadius = am4core.percent(55);
                hand.startWidth = 8;
                hand.pin.disabled = true;
                hand.value = data.score;
                hand.fill = am4core.color("#444");
                hand.stroke = am4core.color("#000");

                hand.events.on("positionchanged", function () {
                    label.text = axis2.positionToValue(hand.currentPosition).toFixed(1);
                    var value2 = axis.positionToValue(hand.currentPosition);
                    var matchingGrade = lookUpGrade(axis.positionToValue(hand.currentPosition), data.gradingData);
                    // label2.text = matchingGrade.title.toUpperCase();
                    label2.fill = am4core.color(matchingGrade.color);
                    label2.stroke = am4core.color(matchingGrade.color);
                    label.fill = am4core.color(matchingGrade.color);
                })

            })
        })
    </script>
@endpush


