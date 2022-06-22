<?php

namespace App\Http\Livewire\Budget\Reforms;

use App\Models\Budget\Account;
use App\Models\Budget\Transaction;
use App\Models\Budget\TransactionDetail;
use App\States\Transaction\Approved;
use Livewire\Component;

class ShowReform extends Component
{
    public $transaction;
    public $totalIncrements = 0;
    public $totalDecreases = 0;
    public array $arrayReformsExpenses = [];
    public array $arrayReformsIncomes = [];
    public $subTotalIncomeIncrement = 0;
    public $subTotalIncomeDecrease = 0;
    public $subTotalExpenseIncrement = 0;
    public $subTotalExpenseDecrease = 0;

    protected $listeners = ['loadTransaction'];

    public function loadTransaction(int $id)
    {
        $this->transaction = Transaction::find($id);
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
                            'debit' => money($item->debit->getAmount()),
                            'credit' => money($item->credit->getAmount()),
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
                            'debit' => money($item->debit->getAmount()),
                            'credit' => money($item->credit->getAmount()),
                            'transaction_id' => $this->transaction->id,
                            'company_id' => session('company_id'),
                        ];
                    $this->subTotalExpenseIncrement += $item->debit->getAmount();
                    $this->subTotalExpenseDecrease += $item->credit->getAmount();
                }
            }
        }
        $this->totalDecreases = $this->subTotalExpenseDecrease + $this->subTotalIncomeDecrease;
        $this->totalIncrements = $this->subTotalExpenseIncrement + $this->subTotalIncomeIncrement;
    }

    public function render()
    {
        return view('livewire.budget.reforms.show-reform');
    }

    public function resetForm()
    {
        $this->reset(['transaction']);
    }

    public function saveReform()
    {
        $this->transaction->status = Approved::label();
        $this->transaction->approved_by = user()->id;
        $this->transaction->save();
        flash('Reforma Aprobada Exitosamente')->success();
        return redirect()->route('budgets.reforms', $this->transaction);
    }
}
