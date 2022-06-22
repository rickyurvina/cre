<div wire:ignore.self class="modal fade fade" id="show-commitment" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        @if($transaction)
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4"><i
                                class="fas fa-plus-circle text-success"></i> {{ trans('general.show') }} {{trans_choice('general.commitments', 1)}} {{$transaction->type}} {{$transaction->number}}
                    </h5>
                    <button wire:click="resetForm" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="d-flex flex-wrap w-100">
                        <div class="d-flex flex-column p-2 w-15">
                            <x-label-detail>Documento</x-label-detail>
                            <x-content-detail> {{ $transaction->type }} {{$transaction->number}}</x-content-detail>
                        </div>
                        <div class="d-flex flex-column p-2 w-15">
                            <x-label-detail>{{trans('general.created_at')}}</x-label-detail>
                            <x-content-detail> {{ $transaction->created_at->diffForHumans() }}</x-content-detail>
                        </div>
                        <div class="d-flex flex-column p-2 w-15">
                            <x-label-detail>{{trans('general.updated_at')}}</x-label-detail>
                            <x-content-detail> {{ $transaction->updated_at->diffForHumans() }}</x-content-detail>
                        </div>
                        <div class="d-flex flex-column p-2 w-15">
                            <x-label-detail>Estado</x-label-detail>
                            <x-content-detail>
                                <span class="badge {{ $transaction->status->color() }}">
                                            {{ $transaction->status->label() }}
                                </span>
                            </x-content-detail>
                        </div>
                        <div class="d-flex flex-column p-2 w-20">
                            <x-label-detail>{{trans('general.approved_by')}}</x-label-detail>
                            <x-content-detail> {{ $transaction->approved_by ?  $transaction->approver->getFullName() : ''}}</x-content-detail>
                        </div>
                        <div class="d-flex flex-column p-2 w-20">
                            <x-label-detail>{{trans('general.date_approved')}}</x-label-detail>
                            <x-content-detail> {{ $transaction->approved_date ?  $transaction->approved_date->format('F j, Y, g:i a')  : ''}}</x-content-detail>

                        </div>
                    </div>
                    <div class="d-flex flex-wrap w-100">
                        <div class="d-flex flex-column p-2 w-20">
                            <x-label-detail>{{trans('general.description')}}</x-label-detail>
                            <x-content-detail> {{ $transaction->description }}</x-content-detail>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap mt-2 p-4">
                        <h2><i class="fa fa-money-bill text-success"></i> Detalles de la {{trans_choice('general.certifications',1)}}</h2>
                        <hr>
                        @if($viewPoaActivity)
                            <div class="d-flex mt-2">
                                <div class="w-50">
                                    <table class="table table-bordered detail-table">
                                        <tbody>
                                        <tr>
                                            <td class="fs-1x fw-700 w-20">Ejercicio</td>
                                            <td colspan="2">
                                                {{$transaction->year}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fs-1x fw-700">Poa</td>
                                            <td class="w-5">
                                                {{$poaActivity->program->poa->year}}
                                            </td>
                                            <td class="fs-1x fw-700">
                                                {{$poaActivity->program->poa->name}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fs-1x fw-700">{{trans_choice('general.plan',1)}}</td>
                                            <td>
                                                {{$poaActivity->program->planDetail->plan->code}}
                                            </td>
                                            <td class="fs-1x fw-700">
                                                {{$poaActivity->program->planDetail->plan->name}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fs-1x fw-700 w-20">{{$poaActivity->program->planDetail->parent->parent->planRegistered->name}}
                                            </td>
                                            <td class="w-5">{{$poaActivity->program->planDetail->parent->parent->code}}</td>
                                            <td class="fs-1x fw-700">{{$poaActivity->program->planDetail->parent->parent->name}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="w-50">
                                    <table class="table table-bordered detail-table">
                                        <tbody>

                                        <tr>
                                            <td class="fs-1x fw-700 w-20">{{$poaActivity->program->planDetail->parent->planRegistered->name}}
                                            </td>
                                            <td class="w-5">{{$poaActivity->program->planDetail->parent->code}}</td>
                                            <td class="fs-1x fw-700">{{$poaActivity->program->planDetail->parent->name}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fs-1x fw-700">{{trans_choice('general.programs',1)}}</td>
                                            <td>
                                                {{$poaActivity->program->planDetail->code}}
                                            </td>
                                            <td class="fs-1x fw-700">
                                                {{$poaActivity->program->planDetail->name}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fs-1x fw-700 w-20">Indicador
                                            </td>
                                            <td class="w-5">{{$poaActivity->indicator->code}}</td>
                                            <td class="fs-1x fw-700">{{$poaActivity->indicator->name}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if($expensesPoa->count()>0)
                                <div class="table-responsive">
                                    <table class="table table-light table-hover">
                                        <thead>
                                        <tr>
                                            <th class="table-th w-20">{{trans('general.item')}}</th>
                                            <th class="table-th w-20"> {{trans('general.name')}}</th>
                                            <th class="table-th w-30"> {{trans('general.description')}}</th>
                                            <th class="table-th w-10">Certificado</th>
                                            <th class="table-th w-10">Comprometido</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($expensesPoa as $item)
                                            <tr class="tr-hover">
                                                <td><span class="badge {{$item->is_new ? 'badge-warning' : '' }}  badge-pill fs-1x fw-700">{{ $item->code }} {{$item->name}}</span>
                                                </td>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->getCertifiedValues($certification->id) }}</td>
                                                <td>{{ $item->getCertifiedValues($transaction->id) }} </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif

                        @if($viewProjectActivity)
                            <div class="d-flex mt-2">
                                <div class="w-50">
                                    <table class="table table-bordered detail-table">
                                        <tbody>
                                        <tr>
                                            <td class="fs-1x fw-700 w-20">Ejercicio</td>
                                            <td colspan="2">
                                                {{$transaction->year}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fs-1x fw-700">Proyecto</td>
                                            <td class="w-5">
                                                {{$projectActivity->project->code}}
                                            </td>
                                            <td class="fs-1x fw-700">
                                                {{$projectActivity->project->name}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="fs-1x fw-700">Junta Ejecutora</td>
                                            <td>
                                                {{$projectActivity->company->id}}
                                            </td>
                                            <td class="fs-1x fw-700">
                                                {{$projectActivity->company->name}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fs-1x fw-700">Programa</td>
                                            <td>
                                                {{$projectActivity->taskable->service->lineAction->program->code}}
                                            </td>
                                            <td class="fs-1x fw-700">
                                                {{$projectActivity->taskable->service->lineAction->program->name}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fs-1x fw-700 w-20">{{trans_choice('general.indicators',1)}}
                                            </td>
                                            <td class="w-5">{{$projectActivity->indicator->code ?? ''}}</td>
                                            <td class="fs-1x fw-700">{{$projectActivity->indicator->name ?? 'Sin Indicador Asociado'}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="w-50">
                                    <table class="table table-bordered detail-table">
                                        <tbody>
                                        <tr>
                                            <td class="fs-1x fw-700 w-20">{{trans('general.specific_objective')}}
                                            </td>
                                            <td class="w-5">{{$projectActivity->parentOfTask->objective->code ?? ''}}</td>
                                            <td class="fs-1x fw-700">{{$projectActivity->parentOfTask->objective->name ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fs-1x fw-700 w-20">{{trans('general.result')}}
                                            </td>
                                            <td class="w-5">{{$projectActivity->parentOfTask->code}}</td>
                                            <td class="fs-1x fw-700">{{$projectActivity->parentOfTask->text}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fs-1x fw-700">{{trans('general.service')}}</td>
                                            <td>
                                                {{$projectActivity->taskable->service->code}}
                                            </td>
                                            <td class="fs-1x fw-700">
                                                {{$projectActivity->taskable->service->name}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fs-1x fw-700 w-20">{{trans('general.lines_action')}}
                                            </td>
                                            <td>
                                                {{$projectActivity->taskable->service->lineAction->code}}
                                            </td>
                                            <td class="fs-1x fw-700">
                                                {{$projectActivity->taskable->service->lineAction->name}}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if($expensesProject)
                                <div class="table-responsive">
                                    <table class="table table-light table-hover">
                                        <thead>
                                        <tr>
                                            <th class="table-th w-20">{{trans('general.item')}}</th>
                                            <th class="table-th w-20"> {{trans('general.name')}}</th>
                                            <th class="table-th w-30"> {{trans('general.description')}}</th>
                                            <th class="table-th w-10">Certificado</th>
                                            <th class="table-th w-10">Comprometido</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($expensesProject as $item)
                                            <tr class="tr-hover">
                                                <td><span class="badge {{$item->is_new ? 'badge-warning' : '' }}  badge-pill fs-1x fw-700">{{ $item->code }} {{$item->name}}</span>
                                                </td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->getCertifiedValues($certification->id) }}</td>
                                                <td>{{ $item->getCertifiedValues($transaction->id) }} </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif
                    </div>

                    @if($transaction->status instanceof \App\States\Transaction\Draft)
                        <div class="modal-footer justify-content-center">
                            <div>
                                <a href="javascript:void(0)" class="btn btn-sm btn-success mr-2"
                                   wire:click="approveCommitment">{{trans('general.approve')}}
                                </a>
                            </div>
                            <div>
                                <a href="javascript:void(0)" class="btn btn-sm btn-warning"
                                   wire:click="declineCommitment">{{trans('general.refuse')}}
                                </a>
                            </div>
                        </div>
                    @endif
                    @endif
                </div>
            </div>
    </div>
</div>