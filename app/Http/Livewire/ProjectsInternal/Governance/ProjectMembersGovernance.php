<?php

namespace App\Http\Livewire\ProjectsInternal\Governance;

use App\Events\ProjectSubsidiaryUpdated;
use App\Jobs\Projects\ProjectCreateSubsidiary;
use App\Models\Admin\Company;
use App\Models\Admin\Department;
use App\Models\Auth\User;
use App\Models\Projects\Project;
use App\Models\Projects\ProjectMemberArea;
use App\Models\Projects\ProjectMemberSubsidiary;
use App\Traits\Jobs;
use Livewire\Component;

class ProjectMembersGovernance extends Component
{
    use Jobs;

    public $project;

    public string $subsidiary;

    public $subsidiaries = [];
    public $subsidiariesAux = [];
    public $subsidiariesSelect = [];

    public $area;
    public $areas = [];

    public $executorAreas = [];
    public $executorAreasAux = [];
    public $executorAreasSelect = [];

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->subsidiary = $this->project->company->name;
    }

    public function render()
    {
        return view('livewire.projectsInternal.governance.project-members-governance');
    }
}
