<div wire:ignore.self class="modal fade fade" id="show-reform" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-xl  modal-dialog-centered">
        @if($transaction)
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4"><i
                                class="fas fa-plus-circle text-success"></i> {{ trans('general.show') }} {{trans('budget.reforms')}} {{$transaction->type}} {{$transaction->number}}
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
                        <div class="d-flex flex-column p-2 w-15" wire:ignore>
                            <x-label-detail>Tipo de Reforma</x-label-detail>
                            <x-content-detail> {{ $transaction->reform_type }}</x-content-detail>
                        </div>
                        <div class="d-flex flex-column p-2 w-15">
                            <x-label-detail>{{trans('general.created_at')}}</x-label-detail>
                            <x-content-detail> {{ $transaction->created_at->diffForHumans() }}</x-content-detail>
                        </div>
                        <div class="d-flex flex-column p-2 w-15">
                            <x-label-detail>{{trans('general.updated_at')}}</x-label-detail>
                            <x-content-detail> {{ $transaction->updated_at->diffForHumans() }}</x-content-detail>
                        </div>
                        <div class="d-flex flex-column p-2 w-10">
                            <x-label-detail>Estado</x-label-detail>
                            <x-content-detail>
                                <span class="badge {{ $transaction->status->color() }}">
                                            {{ $transaction->status->label() }}
                                </span>
                            </x-content-detail>
                        </div>
                        <div class="d-flex flex-column p-2 w-25">
                            <x-label-detail>{{trans('general.description')}}</x-label-detail>
                            <x-content-detail> {{ $transaction->description }}</x-content-detail>

                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap mt-2 p-4">
                    <h2><i class="fa fa-money-bill text-success"></i> Detalles de la reforma</h2>
                    <hr>
                    <div class="w-100 pl-2">
                        <div class="table-responsive">
                            <table class="table table-light">
                                <thead>
                                <tr>
                                    <th class="w-50 table-th">{{__('budget.item_code')}}</th>
                                    <th class="w-20 table-th">{{__('general.name')}}</th>
                                    <th class="w-15 table-th">{{__('budget.increment')}}</th>
                                    <th class="w-15 table-th">{{__('budget.decrease')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="text-center border-top border-bottom bg-secondary color-white">
                                    <td colspan="4"> Ingresos</td>
                                </tr>
                                @foreach($arrayReformsIncomes as $index => $itemIncome)
                                    <tr class="">
                                        <td class="w-50 table-th">{{$itemIncome['code']}}</td>
                                        <td class="w-20 table-th">{{$itemIncome['name']}}</td>
                                        <td class="w-15 table-th">{{$itemIncome['debit']}}</td>
                                        <td class="w-15 table-th">{{$itemIncome['credit']}}</td>
                                    </tr>
                                @endforeach
                                <tr class="text-center border-top border-bottom bg-secondary color-white">
                                    <td colspan="4"> Gastos</td>
                                </tr>
                                @foreach($arrayReformsExpenses as $index => $itemExpense)
                                    <tr class="">
                                        <td class="w-50 table-th">{{$itemExpense['code']}}</td>
                                        <td class="w-20 table-th">{{$itemExpense['name']}}</td>
                                        <td class="w-15 table-th">{{$itemExpense['credit']}}</td>
                                        <td class="w-15 table-th">{{$itemExpense['debit']}}</td>
                                    </tr>
                                @endforeach
                                <tr style="background-color: #e0e0e0">
                                    <td colspan="4" class="fs-2x fw-700 ml-auto text-center @if($totalDecreases==$totalIncrements) color-success-700 @else color-danger-700 @endif">
                                        Balance:  ${{$totalIncrements-$totalDecreases}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if($transaction->status instanceof \App\States\Transaction\Balanced)
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" wire:click="resetForm" class="close" data-dismiss="modal" aria-label="Close"><i
                                    class="fas fa-times"></i> {{ trans('general.cancel') }}</button>
                        <button class="btn btn-success text-center" wire:click="saveReform">
                            <i class="fas fa-save pr-2"></i> {{ trans('general.approve') }}
                        </button>
                    </div>
                @endif

            </div>
        @endif
    </div>
</div>