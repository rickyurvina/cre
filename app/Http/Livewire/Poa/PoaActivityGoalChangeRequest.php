<?php

namespace App\Http\Livewire\Poa;

use App\Jobs\Poa\CreatePoaIndicatorGoalChangeRequest;
use App\Models\Auth\User;
use App\Models\Indicators\Indicator\Indicator;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaActivityIndicator;
use App\Models\Poa\PoaIndicatorGoalChangeRequest;
use App\Traits\Jobs;
use App\Traits\Uploads;
use Livewire\Component;
use Livewire\WithFileUploads;

class PoaActivityGoalChangeRequest extends Component
{
    use Jobs;

    use WithFileUploads, Uploads;

    public $poaId;
    public $activityIndicators = [];
    public $activityIndicatorCollection;
    public $goalMonth;
    public $goalCurrentValue;
    public $activityId;
    public $goalRequestJustificationForm;
    public $showAddRequest = false;
    public $goals = [];
    public $files;
    public $activity;

    protected $listeners = ['requestGoalChange'];

    public function mount($activityId, $poaId)
    {
        $this->poaId = $poaId;
        $this->activityId=$activityId;
        $this->activityIndicatorCollection = PoaActivityIndicator::where('poa_activity_id', $activityId)->get(); // TODO hot fix
        foreach ($this->activityIndicatorCollection as $item) {
            $element = [];
            $poaIndicatorGoalChangeRequest = PoaIndicatorGoalChangeRequest::where('poa_activity_indicator_id', $item->id)
                ->where('status', PoaIndicatorGoalChangeRequest::STATUS_OPEN)
                ->first();
            if (!$poaIndicatorGoalChangeRequest) {
                $element['id'] = $item->id;
                $element['month'] = Indicator::FREQUENCIES[12][$item->period];
                array_push($this->activityIndicators, $element);
            }
        }
        $this->goals = [];
        $activity = PoaActivity::find($activityId);
        $this->activity = $activity;
        $frequency = $activity->indicator->frequency;
        $poaActivityDetails = $activity->poaActivityIndicator;
        foreach ($poaActivityDetails as $poaActivityDetail) {
            $element = [];
            $element['id'] = $poaActivityDetail->id;
            $element['period'] = $poaActivityDetail->period;
            $element['indicator_id'] = $poaActivityDetail->indicator_id;
            $element['year'] = $poaActivityDetail->year;
            $element['month'] = substr($poaActivityDetail->start_date, 5, 2);
            $element['monthName'] = Indicator::FREQUENCIES[$frequency][$poaActivityDetail->period];
            $element['goal'] = $poaActivityDetail->goal;
            $element['request_number'] = $poaActivityDetail->goal;
            $element['poa_activity_id'] = $this->activityId;
            $element['request'] = '';
            array_push($this->goals, $element);
        }
    }

    public function render()
    {
        $listRequests = PoaIndicatorGoalChangeRequest::whereIn('poa_activity_indicator_id', $this->activity->poaActivityIndicator->pluck('id'))->get()->groupBy('request_number');

        return view('livewire.poa.poa-activity-goal-change-request', compact('listRequests'));
    }

    public function submitRequest()
    {
        $this->validate([
            'goalRequestJustificationForm' => 'required',
        ]);
        $listRequests = PoaIndicatorGoalChangeRequest::whereIn('poa_activity_indicator_id', $this->activity->poaActivityIndicator->pluck('id'))->get();
        $maxNumberRequest = $listRequests->max('request_number');

        foreach ($this->goals as $item) {

            $data = [
                'poa_activity_indicator_id' => $item['id'],
                'indicator_id' => $item['indicator_id'],
                'period' => $item['period'],
                'old_value' => $item['goal'],
                'new_value' => $item['request'],
                'request_justification' => $this->goalRequestJustificationForm,
                'request_user' => user()->id,
                'request_number' => $maxNumberRequest + 1,
                'poa_activity_id' => $this->activityId,
                'status' => PoaIndicatorGoalChangeRequest::STATUS_OPEN,
            ];
            if ($item['request'] > 0) {
                $response = $this->ajaxDispatch(new CreatePoaIndicatorGoalChangeRequest($data));
                if ($response['success']) {
                    if ($this->files) {
                        $media = $this->getMedia($this->files, 'poa')->id;
                        $response['data']->attachMedia($media, 'file');
                    }
                } else {
                    $this->dispatchBrowserEvent('alert', ['title' => $response['message'], 'icon' => 'error']);
                }
            }
        }
        $this->resetForm();

        $this->dispatchBrowserEvent('alert', [
            'title' => trans_choice('messages.success.added', 1, ['type' => __('general.poa_request')]),
            'icon' => 'success'
        ]);

    }

    public function loadCurrentValue()
    {
        $item = $this->activityIndicatorCollection->find($this->goalMonth);
        $this->goalCurrentValue = $item->goal;
    }

    /**
     * Reset Form on Cancel
     *
     */
    public function resetForm()
    {
        $this->resetValidation();
        $this->reset(['goalRequestJustificationForm', 'showAddRequest']);
        $this->files = [];
    }
}
