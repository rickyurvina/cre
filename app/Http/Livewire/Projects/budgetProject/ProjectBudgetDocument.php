<?php

namespace App\Http\Livewire\Projects\BudgetProject;

use App\Models\Budget\Account;
use App\Models\Budget\Transaction;
use App\Models\Projects\Activities\Task;
use App\Models\Projects\Project;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectBudgetDocument extends Component
{
    use WithPagination;

    public $transaction;

    public $projects;

    public $search = '';
    public $account;
    public $typeReformSelected = Transaction::REFORM_TYPE_INCREMENT;
    public $readOnly = false;
    public $typeBudgetIncome = true;
    public $typeBudgetExpense;
    public $levelIncomeSelected;
    public $project;
    public array $levelsIncomes = [];

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->levelsIncomes +=
            [
                0 => 'Fuente de Financiamiento'
            ];
        $this->levelsIncomes +=
            [
                1 => 'Item Presupuestario'
            ];

    }

    public function render()
    {
        $activities = Task::with([
            'responsible',
            'indicator',
            'accounts',
            'project'
        ])
            ->when(!empty($this->search), function (Builder $query) {
                $query->where(function ($q) {
                    $q->where('code', 'iLike', '%' . $this->search . '%')
                        ->orWhere('text', 'iLike', '%' . $this->search . '%');
                });
            })->where('project_id', $this->project->id)
            ->where('type', 'task')
            ->paginate(setting('default.list_limit', '25'));
        return view('livewire.projects.budget-project.project-budget-document', compact('activities'));
    }

    public function clearFilters()
    {
        $this->search = '';
    }

    public function updatedTypeBudgetIncome()
    {
        $this->typeBudgetExpense = false;
    }

    public function updatedTypeBudgetExpense()
    {
        $this->typeBudgetIncome = false;
    }

}
