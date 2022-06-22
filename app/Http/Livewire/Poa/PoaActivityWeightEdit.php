<?php

namespace App\Http\Livewire\Poa;

use App\Jobs\Poa\UpdatePoaActivityWeight;
use App\Models\Poa\PoaActivity;
use App\Traits\Jobs;
use Livewire\Component;

class PoaActivityWeightEdit extends Component
{
    use Jobs;

    public $poaId;
    public $activityId;
    public $poaActivityCost;
    public $poaActivityImpact;
    public $poaActivityComplexity;

    protected $listeners = ['activitySelected', 'resetForm'];

    public function render()
    {
        return view('livewire.poa.poa-activity-weight-edit');
    }

    /**
     * Load activity indicator goals
     *
     * @param $activityId
     * @param $poaId
     */
    public function activitySelected($activityId, $poaId)
    {
        $this->activityId = $activityId;
        $this->poaId = $poaId;
        $poaActivity = PoaActivity::find($activityId);
        $this->poaActivityCost = $poaActivity->cost;
        $this->poaActivityImpact = $poaActivity->impact;
        $this->poaActivityComplexity = $poaActivity->complexity;
    }

    /**
     * Update activity weight parameters
     *
     */
    public function submit()
    {
        $this->validate([
            'poaActivityCost' => 'required',
            'poaActivityImpact' => 'required',
            'poaActivityComplexity' => 'required',
        ]);

        $data = [
            'cost' => $this->poaActivityCost,
            'impact' => $this->poaActivityImpact,
            'complexity' => $this->poaActivityComplexity,
        ];

        $response = $this->ajaxDispatch(new UpdatePoaActivityWeight($this->activityId, $this->poaId, $data));

        if ($response['success']) {
            flash(trans_choice('messages.success.updated', 0, ['type' => __('general.poa_edit_activity_weight')]))->success()->livewire($this);
            $this->emit('activityWeightUpdate');
            $this->emit('toggleModalActivityWeight');
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
        $this->reset();
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
