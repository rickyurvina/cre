<?php

namespace App\Http\Livewire\Budget\Commitments;

use App\Models\Budget\Account;
use App\Models\Budget\Transaction;
use App\Models\Poa\PoaActivity;
use App\Models\Projects\Activities\Task;
use App\States\Transaction\Approved;
use App\States\Transaction\Rejected;
use Livewire\Component;

class ShowCommitment extends Component
{

    public $transaction;
    public $viewProjectActivity = false;
    public $viewPoaActivity = false;
    public $poaActivity;
    public $projectActivity;
    public $expensesPoa;
    public $expensesProject;
    public $transactionDetails;
    public string $description = '';
    public array $commitmentsValues = [];
    public $certification;

    protected $listeners = ['loadTransaction'];

    public function mount(Transaction $certification)
    {
        $this->certification = $certification;
    }

    public function loadTransaction(int $id)
    {
        $this->transaction = Transaction::find($id);
        $this->transactionDetails = $this->transaction->transactions;
        $account = $this->transaction->transactions->first()->account;
        if (isset($account->accountable->program)) {
            $this->poaActivity = PoaActivity::find($account->accountable_id);
            $this->description = $this->transaction->description;
            $this->viewPoaActivity = true;
            self::loadPoaActivity($this->poaActivity->id);
        } else {
            $this->projectActivity = Task::find($account->accountable_id);
            $this->description = $this->transaction->description;
            $this->viewProjectActivity = true;
            self::loadProjectActivity($this->projectActivity->id);
        }
        foreach ($this->transactionDetails as $item) {
            $this->commitmentsValues[$item->account_id] = $item->debit->getAmount() / 100;
        }
    }

    public function render()
    {
        return view('livewire.budget.commitments.show-commitment');
    }

    public function resetForm()
    {
        $this->reset(
            [
                'transaction',
                'viewProjectActivity',
                'viewPoaActivity',
                'poaActivity',
                'projectActivity',
                'expensesPoa',
                'expensesProject',
                'transactionDetails',
                'description',
                'commitmentsValues',
            ]);
        $this->emit('refreshCertifications');

    }

    public function loadPoaActivity(int $activityId)
    {
        $expenses = Account::where([
                ['type', Account::TYPE_EXPENSE],
                ['accountable_id', $activityId],
                ['accountable_type', PoaActivity::class],
                ['year', $this->transaction->year],
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
                ['year', $this->transaction->year],
            ]
        );

        if ($expenses->count() > 0) {
            $this->expensesProject = $expenses->get();
            $this->viewProjectActivity = true;
        } else {
            flash('No tiene partidas asignadas')->warning()->livewire($this);
        }
    }

    public function approveCommitment()
    {
        $this->transaction->status = Approved::label();
        $this->transaction->approved_by = user()->id;
        $this->transaction->approved_date = now();
        $this->transaction->save();
        $this->resetForm();
        $this->emit('toggleShowCommitment');

    }

    public function declineCommitment()
    {
        $this->transaction->status = Rejected::label();
        $this->transaction->approved_by = user()->id;
        $this->transaction->approved_date = now();
        $this->transaction->save();
        $this->resetForm();
        $this->emit('toggleShowCommitment');
    }
}
