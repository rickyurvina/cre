<?php

namespace App\Http\Livewire\Poa;

use App\Models\Indicators\Indicator\Indicator;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaActivityIndicator;
use Livewire\Component;
use App\Models\Poa\PoaProgram as Program;

class PoaAssignGoals extends Component
{
    protected $listeners = ['loadProgram'];

    public $goals = [];

    public $elementos = [];

    public ?Program $program = null;

    public $poa;

    public function loadProgram($programId)
    {
        $this->program = Program::with(['poaActivities.indicator', 'poaActivities.poaActivityIndicator.goalIndicator', 'poa'])->find($programId);
        $this->poa = $this->program->poa;
        $poaActivities = $this->program->poaActivities;

        foreach ($poaActivities as $index => $poaActivity) {
            $key = $poaActivity->indicator->name;
            $key2 = $poaActivity->name;
            $frequency = $poaActivity->indicator->frequency;
            foreach ($poaActivity->poaActivityIndicator as $goalPoaActivity) {
                if ($goalPoaActivity->year == date("Y")) {
                    $element = [];
                    $element['id'] = $goalPoaActivity->id;
                    $element['period'] = $goalPoaActivity->period;
                    $element['year'] = $goalPoaActivity->year;
                    $element['month'] = substr($goalPoaActivity->start_date, 5, 2);
                    $element['monthName'] = Indicator::FREQUENCIES[$frequency][$goalPoaActivity->period];
                    $element['goal'] = $goalPoaActivity->goal;
                    if ($goalPoaActivity->goal > 0) {
                        $this->goals[$goalPoaActivity->id] = ['goal' => $goalPoaActivity->goal];
                    }
                    if (!array_key_exists($key, $this->elementos)) {
                        $this->elementos[$key][$key2][] = $element;
                    } else {
                        $this->elementos[$key][$key2][] = $element;
                    }
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.poa.poa-assign-goals');
    }

    /**
     * Reset Form on Cancel
     *
     */
    public function resetForm()
    {
        $this->goals = [];
        $this->elementos = [];
        $this->resetErrorBag();
        $this->resetValidation();
    }

    /**
     * Update Activity indicator goals
     */
    public function submitGoals()
    {
        $goalTotal = 0;
        foreach ($this->goals as $index => $goal) {

            $poaActivityUIndicator = PoaActivityIndicator::find($index);
            $idActivity = $poaActivityUIndicator->poaActivity->id;
            $poaId = $poaActivityUIndicator->poaActivity->program->poa->id;
            $goalTotal += $goal['goal'];
            $poaActivityUIndicator->goal = $goal['goal'];
            $poaActivityUIndicator->save();
        }
        PoaActivity::find($idActivity)->update(['goal' => $goalTotal]);
        $this->goals = [];
        $this->elementos = [];
        $this->resetForm();
        $this->emit('goalsUpdated', $poaId);
        $this->emit('toggleModalGoalsProgram');
        $this->reset();
    }
}
