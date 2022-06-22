<?php

namespace App\Http\Livewire\AdministrativeTasks;

use App\Models\AdministrativeTasks\AdministrativeTask;
use App\Models\Auth\User;
use App\Models\Projects\Project;
use App\Models\Projects\Stakeholders\ProjectCommunicationMatrix;
use App\Traits\Jobs;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class GeneralAdministrativeTask extends Component
{
    use WithPagination, Jobs;

    public $projects;
    public $selectedProjects = [];
    public $search = '';
    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function render()
    {
        $this->projects = Project::whereIn('phase', [Project::PHASE_PLANNING, Project::PHASE_IMPLEMENTATION])->get();
        if (user()->cannot('manage-administrativeTasks-admin' || 'view-administrativeTasks-admin')) {
            abort(403);
        } else {
            $administrativeTasks = AdministrativeTask::with([
                'responsible',
                'subTasks',
                'project'
            ])
                ->orderBy('id', 'asc')
                ->when(count($this->selectedProjects) > 0, function (Builder $query) {
                    $query->whereIn('project_id', $this->selectedProjects);
                })
                ->search('name', $this->search)
                ->paginate(setting('default.list_limit', '25'));
        }
        return view('livewire.administrativeTasks.general-administrative-task', compact('administrativeTasks'));
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->selectedProjects = [];
    }

}