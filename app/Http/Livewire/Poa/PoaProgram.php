<?php

namespace App\Http\Livewire\Poa;

use App\Jobs\Poa\CreatePoaActivityTemplate;
use App\Models\Admin\Company;
use App\Models\Auth\User;
use App\Models\Poa\Poa;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaProgram as ProgramPoa;
use App\Traits\Jobs;
use Livewire\Component;

class PoaProgram extends Component
{
    use Jobs;

    public $poaId;
    public $poa;

    public $programs;

    public $poaIndicatorConfigs;

    public array $status = [];

    public array $activitySelected = [];

    public $show = false;

    public $company;

    protected $listeners = [
        'programAdded' => 'render',
        'colorPaletteChanged' => 'render',
        'activityDeleted' => 'render',
        'commentAdded' => 'render',
        'selectInputChanged' => 'render',
        'activityCreated' => 'render',
        'indicatorGoalChangeRequestCreated' => 'render',
        'goalProgressUpdated' => 'render',
        'activityWeightUpdate' => 'render',
    ];

    public function render()
    {
        $this->company = Company::find(session('company_id'));
        $this->status =
            [
                PoaActivity::STATUS_SCHEDULED => PoaActivity::STATUS_SCHEDULED,
                PoaActivity::STATUS_IN_PROGRESS => PoaActivity::STATUS_IN_PROGRESS,
                PoaActivity::STATUS_FINISHED => PoaActivity::STATUS_FINISHED,
            ];
        if (!$this->poaId) {
            $currentYear = date('Y');
            $currentPoa = Poa::enabled()->where('year', $currentYear)->first();
            if ($currentPoa) {
                $this->poaId = $currentPoa->id;
            }
        }
        $this->loadPrograms();
        $users = User::get();
        return view('livewire.poa.poa-program', compact('users'));
    }

    /**
     * Load POA programs
     *
     */
    public function loadPrograms()
    {
        if ($this->poaId) {
            $poa = Poa::with(['programs.activities' => function ($query) {
                $query->orderBy('name');
            },
                'programs.planDetail.parent',
                'programs.planDetail.indicators.indicatorGoals',
                'programs.planDetail.indicators.poaActivities.responsible',
                'programs.planDetail.indicators.poaActivities.poaActivityIndicator',
                'programs.planDetail.indicators.poaActivities' => function ($query) {
                    $query->withCount('comments');
                }
            ])->find($this->poaId);
            $this->poa = $poa;
            $this->programs = $poa->programs;
            $poaIndicatorConfigs = \App\Models\Poa\PoaIndicatorConfig::where('poa_id', $this->poaId)
                ->where('selected', true)
                ->get()
                ->toArray();
            $this->poaIndicatorConfigs = $poaIndicatorConfigs;
        }
    }

    /**
     * Save Activity Template
     *
     */
    public function saveTemplate($id)
    {
        $activity = PoaActivity::findOrFail($id);

        $data = [
            'name' => $activity->name,
            'cost' => $activity->cost,
            'impact' => $activity->impact,
            'complexity' => $activity->complexity
        ];

        $response = $this->ajaxDispatch(new CreatePoaActivityTemplate($data));

        if ($response['success']) {
            $this->dispatchBrowserEvent('alert', ['title' => trans('general.poa_create_activity_template'), 'icon' => 'success']);
        } else {
            $this->dispatchBrowserEvent('alert', ['title' => trans('general.poa_create_activity_template_error'), 'icon' => 'error']);
        }

        $this->render();
    }

    public function updateSelected($id)
    {
        if (in_array($id, $this->activitySelected)) {
            $this->activitySelected = array_diff($this->activitySelected, [$id]);
        } else {
            array_push($this->activitySelected, $id);
        }
    }

    public function deleteActivity($id)
    {
        if (is_array($id)) {
            foreach ($this->activitySelected as $item) {
                PoaActivity::find($item)->delete();
            }
        } else {
            PoaActivity::find($id)->delete();
        }
        flash(trans_choice('messages.success.deleted', 1, ['type' => trans_choice('general.activities', 1)]))->success()->livewire($this);

        $this->activitySelected = [];
        $this->show = false;
    }

    public function delete($id)
    {
        if (is_array($id)) {
            foreach ($this->resultSelected as $item) {
                ProgramPoa::find($item)->delete();
            }
        } else {
            ProgramPoa::find($id)->delete();
        }

        $this->emit('calcWeight', $this->poaId);
        $this->emit('renderWeights', $this->poaId);
        $this->render();
    }

}