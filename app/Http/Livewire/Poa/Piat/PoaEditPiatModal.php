<?php

namespace App\Http\Livewire\Poa\Piat;

use App\Jobs\Poa\UpdatePoaActivityPiat;
use App\Jobs\Poa\UpdatePoaActivityPiatPlan;
use App\Jobs\Poa\UpdatePoaActivityPiatRequirements;
use App\Models\Auth\User;
use App\Models\Common\CatalogGeographicClassifier;
use App\Models\Poa\PoaActivityPiat;
use App\Models\Poa\PoaActivityPiatPlan;
use App\Models\Poa\PoaActivityPiatRequirements;
use App\Traits\Jobs;
use Livewire\Component;
use function flash;
use function redirect;
use function user;

class PoaEditPiatModal extends Component
{
    use Jobs;

    //For PoaActivityPiat table
    public $activity;
    public $name;
    public $place;
    public $date;
    public $initTime;
    public $endTime;
    public $province;
    public $canton;
    public $parish;
    public $poaActivity;
    public $numberMaleResp = 0;
    public $numberFeMaleResp = 0;
    public $maleBenef = 0;
    public $femaleBenef = 0;
    public $maleVol = 0;
    public $femaleVol = 0;
    public $goal;
    public $createdBy;
    public $approvedBy;

    public $planId;

    public $reqId;
    public $piatId;
    public $provinces;
    public $cantons = [];
    public $parishes = [];
    public $users;

    public $flag = false;

    public $piatPlan = [];
    public $piatReq = [];
    public $piat;

    public $taskPlan;
    public $datePlan;
    public $responsablePlan;
    public $initTimePlan;
    public $endTimePlan;
    public $is_terminated;
    public $description;
    public $quantity;
    public $approximateCost;
    public $responsableReq;

    protected $listeners = [
        'loadEditForm' => 'edit',
    ];

    protected $rules = [
        'name' => 'required',
        'date' => 'required',
        'initTime' => 'required',
        'endTime' => 'required|after:initTime',
        'province' => 'required',
        'canton' => 'required',
        'parish' => 'required',
    ];

    public function mount()
    {
        $this->provinces = CatalogGeographicClassifier::where('type', CatalogGeographicClassifier::TYPE_PROVINCE)->get();
        $this->cantons = CatalogGeographicClassifier::where('type', CatalogGeographicClassifier::TYPE_CANTON)->get();
        $this->parishes = CatalogGeographicClassifier::where('type', CatalogGeographicClassifier::TYPE_PARISH)->get();
        $this->users = User::where('enabled', true)->get();

        $this->matrix = PoaActivityPiat::where('id_poa_activities', $this->activity->id)->get();
    }

    public function edit($id = null)
    {
        $this->cleanThemeTask();
        $this->cleanRequirements();
        if ($id) {
            $this->piat = PoaActivityPiat::find($id);
            $piatPlans = PoaActivityPiatPlan::where('id_poa_activity_piat', $id)->get();
            $piatRequirements = PoaActivityPiatRequirements::where('id_poa_activity_piat', $id)->get();
            $this->piatId = $this->piat->id;
            $this->name = $this->piat->name;
            $this->place = $this->piat->place;
            $this->date = $this->piat->date;
            $this->initTime = $this->piat->initial_time;
            $this->endTime = $this->piat->end_time;
            $this->province = $this->piat->province;
            $this->canton = $this->piat->canton;
            $this->parish = $this->piat->parish;
            $this->numberMaleResp = $this->piat->number_male_respo;
            $this->numberFeMaleResp = $this->piat->number_female_respo;
            $this->maleBenef = $this->piat->males_beneficiaries;
            $this->femaleBenef = $this->piat->females_beneficiaries;
            $this->maleVol = $this->piat->males_volunteers;
            $this->femaleVol = $this->piat->females_volunteers;
            $this->goal = $this->piat->goals;
            $this->createdBy = $this->piat->created_by;
            $this->approvedBy = $this->piat->approved_by;
            $this->is_terminated = $this->piat->is_terminated;

            $this->piatPlan = [];
            $this->piatReq = [];

            foreach ($piatPlans as $plan) {
                $element = [];

                $element['id'] = $plan->id;
                $element['task'] = $plan->task;
                $element['date'] = $plan->date;
                $element['responsable'] = $plan->responsable;
                $element['initial_time'] = $plan->initial_time;
                $element['end_time'] = $plan->end_time;

                array_push($this->piatPlan, $element);
            }

            foreach ($piatRequirements as $req) {
                $element = [];

                $element['id'] = $req->id;
                $element['description'] = $req->description;
                $element['quantity'] = $req->quantity;
                $element['approximate_cost'] = $req->approximate_cost;
                $element['responsable'] = $req->responsable;

                array_push($this->piatReq, $element);
            }

        }
    }

    public function changeStatus()
    {
        if (user()->can('approve-piat-matrix-poa')) {
            $this->piat->status->transitionTo($this->piat->status->to());
            $this->closeModal();
            $this->piat->update(['approved_by' => user()->id]);
            flash(trans_choice('messages.success.updated', 0, ['type' => trans_choice('poa.piat_matrix_tag', 0)]))->success();
        }
    }

    public function closeModal()
    {
        $this->emit('togglePiatEditModal');
        PoaActivityPiat::where('id', $this->piatId)->update(['is_terminated' => true]);
        return redirect()->route('activities.edit', ['activity' => $this->activity->id]);
    }

    public function updatedProvince($value)
    {
        $this->cantons = CatalogGeographicClassifier::where('parent_id', $value)->get();
    }

    public function updatedCanton($value)
    {
        $this->parishes = CatalogGeographicClassifier::where('parent_id', $value)->get();
    }

    public function submit()
    {

        $this->validate();
        $data = [
            'name' => $this->name,
            'place' => $this->place,
            'date' => $this->date,
            'initial_time' => $this->initTime,
            'end_time' => $this->endTime,
            'province' => $this->province,
            'canton' => $this->canton,
            'parish' => $this->parish,
            'id_poa_activities' => $this->piat->id_poa_activities,
            'number_male_respo' => $this->numberMaleResp,
            'number_female_respo' => $this->numberFeMaleResp,
            'males_beneficiaries' => $this->maleBenef,
            'females_beneficiaries' => $this->femaleBenef,
            'males_volunteers' => $this->maleVol,
            'females_volunteers' => $this->femaleVol,
            'goals' => $this->goal,
            'created_by' => user()->id,
            'approved_by' => -1,
        ];

        $response = $this->ajaxDispatch(new UpdatePoaActivityPiat($this->piat->id, $data));

        if ($response['success']) {
            flash(trans_choice('messages.success.added', 1, ['type' => __('general.poa_activity_piat')]))->success()->livewire($this);
        } else {
            $this->dispatchBrowserEvent('alert', ['title' => $response['message'], 'icon' => 'error']);
        }
    }

    public function submitPlan()
    {
        $this->validate([
            'taskPlan' => 'required',
            'initTimePlan' => 'required',
            'endTimePlan' => 'required|after:initTime',
            'responsablePlan' => 'required',
            'datePlan' => 'required'
        ]);
        $data = [
            'id' => $this->planId,
            'id_poa_activity_piat' => $this->piat->id,
            'task' => $this->taskPlan,
            'responsable' => $this->responsablePlan,
            'date' => $this->datePlan,
            'initial_time' => $this->initTimePlan,
            'end_time' => $this->endTimePlan,
        ];

        $response = $this->ajaxDispatch(new UpdatePoaActivityPiatPlan($data));

        if ($response['success']) {
            $this->cleanThemeTask();
            $this->piatPlan = PoaActivityPiatPlan::where('id_poa_activity_piat', $this->piat->id)->get();
            flash(trans_choice('messages.success.added', 1, ['type' => __('general.poa_activity_piat_plan')]))->success()->livewire($this);
        } else {
            $this->dispatchBrowserEvent('alert', ['title' => $response['message'], 'icon' => 'error']);
        }
    }

    public function submitRequirements()
    {
        $this->validate([
            'description' => 'required',
            'quantity' => 'required',
            'approximateCost' => 'required',
            'responsableReq' => 'required',
        ]);
        $data = [
            'id' => $this->reqId,
            'id_poa_activity_piat' => $this->piat->id,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'approximate_cost' => $this->approximateCost,
            'responsable' => $this->responsableReq,
        ];

        $response = $this->ajaxDispatch(new UpdatePoaActivityPiatRequirements($data));

        if ($response['success']) {
            $this->cleanRequirements();
            $this->piatReq = PoaActivityPiatRequirements::where('id_poa_activity_piat', $this->piat->id)->get();
            flash(trans_choice('messages.success.added', 1, ['type' => __('general.poa_activity_piat_requirement')]))->success()->livewire($this);
        } else {
            $this->dispatchBrowserEvent('alert', ['title' => $response['message'], 'icon' => 'error']);
        }
    }

    public function editThemeTask($id)
    {
        $aux = PoaActivityPiatPlan::find($id);

        $this->planId = $aux->id;
        $this->taskPlan = $aux->task;
        $this->datePlan = $aux->date;
        $this->responsablePlan = $aux->responsable;
        $this->initTimePlan = $aux->initial_time;
        $this->endTimePlan = $aux->end_time;
    }

    public function deleteThemeTask($id)
    {
        $papp = PoaActivityPiatPlan::find($id);
        $papp->delete();

        $this->piatPlan = PoaActivityPiatPlan::where('id_poa_activity_piat', $this->piat->id)->get();
    }

    public function editRequirements($id)
    {
        $re = PoaActivityPiatRequirements::find($id);

        $this->reqId = $re->id;
        $this->description = $re->description;
        $this->quantity = $re->quantity;
        $this->approximateCost = $re->approximate_cost;
        $this->responsableReq = $re->responsable;
    }

    public function deleteRequirements($id)
    {
        $re = PoaActivityPiatRequirements::find($id);
        $re->delete();

        $this->piatReq = PoaActivityPiatRequirements::where('id_poa_activity_piat', $this->piat->id)->get();
    }

    public function cleanThemeTask()
    {
        $this->planId = null;
        $this->taskPlan = null;
        $this->datePlan = null;
        $this->responsablePlan = null;
        $this->initTimePlan = null;
        $this->endTimePlan = null;
    }

    public function cleanRequirements()
    {
        $this->reqId = null;
        $this->description = null;
        $this->quantity = null;
        $this->approximateCost = null;
        $this->responsableReq = null;
    }
}
