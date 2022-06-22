<?php

namespace App\Http\Livewire\Budget\Reforms;

use App\Models\Budget\Account;
use App\Models\Budget\Transaction;
use App\Models\Budget\TransactionDetail;
use App\States\Transaction\Balanced;
use App\States\Transaction\Digited;
use Livewire\Component;
use Livewire\WithPagination;

class CreateReform extends Component
{
    use WithPagination;

    public $transaction;
    public $account;
    public $typeReformSelected = Transaction::REFORM_TYPE_INCREMENT;
    public $countRegisterSelect;
    public $typeBudgetIncome = true;
    public $typeBudgetExpense;
    public $accountSelected;
    public $increment;
    public $decrease;
    public $readOnly = false;
    public array $arrayReformsExpenses = [];
    public array $arrayReformsIncomes = [];
    public $search = '';
    public $subTotalIncomeIncrement = 0;
    public $subTotalIncomeDecrease = 0;
    public $subTotalExpenseIncrement = 0;
    public $subTotalExpenseDecrease = 0;
    public $totalIncrements = 0;
    public $totalDecreases = 0;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function render()
    {
        $accounts = Account::where('year', $this->transaction->year)
            ->when($this->typeBudgetIncome, function ($query) {
                $query->where('type', Account::TYPE_INCOME);
            })
            ->when($this->typeBudgetExpense, function ($query) {
                $query->where('type', Account::TYPE_EXPENSE);
            })->search('code', $this->search)
            ->paginate(setting('default.list_limit', '25'));

        $this->totalDecreases = $this->subTotalExpenseDecrease + $this->subTotalIncomeDecrease;
        $this->totalIncrements = $this->subTotalExpenseIncrement + $this->subTotalIncomeIncrement;
        return view('livewire.budget.reforms.create-reform', compact('accounts'));
    }

    public function updatedTypeBudgetIncome()
    {
        $this->typeBudgetExpense = false;
    }

    public function updatedTypeBudgetExpense()
    {
        $this->typeBudgetIncome = false;
    }

    public function updatedAccountSelected()
    {
        $this->account = Account::find($this->accountSelected);
    }

    public function updatedTypeReformSelected()
    {
        $this->reset(
            [
                'typeBudgetIncome',
                'typeBudgetExpense',
                'readOnly',
            ]);
        if ($this->typeReformSelected == Transaction::REFORM_TYPE_TRANSFER) {
            $this->typeBudgetIncome = false;
            $this->typeBudgetExpense = true;
            $this->readOnly = true;
            $this->reset(['arrayReformsIncomes', 'subTotalIncomeDecrease', 'subTotalIncomeIncrement']);
        }
    }

    public function addReform()
    {
        if ($this->decrease > $this->account->balance->getAmount() / 100) {
            flash('El valor de la cuenta es menor a la disminución')->warning()->livewire($this);
        } else {
            switch ($this->typeReformSelected) {
                case Transaction::REFORM_TYPE_TRANSFER:
                    $this->isTransfer();
                    break;
                case Transaction::REFORM_TYPE_DECREASE || Transaction::REFORM_TYPE_INCREMENT;
                    $this->isDecreaseOrIncrement();
                    break;
            }
            $this->reset(['increment', 'decrease']);
        }
    }

    public function deleteItemExpense($index)
    {
        $this->subTotalExpenseIncrement -= $this->arrayReformsExpenses[$index]['credit'];
        $this->subTotalExpenseDecrease -= $this->arrayReformsExpenses[$index]['debit'];
        unset($this->arrayReformsExpenses[$index]);
    }

    public function deleteItemIncome($index)
    {
        $this->subTotalIncomeIncrement -= $this->arrayReformsExpenses[$index]['debit'];
        $this->subTotalIncomeDecrease -= $this->arrayReformsExpenses[$index]['credit'];
        unset($this->arrayReformsIncomes[$index]);
    }

    public function saveReform()
    {
        if ($this->typeReformSelected) {
            $this->transaction->reform_type = $this->typeReformSelected;
            if ($this->totalIncrements == $this->totalDecreases) {
                $this->transaction->status = Balanced::label();
            } else {
                $this->transaction->status = Digited::label();
            }
            $this->transaction->save();
            foreach ($this->arrayReformsExpenses as $itemExpense) {
                if ($itemExpense['credit'] > 0) {
                    $this->transaction->credit($itemExpense['credit'], null, $itemExpense['id']);
                } else {
                    $this->transaction->debit($itemExpense['debit'], null, $itemExpense['id']);
                }
            }
            foreach ($this->arrayReformsIncomes as $itemIncome) {
                if ($itemIncome['credit'] > 0) {
                    $this->transaction->credit($itemIncome['credit'], null, $itemIncome['id']);
                } else {
                    $this->transaction->debit($itemIncome['debit'], null, $itemIncome['id']);

                }
            }
            $transactionGeneral = Transaction::where('year', $this->transaction->year)
                ->where('type', Transaction::TYPE_PROFORMA)->first();
            flash('Reforma Creada Exitosamente')->success();
            return redirect()->route('budgets.reforms', $transactionGeneral);
        }
    }

    public function resetCreate()
    {
        $this->reset(
            [
                'account',
                'typeReformSelected',
                'countRegisterSelect',
                'typeBudgetIncome',
                'typeBudgetExpense',
                'accountSelected',
                'increment',
                'decrease',
                'readOnly',
                'arrayReformsExpenses',
                'arrayReformsIncomes',
                'search',
                'subTotalIncomeIncrement',
                'subTotalIncomeDecrease',
                'subTotalExpenseIncrement',
                'subTotalExpenseDecrease',
                'totalIncrements',
                'totalDecreases',
            ]
        );
    }

    public function isTransfer()
    {
        if ($this->account->type == Account::TYPE_EXPENSE) {
            $this->arrayReformsExpenses[] =
                [
                    'id' => $this->account->id,
                    'code' => $this->account->code,
                    'debit' => $this->decrease ? $this->decrease : '0',
                    'credit' => $this->increment ? $this->increment : '0',
                    'transaction_id' => $this->transaction->id,
                    'company_id' => session('company_id'),
                ];
            $this->subTotalExpenseIncrement += $this->increment;
            $this->subTotalExpenseDecrease += $this->decrease;
        } else {
            $this->arrayReformsIncomes[] = [
                'id' => $this->account->id,
                'code' => $this->account->code,
                'debit' => $this->increment ? $this->increment : '0',
                'credit' => $this->decrease ? $this->decrease : '0',
                'transaction_id' => $this->transaction->id,
                'company_id' => session('company_id'),
            ];
            $this->subTotalIncomeDecrease += $this->decrease;
            $this->subTotalIncomeIncrement += $this->increment;
        }
    }

    public function isDecreaseOrIncrement()
    {
        if ($this->account->type == Account::TYPE_EXPENSE) {
            $this->arrayReformsExpenses[] =
                [
                    'id' => $this->account->id,
                    'code' => $this->account->code,
                    'debit' => $this->decrease ? $this->decrease : '0.00',
                    'credit' => $this->increment ? $this->increment : '0.00',
                    'transaction_id' => $this->transaction->id,
                    'company_id' => session('company_id'),
                ];
            $this->subTotalExpenseIncrement += $this->decrease;
            $this->subTotalExpenseDecrease += $this->increment;
        } else {
            $this->arrayReformsIncomes[] = [
                'id' => $this->account->id,
                'code' => $this->account->code,
                'debit' => $this->increment ? $this->increment : '0',
                'credit' => $this->decrease ? $this->decrease : '0',
                'transaction_id' => $this->transaction->id,
                'company_id' => session('company_id'),
            ];
            $this->subTotalIncomeDecrease += $this->decrease;
            $this->subTotalIncomeIncrement += $this->increment;
        }
    }
}
