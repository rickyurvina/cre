<?php

namespace App\Http\Livewire\Budget\Reforms;

use App\Models\Budget\Account;
use App\Models\Budget\Transaction;
use App\Models\Budget\TransactionDetail;
use App\States\Transaction\Balanced;
use App\States\Transaction\Digited;
use Livewire\Component;
use Livewire\WithPagination;

class EditReform extends Component
{
    use WithPagination;

    public $transaction;
    public $account;
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
        $transactionDetails = TransactionDetail::where('transaction_id', $this->transaction->id)->get()->groupBy('account_id');
        foreach ($transactionDetails as $items) {
            foreach ($items as $item) {
                if ($item->account->type == Account::TYPE_INCOME) {
                    $this->arrayReformsIncomes [] =
                        [
                            'id' => $item->id,
                            'id_account' => $item->account->id,
                            'code' => $item->account->code,
                            'name' => $item->account->name,
                            'debit' => $item->debit->getAmount(),
                            'credit' => $item->credit->getAmount(),
                            'transaction_id' => $this->transaction->id,
                            'company_id' => session('company_id'),
                        ];
                    $this->subTotalIncomeDecrease += $item->credit->getAmount();
                    $this->subTotalIncomeIncrement += $item->debit->getAmount();
                } else {
                    $this->arrayReformsExpenses [] =
                        [
                            'id' => $item->id,
                            'id_account' => $item->account->id,
                            'code' => $item->account->code,
                            'name' => $item->account->name,
                            'debit' => $item->debit->getAmount(),
                            'credit' => $item->credit->getAmount(),
                            'transaction_id' => $this->transaction->id,
                            'company_id' => session('company_id'),
                        ];
                    $this->subTotalExpenseIncrement += $item->debit->getAmount();
                    $this->subTotalExpenseDecrease += $item->credit->getAmount();
                }
            }
        }
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
        return view('livewire.budget.reforms.edit-reform', compact('accounts'));
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

    public function addReform()
    {
        if ($this->decrease > $this->account->balance->getAmount() / 100) {
            flash('El valor de la cuenta es menor a la disminución')->warning()->livewire($this);
        } else {
            $this->isDecreaseOrIncrement();
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

    public function isDecreaseOrIncrement()
    {
        if ($this->account->type == Account::TYPE_EXPENSE) {
            $this->arrayReformsExpenses[] =
                [
                    'id_account' => $this->account->id,
                    'code' => $this->account->code,
                    'name' => $this->account->name,
                    'debit' => $this->decrease ? $this->decrease : '0',
                    'credit' => $this->increment ? $this->increment : '0',
                    'transaction_id' => $this->transaction->id,
                    'company_id' => session('company_id'),
                ];
            $this->subTotalExpenseIncrement += $this->decrease * 100;
            $this->subTotalExpenseDecrease += $this->increment * 100;
        } else {
            $this->arrayReformsIncomes[] = [
                'id_account' => $this->account->id,
                'code' => $this->account->code,
                'name' => $this->account->name,
                'debit' => $this->increment ? $this->increment : '0',
                'credit' => $this->decrease ? $this->decrease : '0',
                'transaction_id' => $this->transaction->id,
                'company_id' => session('company_id'),
            ];
            $this->subTotalIncomeDecrease += $this->decrease * 100;
            $this->subTotalIncomeIncrement += $this->increment * 100;
        }
    }

    public function saveReform()
    {
//        $this->transaction->transactions->each->delete();
        if ($this->totalIncrements == $this->totalDecreases) {
            $this->transaction->status = Balanced::label();
        } else {
            $this->transaction->status = Digited::label();
        }
        $this->transaction->save();

        foreach ($this->arrayReformsExpenses as $itemExpense) {
            if (!isset($itemExpense['id'])) {
                if ($itemExpense['credit'] > 0) {
                    $this->transaction->credit($itemExpense['credit'], null, $itemExpense['id_account']);
                } else {
                    $this->transaction->debit($itemExpense['debit'], null, $itemExpense['id_account']);
                }
            }
        }
        foreach ($this->arrayReformsIncomes as $itemIncome) {
            if (!isset($itemIncome['id'])) {
                if ($itemIncome['credit'] > 0) {
                    $this->transaction->credit($itemIncome['credit'], null, $itemIncome['id_account']);
                } else {
                    $this->transaction->debit($itemIncome['debit'], null, $itemIncome['id_account']);
                }
            }
        }

        $transactionGeneral = Transaction::where('year', $this->transaction->year)
            ->where('type', Transaction::TYPE_PROFORMA)->first();
        flash('Reforma Actualizada Exitosamente')->success();
        return redirect()->route('budgets.reforms', $transactionGeneral);
    }

    public function resetCreate()
    {
        $this->reset(
            [
                'account',
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
}
