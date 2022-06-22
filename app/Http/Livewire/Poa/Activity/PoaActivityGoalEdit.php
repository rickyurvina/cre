<?php

namespace App\Http\Livewire\Poa\Activity;

use App\Jobs\Poa\UpdatePoaActivityGoal;
use App\Models\Indicators\Indicator\Indicator;
use App\Models\Poa\Poa;
use App\Traits\Jobs;
use Livewire\Component;

class PoaActivityGoalEdit extends Component
{
    use Jobs;

    public $activityId = null;

    public $goals = [];

    public $indicatorUnitName;

    public $activity;

    public $readOnlyGoal = false;

    public $readOnlyProgress = false;

    public function mount($activity, $readOnly = false)
    {
        if ($activity->program->poa->phase->isActive(\App\States\Poa\Planning::class)) {
            $this->readOnlyProgress = true;
        } else {
            $this->readOnlyGoal = true;
        }
        $this->goals = [];
        $this->activityId = $activity->id;
        $frequency = $activity->indicator->frequency;
        $poaActivityDetails = $activity->poaActivityIndicator;

        foreach ($poaActivityDetails as $poaActivityDetail) {
            $element = [];
            $element['id'] = $poaActivityDetail->id;
            $element['period'] = $poaActivityDetail->period;
            $element['year'] = $poaActivityDetail->year;
            $element['month'] = substr($poaActivityDetail->start_date, 5, 2);
            $element['monthName'] = Indicator::FREQUENCIES[$frequency][$poaActivityDetail->period];
            $element['goal'] = $poaActivityDetail->goal;
            $element['progress'] = $poaActivityDetail->progress;
            array_push($this->goals, $element);
        }
        $this->indicatorUnitName = $activity->indicator->indicatorUnit->name;
    }

    public function getTotalProperty()
    {
        return array_sum(array_column($this->goals, 'goal'));
    }

    public function getProgressProperty()
    {
        return array_sum(array_column($this->goals, 'progress'));
    }

    public function render()
    {
        return view('livewire.poa.activity.poa-activity-goal-edit');
    }

    /**
     * Update Activity indicator goals
     */
    public function submitGoals()
    {
        $response = $this->ajaxDispatch(new UpdatePoaActivityGoal($this->activityId, $this->goals));
        if ($response['success']) {
            flash(trans_choice('messages.success.updated', 1, ['type' => __('general.poa_activity_goal')]))->success()->livewire($this);
        } else {
            $this->dispatchBrowserEvent('alert', ['title' => $response['message'], 'icon' => 'error']);
        }
    }

    public function resetForm()
    {

    }
}
