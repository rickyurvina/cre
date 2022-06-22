<div>
    <div>
        <div class="bold-h4">Nombre del Proyecto: {{$project->name ?? ''}}</div>
        <hr>
        <div class="d-flex flex-wrap">
            <div class="frame-wrap mt-2">
                @if(!$readOnly)
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="incomes" @if($readOnly) readonly="readonly"
                               @endif name="inlineDefaultRadiosExample"
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
            <div class="d-flex flex-wrap w-25 ml-2" wire:ignore>
                <div class="d-flex flex-wrap w-100">
                    <div class="mr-2 w-auto mt-2">
                        <x-label-detail>{{trans('general.level')}}</x-label-detail>
                    </div>
                    <div class="w-75">
                        <select class="form-control" id="select2-levelIncome">
                            @foreach($levelsIncomes as $index => $levelIncome)
                                <option value="{{$index}}">{{$levelIncome}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="w-100">
                <div class="d-flex mb-3 w-50">
                    <div class="input-group bg-white shadow-inset-f2 w-100 mr-2">
                        <input type="text" class="form-control border-right-0 bg-transparent pr-0"
                               placeholder="{{ trans('general.filter') . ' por ' . trans_choice('budget.item_code', 1) }} o Nombre ..."
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
        <table class="border ex-e-table w-100">
            <tr class="border text-center header">
                <th class="border text-center bold-h4 p-2">PARTIDA</th>
                <th class="border text-center bold-h4 p-2">NOMBRE</th>
                <th class="border text-center bold-h4 p-2">ASIG. INI</th>
                <th class="border text-center bold-h4 p-2">REFORMA</th>
                <th class="border text-center bold-h4 p-2">CODIFICADO</th>
                <th class="border text-center bold-h4 p-2">CERTIFICADO</th>
                <th class="border text-center bold-h4 p-2">COMPROMETIDO</th>
                <th class="border text-center bold-h4 p-2">DEVENGADO</th>
                <th class="border text-center bold-h4 p-2">POR COMPROMETER</th>
                <th class="border text-center bold-h4 p-2">POR DEVENGAR</th>
                <th class="border text-center bold-h4 p-2">PAGADO</th>
            </tr>
            @foreach($activities as $activity)
                @foreach($activity->accounts as $account)
                    <tr class="border text-center">
                        <td class="border text-center p-2">
                            <a href="{{route('budget.showAccount',$account->id)}}">
                                {{$account->code}}
                            </a>
                        </td>
                        <td class="border text-center p-2">{{$activity->text}}</td>
                        <td class="border text-center p-2">{{$account->balancePr}}</td>
                        <td class="border text-center p-2">{{$account->balanceRe}}</td>
                        <td class="border text-center p-2">{{$account->balance}}</td>
                        <td class="border text-center p-2">$0.00</td>
                        <td class="border text-center p-2">{{$account->balanceCm}}</td>
                        <td class="border text-center p-2">$0.00</td>
                        <td class="border text-center p-2">$0.00</td>
                        <td class="border text-center p-2">$0.00</td>
                        <td class="border text-center p-2">$0.00</td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    </div>
    @push('page_script')
        <script>

            $(document).ready(function () {

                $('#select2-levelIncome').select2({
                    placeholder: "{{ trans('general.select').' '.trans('general.level') }}"
                }).on('change', function (e) {
                @this.set('levelIncomeSelected', $(this).val());
                });
            });
        </script>
    @endpush
</div>
