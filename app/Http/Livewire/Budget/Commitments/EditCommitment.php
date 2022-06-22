<?php

namespace App\Http\Livewire\Budget\Commitments;

use App\Models\Budget\Account;
use App\Models\Budget\CertificationsCommitments;
use App\Models\Budget\Transaction;
use App\Models\Poa\PoaActivity;
use App\Models\Projects\Activities\Task;
use Cknow\Money\Money;
use Livewire\Component;

class EditCommitment extends Component
{

    public $commitment;
    public $certification;
    public $viewProjectActivity = false;
    public $viewPoaActivity = false;
    public $poaActivity;
    public $projectActivity;
    public $expensesPoa;
    public $expensesProject;
    public $commitmentDetails;
    public string $description = '';
    public array $commitmentsValues = [];

    protected $rules = [
        'description' => 'required',
    ];

    public function mount(Transaction $commitment, Transaction $certification)
    {
        $commitment->load(['transactions.account']);
        $this->commitment = $commitment;
        $this->certification = $certification;
        $this->commitmentDetails = $commitment->transactions;
        $account = $this->commitment->transactions->first()->account;
        if ($account->accountable_type === PoaActivity::class) {
            $this->poaActivity = PoaActivity::find($account->accountable_id);
            $this->description = $commitment->description;
            $this->viewPoaActivity = true;
            self::loadPoaActivity($this->poaActivity->id);
        } else {
            $this->projectActivity = Task::find($account->accountable_id);
            $this->description = $commitment->description;
            $this->viewProjectActivity = true;
            self::loadProjectActivity($this->projectActivity->id);
        }
        foreach ($this->commitmentDetails as $item) {
            $this->commitmentsValues[$item->account_id] = $item->debit->getAmount() / 100;
        }
    }

    public function render()
    {
        return view('livewire.budget.commitments.edit-commitment');
    }

    public function loadPoaActivity(int $activityId)
    {
        $expenses = Account::where([
                ['type', Account::TYPE_EXPENSE],
                ['accountable_id', $activityId],
                ['accountable_type', PoaActivity::class],
                ['year', $this->commitment->year],
            ]
        );
        if ($expenses->count() > 0) {
            $this->expensesPoa = $expenses->get();
            $this->viewPoaActivity = true;
        } else {
            flash('No tiene partidas asignadas')->warning()->livewire($this);
        }
    }

    public function loadProjectActivity(int $activityId)
    {
        $expenses = Account::where([
                ['type', Account::TYPE_EXPENSE],
                ['accountable_id', $activityId],
                ['accountable_type', Task::class],
                ['year', $this->commitment->year],
            ]
        );

        if ($expenses->count() > 0) {
            $this->expensesProject = $expenses->get();
            $this->viewProjectActivity = true;
        } else {
            flash('No tiene partidas asignadas')->warning()->livewire($this);
        }
    }

    public function updatedCommitmentsValues()
    {
        foreach ($this->commitmentsValues as $index => $item) {
            if ($this->expensesPoa) {
                $expense = $this->expensesPoa->find($index);
            } else {
                $expense = $this->expensesProject->find($index);
            }
            if ($expense->getCertifiedValues($this->certification->id)->getAmount() / 100 < $item) {
                flash('El valor no puede ser mayor al valor por comprometer')->warning()->livewire($this);
                unset($this->commitmentsValues[$index]);
            }
        }
    }

    public function saveCommitment()
    {
        $this->validate();
        if (count($this->commitmentsValues) === 0) {
            flash('No se han asignado valores a la certificación')->warning()->livewire($this);
        } else {
            foreach ($this->commitmentsValues as $index => $item) {
                $commitmentDetail = $this->commitmentDetails->where('account_id', $index)->first();
                $this->commitment->debitUpdate($item, $this->description, $commitmentDetail->id);
                $value = is_a($item, Money::class)
                    ? $item
                    : money_parse_by_decimal($item, Money::getDefaultCurrency());
                $dataCertificationCommitment = CertificationsCommitments::where('certification_id', $this->certification->id)
                    ->where('commitment_id', $this->commitment->id)->first();
                $dataCertificationCommitment->update(['amount' =>  $value ? $value->getAmount() : null]);
            }
            $this->commitment->description = $this->description;
            $this->commitment->save();
            flash('Guardado Exitosamente')->success();
            return redirect()->route('budgets.commitments', $this->certification);
        }
    }
}
