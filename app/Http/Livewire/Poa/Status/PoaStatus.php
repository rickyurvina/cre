<?php

namespace App\Http\Livewire\Poa\Status;

use App\Http\Livewire\Components\Modal;
use App\Models\Poa\Poa;

class PoaStatus extends Modal
{
    public $poa;

    public $resume = [];

    public $phase = false;

    public function mount(Poa $poa)
    {
        $this->poa = $poa;
        $this->poa->load(['poaIndicatorConfigs.indicator.poaActivities', 'poaIndicatorConfigs.indicator.indicatorUnit', 'activities.causer']);

        $poaIndicatorConfigs=$this->poa->poaIndicatorConfigs->where('selected', true);
        foreach ($poaIndicatorConfigs as $indicatorConfig) {
            $this->resume[] = [
                'indicator' => $indicatorConfig->indicator->name,
                'activityCount' => $indicatorConfig->indicator->poaActivities->count(),
                'goal' => $indicatorConfig->indicator->total_goal_value,
                'type' => $indicatorConfig->indicator->indicatorUnit->name,
            ];
        }
    }

    public function render()
    {
        return view('livewire.poa.status.poa-status');
    }

    public function changeStatus()
    {
        if($this->poa->status->to() instanceof \App\States\Poa\Reviewed) {
            if(user()->can('poa-review-poas')) {
                $this->poa->status->transitionTo($this->poa->status->to());
                flash(trans_choice('messages.success.updated', 0, ['type' => trans_choice('general.poa', 0)]))->success();
            } else {
                flash(trans_choice('messages.error.approve_permission_denied', 1, ['type' => trans_choice('general.poa', 0)]))->error();
                
            }
        } else if($this->poa->status->to() instanceof \App\States\Poa\Approved) {
            if (user()->can('poa-approve-poas')) {
                $this->poa->status->transitionTo($this->poa->status->to());
                flash(trans_choice('messages.success.updated', 0, ['type' => trans_choice('general.poa', 0)]))->success();
            } else {
                flash(trans_choice('messages.error.approve_permission_denied', 1, ['type' => trans_choice('general.poa', 0)]))->error();
                
            }
        }
        
        return redirect()->route('poas.activities.index', ['poa' => $this->poa->id]);
    }

    public function changePhase()
    {
        $this->poa->phase->transitionTo($this->poa->phase->to());
        flash(trans_choice('messages.success.updated', 0, ['type' => trans_choice('general.poa', 0)]))->success();
        return redirect()->route('poas.activities.index', ['poa' => $this->poa->id]);
    }
}
