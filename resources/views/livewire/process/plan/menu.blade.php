<a href="{{ route('process.showInformation', [$process->id, $page]) }}">
                        <span class="btn btn-sm {{ $subMenu == 'showInformation' ? 'btn-success':' btn-info' }} mr-2"
                              data-placement="top" title="Información del Proceso"
                              data-original-title="Información del Proceso">
                          <i class="fas fa-eye mr-1 "></i>  Información del Proceso </span>
</a>
<a href="{{ route('process.showRisks', [$process->id, $page]) }}">
                        <span class="btn btn-sm {{ $subMenu == 'showRisks' ? 'btn-success':' btn-info' }} mr-2"
                              data-placement="top" title="Gestión de Riesgos"
                              data-original-title="Gestión de Riesgos">
                          <i class="fas fa-engine-warning  mr-1"></i>Gestión de Riesgos</span>
</a>
<a href="{{ route('process.showActivities',[$process->id, $page]) }}">
                <span class="btn btn-sm {{ $subMenu == 'showActivities' ? 'btn-success':' btn-info' }} mr-2"
                      data-placement="top" title="Actividades"
                      data-original-title="Actividades">
                     <i class="fas fa-arrow-alt-from-top  mr-1"></i>Actividades</span>
</a>
<a href="{{ route('process.showPlanChanges', [$process->id, $page]) }}">
                        <span class="btn btn-sm {{ $subMenu == 'showPlanChanges' ? 'btn-success':' btn-info' }} mr-2"
                              data-placement="top" title="Planificación de Cambios"
                              data-original-title="Planificación de Cambios">
                    <i class="fas fa-calendar-check  mr-1"></i>Planificación de Cambios</span>
</a>
<a href="{{ route('process.showIndicators', [$process->id, $page]) }}">
                        <span class="btn btn-sm {{ $subMenu == 'showIndicators' ? 'btn-success':' btn-info' }} mr-2"
                              data-placement="top" title="Indicadores"
                              data-original-title="Indicadores">
                    <i class="fas fa-arrow-alt-square-up  mr-1"></i>Indicadores</span>
</a>
<a href="{{ route('process.showFiles', [$process->id, $page]) }}">
                        <span class="btn btn-sm {{ $subMenu == 'showFiles' ? 'btn-success':' btn-info' }} mr-2"
                              data-placement="top" title="Indicadores"
                              data-original-title="Indicadores">
                    <i class="fas fa-paperclip  mr-1"></i>Archivos</span>
</a>

