<?php

namespace App\Http\Livewire\Budget\Commitments;

use App\Models\Budget\CertificationsCommitments;
use App\Models\Budget\Transaction;
use App\States\Transaction\Override;
use Livewire\Component;
use Livewire\WithPagination;

class BudgetCommitmentIndex extends Component
{
    use WithPagination;

    public $certification;
    public $stateSelect;
    public $reformSelect;
    public $start_date;
    public $end_date;
    public $countRegisterSelect = 25;
    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $listeners = ['refreshCertifications' => '$refresh'];

    public function mount(Transaction $certification)
    {
        $this->certification = $certification;
    }

    public function render()
    {
        $certificationsCommitments = CertificationsCommitments::where('certification_id', $this->certification->id)->pluck('commitment_id')->toArray();
        $commitments = Transaction::with(['transactions.account'])
            ->whereIn('id', $certificationsCommitments)
            ->orderBy('number', 'desc')->where('year', $this->certification->year)
            ->when($this->stateSelect, function ($query) {
                $query->where('status', $this->stateSelect);
            })->where('type', Transaction::TYPE_COMMITMENT)
            ->search('description', $this->search)
            ->paginate(setting('default.list_limit', '25'));
        return view('livewire.budget.commitments.budget-commitment-index', compact('commitments'));
    }

    public function overrideTransaction(int $id)
    {
        $certification = Transaction::find($id);
        $certification->status = Override::label();
        $certification->save();
    }

    public function clearFilters()
    {
        $this->reset(['stateSelect', 'reformSelect', 'countRegisterSelect']);
    }

}
