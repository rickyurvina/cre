<?php

namespace App\Http\Livewire\Risks;

use App\Http\Livewire\Components\Modal;
use App\Models\Common\Catalog;
use App\Models\Projects\Catalogs\ProjectRiskClassification;
use App\Models\Projects\Project;
use App\Models\Risk\Risk;
use App\Traits\Jobs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class CreateRisk extends Modal
{

    use Jobs;

    public string $name = '';
    public string $description = '';
    public $identification_date;
    public $incidence_date;
    public $closing_date;
    public int $state = 0;
    public string $cost = '';
    public string $cause = '';
    public string $classification = '';
    public int $count = 0;

    public Collection $classifications;
    public Collection $states;

    public $messagesList;

    public $model;
    public $modelId;
    public $class;


    protected array $rules = [
        'name' => 'required|max:150',
        'description' => 'required|min:5|max:200',
        'classification' => 'required',

    ];

    public function mount($modelId, $class, $messages = null)
    {
        $this->modelId=$modelId;
        $this->class=$class;
        $this->model = App::make($this->class)::withoutGlobalScope(\App\Scopes\Company::class)->find($this->modelId);
        $this->classifications = ProjectRiskClassification::get();
        $this->states = Catalog::catalogName('risk_states')->first()->details;
        $this->messagesList = $messages;

    }

    public function render()
    {
        return view('livewire.risks.create-risk');
    }

    public function increment()
    {
        $this->count++;
    }


    public function store()
    {
        $this->validate();

        foreach ($this->states as $item) {
            if ($item->description == Risk::RISK_STATE_OPEN) {
                $this->state = $item->id;
                break;
            }
        }

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'cause' => $this->cause,
            'identification_date' => $this->identification_date,
            'closing_date' => $this->closing_date,
            'incidence_date' => $this->incidence_date,
            'cost' => $this->cost,
            'state_id' => $this->state,
            'classification' => $this->classification,
            'riskable_id' => $this->modelId,
            'riskable_type' => $this->class,
            'company_id' => session('company_id'),
        ];

        $response = $this->ajaxDispatch(new \App\Jobs\Risk\CreateRisk($data));

        $this->show = false;

        if ($response['success']) {
            flash(trans_choice('messages.success.added', 0, ['type' => trans_choice('general.risks', 1)]))->success();
            $this->cleanForm();
        } else {
            flash($response['message'])->error();
        }

        if ($this->class==Project::class){
            return redirect()->to(url()->previous());
        }else{
            $this->emit('riskCreated');
            $this->emit('toggleCreateRisk');
        }
    }

    public function cleanForm()
    {
        $this->name = '';
        $this->state = 0;
        $this->cost = '';
        $this->cause = '';
        $this->classification = '';
    }

    public function resetForm(){
        $this->show=false;
    }

}
