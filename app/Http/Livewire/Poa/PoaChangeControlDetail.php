<?php

namespace App\Http\Livewire\Poa;

use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class PoaChangeControlDetail extends Component
{

    public Activity $activity;

    protected $listeners=['open'=>'mount'];

    public function mount($id=null){

        if ($id){
            $this->activity=Activity::find($id);
            $this->emit('toggleModalPoaChangeControlDetail');
        }
    }

    public function render()
    {
        return view('livewire.poa.poa-change-control-detail');
    }
}
