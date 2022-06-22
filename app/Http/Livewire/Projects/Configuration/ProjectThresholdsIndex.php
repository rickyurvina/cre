<?php

namespace App\Http\Livewire\Projects\Configuration;

use App\Models\Projects\Configuration\ProjectThreshold;
use App\Models\Projects\Project;
use App\Models\Projects\ProjectLearnedLessons;
use App\Traits\Jobs;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectThresholdsIndex extends Component
{
    use WithPagination, Jobs;

    public array $selectedProjects = [];
    public $search = '';

    protected $listeners=['refreshIndexThresholds'=>'$refresh'];

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function render()
    {

        $thresholds = ProjectThreshold::with(['thresholdable'])->when(count($this->selectedProjects) > 0, function (Builder $query) {
            $query->where('thresholdable_id', $this->selectedProjects)
                ->where('thresholdable_type', Project::class);
        })->orderBy('id','desc')
            ->search('description', $this->search)
            ->paginate(setting('default.list_limit', '25'));

        return view('livewire.projects.configuration.project-thresholds-index', compact('thresholds'));
    }
}
