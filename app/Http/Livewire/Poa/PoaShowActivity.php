<?php

namespace App\Http\Livewire\Poa;

use App\Models\Indicators\Indicator\Indicator;
use App\Models\Poa\PoaActivity;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class PoaShowActivity extends Component
{
    public $poaActivity;
    public array $data = [];
    public array $dataScore = [];

    protected $listeners = ['open' => 'mount'];

    public function mount($id = null)
    {
        if ($id) {
            $this->poaActivity = PoaActivity::withoutGlobalScope(\App\Scopes\Company::class)->find($id);
            $frequency = $this->poaActivity->indicator->frequency;
            $this->data = [];
            $i = 1;
            foreach ($this->poaActivity->poaActivityIndicator as $item) {
                if ($i > 12) {
                    $i = 1;
                }
                if ($item->period <= 12) {
                    $this->data[] = [
                        'frequency' => Indicator::FREQUENCIES[$frequency][$i],
                        'value' => $item->goal != 0 ? $item->goal : null,
                        'actual' => $item->progress != 0 ? $item->progress : null,
                        'year' => date("Y"),
                        'progress' => $item->progress()
                    ];
                    $i++;
                }
            }
            $this->dataScore = [];
            $properties = $this->poaActivity->indicator->threshold_properties;
            $min = $properties[1]['min'];
            $max = $properties[1]['max'];
            $this->dataScore[] = [
                'score' => $this->poaActivity->progress(),
                'min' => $min,
                'max' => $max
            ];
            $this->dispatchBrowserEvent('updateChartDataActivity', ['data' => $this->data]);
            $this->dispatchBrowserEvent('updateChartDataActivity2', ['data' => $this->dataScore]);
            $this->emit('toggleShowModal');
        }
    }

    /**
     * Reset Form on Cancel
     *
     */
    public function resetForm()
    {
        $this->resetErrorBag();
        $this->resetValidation();

    }

    public function render()
    {
        return view('livewire.poa.poa-show-activity');
    }
}
