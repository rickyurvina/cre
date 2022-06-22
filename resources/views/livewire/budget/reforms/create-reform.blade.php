<div>
    <div class="d-flex flex-wrap w-100">
        <div class="d-flex flex-column p-2 w-15">
            <x-label-detail>Documento</x-label-detail>
            <x-content-detail> {{ $transaction->type }} {{$transaction->number}}</x-content-detail>
        </div>
        <div class="d-flex flex-column p-2 w-15" wire:ignore>
            <x-label-detail>Tipo de Reforma</x-label-detail>
            <select class="form-control" id="select2-types-reforms">
                @foreach(\App\Models\Budget\Transaction::REFORMS_TYPES  as $index => $item)
                    <option value="{{$item}}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex flex-column p-2 w-15">
            <x-label-detail>Fecha de Aprobación</x-label-detail>
            <x-content-detail> {{ $transaction->approved_date ?? 'Sin Aprobacion' }}</x-content-detail>
        </div>
        <div class="d-flex flex-column p-2 w-15">
            <x-label-detail>Fecha Creación Reforma</x-label-detail>
            <x-content-detail> {{ $transaction->created_at }}</x-content-detail>
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
            <livewire:components.input-inline-edit :modelId="$transaction->id"
                                                   class="App\Models\Budget\Transaction"
                                                   field="description" type="textarea"
                                                   defaultValue="{{$transaction->description ?? ''}}"
                                                   :key="time().$transaction->id"/>
        </div>
    </div>
    <div class="row">
        <div class="col-6 p-2">
            <div class="d-flex flex-wrap p-2 mt-2">
                <h2><i class="fa fa-search text-success"></i> Buscar Partidas Presupuestarias</h2>
            </div>
            <div class="d-flex flex-wrap mt-1">
                <div class="frame-wrap">
                    @if(!$readOnly)
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="incomes" @if($readOnly) readonly="readonly" @endif name="inlineDefaultRadiosExample"
                                   wire:click="$set('typeBudgetIncome', true)" checked="">
                            <label class="custom-control-label" for="incomes">{{trans('budget.incomes')}}</label>
                        </div>
                    @endif
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="expenses" name="inlineDefaultRadiosExample"
                               @if($readOnly) checked="" @endif
                               wire:click="$set('typeBudgetExpense', true)">
                        <label class="custom-control-label" for="expenses">{{trans('budget.expense')}}</label>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap mt-2 p-2">
                <div class="w-5">
                    <label for="countRegisters" class="mt-2">
                        Mostrar
                    </label>
                </div>
                <div class="w-20" wire:ignore>
                    <select class="form-control" id="select2-registers">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="w-5 mr-6">
                    <label for="countRegisters2" class="mt-2">
                        Registros
                    </label>
                </div>
                <div class="w-50">
                    <div class="d-flex mb-3">
                        <div class="input-group bg-white shadow-inset-f2 w-100 mr-2">
                            <input type="text" class="form-control border-right-0 bg-transparent pr-0"
                                   placeholder="{{ trans('general.filter') . ' ' . trans_choice('budget.item_code', 1) }} ..."
                                   wire:model="search">
                            <div class="input-group-append">
                        <span class="input-group-text bg-transparent border-left-0">
                            <i class="fal fa-search"></i>
                        </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap mt-2 p-2">
                <div class="w-100 pl-2">
                    <div class="table-responsive">
                        <table class="table table-light table-hover">
                            <thead>
                            <tr>
                                <th class="w-10 table-th">{{__('general.type')}}</th>
                                <th class="w-75 table-th">{{__('budget.item_code')}}</th>
                                <th class="w-5 table-th">{{__('budget.balance')}}</th>
                                <th class="w-10 table-th"><a href="#">{{ trans('general.actions') }} </a></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($accounts->take($countRegisterSelect) as $item)
                                <tr>
                                    <td class="table-th">{{\App\Models\Budget\Account::TYPES[$item->type]}}</td>
                                    <td class="table-th">{{$item->code}}</td>
                                    <td class="table-th">
                                        {{$item->balance}}
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" class="mr-2" wire:click="$set('accountSelected', {{$item->id}})"
                                           data-toggle="tooltip"
                                           data-placement="top" title=""
                                           data-original-title="{{ trans('general.select') }}">
                                            <i class="fas fa-check-circle text-success"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <x-pagination :items="$accounts"/>
                </div>
            </div>

        </div>
        <div class="col-6 p-2">
            @if($account)
                <div class="d-flex flex-wrap p-2 mt-2">
                    <h2><i class="fa fa-plus text-success"></i> Adicionar Partida Presupuestaria</h2>
                </div>
                <div class="d-flex flex-wrap w-100">
                    <div class="d-flex flex-column p-2 w-50">
                        <x-label-detail>{{trans('budget.item_code')}}</x-label-detail>
                        <x-content-detail> {{ $account->code }}</x-content-detail>
                    </div>
                    <div class="d-flex flex-column p-2 w-50">
                        <x-label-detail>{{trans('general.name')}}</x-label-detail>
                        <x-content-detail> {{ $account->name }}</x-content-detail>
                    </div>
                    <div class="d-flex flex-column p-2 w-50">
                        <x-label-detail>{{trans('general.description')}}</x-label-detail>
                        <x-content-detail> {{ $account->description }}</x-content-detail>
                    </div>
                    <div class="d-flex flex-column p-2 w-50">
                        <x-label-detail>{{trans('budget.balance')}}</x-label-detail>
                        <x-content-detail> {{ $account->balance }}</x-content-detail>
                    </div>
                    <div class="d-flex flex-column p-2 w-50">
                        <x-label-detail>{{trans('budget.increment')}}</x-label-detail>
                        @if($typeReformSelected==\App\Models\Budget\Transaction::REFORM_TYPE_INCREMENT || $typeReformSelected==\App\Models\Budget\Transaction::REFORM_TYPE_TRANSFER)
                            <div class="detail">
                                <div class="form-group col-md-12 required">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text badge badge-success">$</span>
                                        </div>
                                        <input type="number" wire:model.defer="increment" id="increment" class="form-control  @error('increment') is-invalid @enderror">
                                        @error('increment')
                                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="d-flex flex-column p-2 w-50">
                        <x-label-detail>{{trans('budget.decrease')}}</x-label-detail>
                        @if($typeReformSelected==\App\Models\Budget\Transaction::REFORM_TYPE_DECREASE || $typeReformSelected==\App\Models\Budget\Transaction::REFORM_TYPE_TRANSFER )
                            <div class="detail">
                                <div class="form-group col-md-12 required">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text badge badge-success">$</span>
                                        </div>
                                        <input type="number" wire:model.defer="decrease" id="decrease" class="form-control @error('decrease') is-invalid @enderror">
                                        @error('decrease')
                                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 text-center">
                        <button class="btn btn-success text-center" wire:click="addReform">
                            <i class="fas fa-plus pr-2"></i> {{ trans('general.add') }}
                        </button>
                    </div>
                </div>

                <div class="d-flex flex-wrap mt-2 p-2">
                    <h2><i class="fa fa-money-bill text-success"></i> Detalles de la reforma</h2>
                    <hr>
                    <div class="w-100 pl-2">
                        <div class="table-responsive">
                            <table class="table table-light">
                                <thead>
                                <tr>
                                    <th class="w-50 table-th">{{__('budget.item_code')}}</th>
                                    <th class="w-15 table-th">{{__('budget.increment')}}</th>
                                    <th class="w-15 table-th">{{__('budget.decrease')}}</th>
                                    <th class="w-20 table-th"><a href="#">{{ trans('general.actions') }} </a></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="text-center border-top border-bottom bg-secondary color-white">
                                    <td colspan="4"> Ingresos</td>
                                </tr>
                                @foreach($arrayReformsIncomes as $index => $itemIncome)
                                    <tr class="">
                                        <th class="w-50 table-th">{{$itemIncome['code']}}</th>
                                        <th class="w-15 table-th">${{$itemIncome['debit']}}</th>
                                        <th class="w-15 table-th">{{$itemIncome['credit']}}</th>
                                        <th class="w-20 table-th">
                                            <a href="javascript:void(0);" class="mr-2" wire:click="deleteItemIncome({{$index}})"
                                               data-toggle="tooltip"
                                               data-placement="top" title=""
                                               data-original-title="{{ trans('general.delete') }}">
                                                <i class="fas fa-trash text-danger"></i>
                                            </a>
                                        </th>
                                    </tr>
                                @endforeach
                                @if($typeReformSelected==\App\Models\Budget\Transaction::REFORM_TYPE_TRANSFER)
                                    <tr style="background-color: #e0e0e0">
                                        <td class="fs-2x fw-700 ml-auto text-right @if($subTotalIncomeDecrease==$subTotalIncomeIncrement) color-success-700 @else color-danger-700 @endif">
                                            Sub Total Ingresos
                                        </td>
                                        <td class="fs-2x fw-700 @if($subTotalIncomeDecrease==$subTotalIncomeIncrement)  color-success-700 @else color-danger-700 @endif">
                                            ${{$subTotalIncomeIncrement}}
                                        </td>
                                        <td class="fs-2x fw-700 @if($subTotalIncomeDecrease==$subTotalIncomeIncrement) color-success-700 @else color-danger-700 @endif">
                                            ${{$subTotalIncomeDecrease}}
                                        </td>
                                        <td></td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-light">
                                <tbody>
                                <tr class="text-center border-top border-bottom bg-secondary color-white">
                                    <td colspan="4"> Gastos</td>
                                </tr>
                                @foreach($arrayReformsExpenses as $index => $itemExpense)
                                    <tr class="">
                                        <th class="w-50 table-th">{{$itemExpense['code']}}</th>
                                        <th class="w-15 table-th">${{$itemExpense['credit']}}</th>
                                        <th class="w-15 table-th">${{$itemExpense['debit']}}</th>
                                        <th class="w-20 table-th">
                                            <a href="javascript:void(0);" class="mr-2" wire:click="deleteItemExpense({{$index}})"
                                               data-toggle="tooltip"
                                               data-placement="top" title=""
                                               data-original-title="{{ trans('general.delete') }}">
                                                <i class="fas fa-trash text-danger"></i>
                                            </a>
                                        </th>
                                    </tr>
                                @endforeach
                                @if($typeReformSelected==\App\Models\Budget\Transaction::REFORM_TYPE_TRANSFER)
                                    <tr style="background-color: #e0e0e0">
                                        <td class="fs-2x fw-700 ml-auto text-right @if($subTotalExpenseIncrement==$subTotalExpenseDecrease) color-success-700 @else color-danger-700 @endif">
                                            Sub Total Gastos
                                        </td>
                                        <td class="fs-2x fw-700 @if($subTotalExpenseIncrement==$subTotalExpenseDecrease) color-success-700 @else color-danger-700 @endif">
                                            ${{$subTotalExpenseIncrement}}
                                        </td>
                                        <td class="fs-2x fw-700 @if($subTotalExpenseIncrement==$subTotalExpenseDecrease) color-success-700 @else color-danger-700 @endif">
                                            ${{$subTotalExpenseDecrease}}
                                        </td>
                                        <td></td>
                                    </tr>
                                @endif
                                <tr style="background-color: #e0e0e0">
                                    <td class="fs-2x fw-700 ml-auto text-right @if($totalDecreases==$totalIncrements) color-success-700 @else color-danger-700 @endif">
                                        Total
                                    </td>
                                    <td class="fs-2x fw-700 @if($totalDecreases==$totalIncrements) color-success-700 @else color-danger-700 @endif">
                                        ${{$totalIncrements}}
                                    </td>
                                    <td class="fs-2x fw-700 @if($totalDecreases==$totalIncrements) color-success-700 @else color-danger-700 @endif">
                                        ${{$totalDecreases}}
                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" wire:click="resetCreate"><i
                                class="fas fa-times"></i> {{ trans('general.cancel') }}</button>
                    <button class="btn btn-success text-center" wire:click="saveReform">
                        <i class="fas fa-save pr-2"></i> {{ trans('general.save') }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

@push('page_script')
    <script>
        $(document).ready(function () {
            $('#select2-types-reforms').select2({
                placeholder: "{{ trans('general.select').' '.trans_choice('general.state',2) }}"
            }).on('change', function (e) {
            @this.set('typeReformSelected', $(this).val());
            });
        });

        $('#select2-registers').select2({
            placeholder: "{{ trans('general.select').' '.trans_choice('general.state',2) }}"
        }).on('change', function (e) {
        @this.set('countRegisterSelect', $(this).val());
        });

    </script>
@endpush