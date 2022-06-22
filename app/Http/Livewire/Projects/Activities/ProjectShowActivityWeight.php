<?php

namespace App\Http\Livewire\Projects\Activities;

use App\Models\Projects\Project;
use App\Models\Projects\Activities\Task;
use Livewire\Component;

class ProjectShowActivityWeight extends Component
{
    public $sumWeights = null;
    public $projectId;
    public $even;

    public $activities = null;

    public $weights = [];

    protected $listeners = [
        'newActivity' => 'mount',
    ];

    public function mount(Project $project)
    {
        $this->projectId = $project;
        $activities = $project->tasks->where('type', 'project')
            ->where('parent', '<>', 'root');
        $i = 0;
        $this->even = false;
        foreach ($activities as $item) {
            if ($item->weight == null) {
                $this->even = true;
            }
            $this->weights[$i]['id'] = $item->id;
            $this->weights[$i++]['weight'] = $item->weight*100;
        }
        $this->activities = $activities;
    }

    public function render()
    {
        $totalActivities = count($this->weights);
        $evenWeight = 100;
        if ($totalActivities > 0) {
            $evenWeight = 100 / $totalActivities;
        }
        $this->sumWeights = 0;
        $i = 0;
        foreach ($this->weights as $item) {
            if ($this->even) {
                $this->sumWeights += $evenWeight;
                $this->weights[$i]['weight'] = number_format($evenWeight, 2, '.', ',');
            } else {
                $this->sumWeights += $item['weight'];
                $this->weights[$i]['weight'] = number_format($item['weight'], 2, '.', ',');
            }
            $i++;
        }
        $this->even = false;
        return view('livewire.projects.activities.project-show-activity-weight');
    }

    /**
     * Reset Form on Cancel
     *
     */
    public function resetForm()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->emit('closeModalResultsWeight');
        $this->emit('updateResultsActivities');
//        return redirect()->route('projects.activities', ['project' => $this->projectId]);
    }

    public function updatedWeights()
    {
        $this->sumWeights = 0;
        foreach ($this->weights as $weight) {
            $this->sumWeights += $weight['weight'] > 0 ? $weight['weight'] : 0;
        }
        $this->sumWeights = round($this->sumWeights, 2);
    }

    public function store()
    {
        foreach ($this->weights as $item) {
            Task::where('id', $item['id'])->update(['weight' => $item['weight']/100]);
        }
        $this->resetForm();
    }
}
