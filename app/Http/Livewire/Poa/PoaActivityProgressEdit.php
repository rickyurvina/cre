<?php

namespace App\Http\Livewire\Poa;

use App\Jobs\Poa\UpdatePoaActivityProgress;
use App\Models\Indicators\Indicator\Indicator;
use App\Models\Indicators\Units\IndicatorUnits;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaActivityIndicator;
use App\Traits\Jobs;
use Livewire\Component;

class PoaActivityProgressEdit extends Component
{
    use Jobs;

    public $poaId = null;

    public $activityId = null;

    public $progress = [];

    public $indicatorUnitName;

    public $progressType = null;// Men Women Progress type or not

    public $currentMonth = null;

    public $poaActivity = null;

    protected $listeners = ['activitySelected', 'resetForm'];

    public function render()
    {
        $this->currentMonth = date("m");
        return view('livewire.poa.poa-activity-progress-edit');
    }

    /**
     * Load activity indicator progress
     *
     * @param $activityId
     * @param $poaId
     */
    public function activitySelected($activityId, $poaId)
    {
        $this->progress = [];
        $this->activityId = $activityId;
        $this->poaId = $poaId;
        $this->poaActivity = PoaActivity::with(['poaActivityIndicator.goalIndicator', 'indicator.indicatorUnit'])->find($activityId);

        $frequency = $this->poaActivity->indicator->frequency;
        $menWomenProgressType = trim($this->poaActivity->indicator->indicatorUnit->abbreviation);
        $this->progressType = $menWomenProgressType === 'PCap' || $menWomenProgressType === 'PA';
        $poaActivityDetails = $this->poaActivity->poaActivityIndicator;
        foreach ($poaActivityDetails as $poaActivityDetail) {
            $element = [];
            $element['id'] = $poaActivityDetail->id;
            $element['period'] = $poaActivityDetail->period;
            $element['year'] = $poaActivityDetail->year;
            $element['month'] = substr($poaActivityDetail->start_date, 5, 2);
            $element['monthName'] = Indicator::FREQUENCIES[$frequency][$poaActivityDetail->period];
            $element['progress'] = $poaActivityDetail->progress;
            $element['menProgress'] = $poaActivityDetail->men_progress;
            $element['womenProgress'] = $poaActivityDetail->women_progress;
            $element['menWomenProgressType'] = $this->progressType;
            $element['goal'] = $poaActivityDetail->goal;
            array_push($this->progress, $element);
        }
        $this->indicatorUnitName = $this->poaActivity->indicator->indicatorUnit->name;
    }

    /**
     * Update Activity indicator progress
     */
    public function submitProgress()
    {
        $response = $this->ajaxDispatch(new UpdatePoaActivityProgress($this->activityId, $this->progress));
        if ($response['success']) {
            flash(trans_choice('messages.success.updated', 0, ['type' => __('general.poa_activity_progress')]))->success()->livewire($this);
            $this->progress = [];
            $this->emit('goalProgressUpdated');
            $this->emit('toggleModalProgressActivity');
        } else {
            $this->dispatchBrowserEvent('alert', ['title' => $response['message'], 'icon' => 'error']);
        }
    }

    /**
     * Reset Form on Cancel
     *
     */
    public function resetForm()
    {
        $this->progress = [];
        $this->reset([
            'progress',
            'currentMonth',
            'poaId',
            'poaActivity',
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

}