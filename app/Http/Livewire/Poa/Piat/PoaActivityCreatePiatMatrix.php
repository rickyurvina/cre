<?php

namespace App\Http\Livewire\Poa\Piat;

use App\Jobs\Poa\CreatePoaActivityPiat;
use App\Jobs\Poa\CreatePoaActivityPiatPlan;
use App\Jobs\Poa\CreatePoaActivityPiatRequirements;
use App\Models\Auth\User;
use App\Models\Common\CatalogGeographicClassifier;
use App\Models\Poa\PoaActivityPiat;
use App\Traits\Jobs;
use Livewire\Component;
use PhpOffice\PhpWord\Element\Field;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use function flash;
use function view;

class PoaActivityCreatePiatMatrix extends Component
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
    public $numberFeMaleResp =0;
    public $maleBenef = 0;
    public $femaleBenef = 0;
    public $maleVol = 0;
    public $femaleVol = 0;
    public $goal;
    public $createdBy;
    public $approvedBy;

    //For PoaActivityPiatPlan table
    public $task;
    public $responsable;
    public $planDate;
    public $planInitTime;
    public $planEndTime;

    //For PoaActivityPiatRequirements table
    public $description;
    public $quantity = 0;
    public $approxCost = 0.00;
    public $reqResponsable;

    public $provinces;
    public $cantons;
    public $parishes;
    public $users;
    public $newPoaActivityPiatId = null;
    public $newPoaActivityPiatName = null;
    public $flag = false;

    public $matrix = [];

    public $activityId;

    public function mount() {
        $this->users = User::where('enabled', true)->get();
        
        $this->matrix = PoaActivityPiat::where('id_poa_activities', $this->activity->id)->get();
    }

    public function render()
    {
        return view('livewire.poa.piat.poa-activity-create-piat-matrix');
    }

    public function showCreateForm() {
        $this->flag = true;
    }

    public function showMatrixList() {
        $this->matrix = PoaActivityPiat::where('id_poa_activities', $this->activityId)->get();
        $this->newPoaActivityPiatId = null;
        $this->flag == false;
    }

    public function updatedProvince($value) {
        $this->cantons = CatalogGeographicClassifier::where('parent_id', $value)->get();
    }

    public function updatedCanton($value) {
        $this->parishes = CatalogGeographicClassifier::where('parent_id', $value)->get();
    }

    public function submit() {

        $data = [
            'name' => $this->name,
            'place' => $this->place,
            'date' => $this->date,
            'initial_time' => $this->initTime,
            'end_time' => $this->endTime,
            'province' => $this->province,
            'canton' => $this->canton,
            'parish' => $this->parish,
            'id_poa_activities' => $this->activity->id,
            'number_male_respo' => $this->numberMaleResp,
            'number_female_respo' => $this->numberFeMaleResp,
            'males_beneficiaries' => $this->maleBenef,
            'females_beneficiaries' => $this->femaleBenef,
            'males_volunteers' => $this->maleVol,
            'females_volunteers' => $this->femaleVol,
            'goals' => $this->goal,
            'created_by' => $this->createdBy,
            'approved_by' => $this->approvedBy,
        ];

        $response = $this->ajaxDispatch(new CreatePoaActivityPiat($data));
        $this->activityId = $this->activity->id;

        if ($response['success']) {
            $aux = $response['data'];
            $this->newPoaActivityPiatId = $aux->id;
            $this->newPoaActivityPiatName = $aux->name;

            $this->resetMainForm();
            flash(trans_choice('messages.success.added', 1, ['type' => __('general.poa_activity_piat')]))->success()->livewire($this);
        } else {
            $this->dispatchBrowserEvent('alert', ['title' => $response['message'], 'icon' => 'error']);
        }
    }

    public function submitPlan() {

        $dataPlan = [
            'id_poa_activity_piat' => $this->newPoaActivityPiatId,
            'task' => $this->task,
            'responsable' => $this->responsable,
            'date' => $this->planDate,
            'initial_time' => $this->planInitTime,
            'end_time' => $this->planEndTime,
        ];

        $response = $this->ajaxDispatch(new CreatePoaActivityPiatPlan($dataPlan));

        if ($response['success']) {
            $this->resetPlanForm();
            flash(trans_choice('messages.success.added', 1, ['type' => __('general.poa_activity_piat_plan')]))->success()->livewire($this);
        } else {
            $this->dispatchBrowserEvent('alert', ['title' => $response['message'], 'icon' => 'error']);
        }
    }

    public function submitRequirements() {

        $dataRequirements = [
            'id_poa_activity_piat' => $this->newPoaActivityPiatId,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'approximate_cost' => $this->approxCost,
            'responsable' => $this->reqResponsable,
        ];
        $response = $this->ajaxDispatch(new CreatePoaActivityPiatRequirements($dataRequirements));

        if ($response['success']) {
            $this->resetRequirementsForm();
            flash(trans_choice('messages.success.added', 1, ['type' => __('general.poa_activity_piat_requirement')]))->success()->livewire($this);
        } else {
            $this->dispatchBrowserEvent('alert', ['title' => $response['message'], 'icon' => 'error']);
        }
    }

    public function resetMainForm() {
        $this->activity = null;
        $this->name = null;
        $this->place = null;
        $this->date = null;
        $this->initTime = null;
        $this->endTime = null;
        $this->province = null;
        $this->canton = null;
        $this->parish = null;
        $this->poaActivity = null;
        $this->numberMaleResp = null;
        $this->numberFeMaleResp = null;
        $this->maleBenef = null;
        $this->femaleBenef = null;
        $this->maleVol = null;
        $this->femaleVol = null;
        $this->goal = null;
        $this->createdBy = null;
        $this->approvedBy = null;
    }

    public function resetPlanForm() {
        $this->task = null;
        $this->planDate = null;
        $this->approxCost = null;
        $this->responsable = null;
        $this->planInitTime = null;
        $this->planEndTime = null;
    }

    public function resetRequirementsForm() {
        $this->description = null;
        $this->quantity = null;
        $this->approxCost = null;
        $this->reqResponsable = null;
    }
}
