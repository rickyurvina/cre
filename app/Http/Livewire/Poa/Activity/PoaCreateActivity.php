<?php

namespace App\Http\Livewire\Poa\Activity;

use App\Jobs\Poa\CreatePoaActivity;
use App\Models\Auth\User;
use App\Models\Common\Catalog;
use App\Models\Common\CatalogGeographicClassifier;
use App\Models\Indicators\Indicator\Indicator;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaActivityTemplate;
use App\Models\Poa\PoaIndicatorConfig;
use App\Models\Poa\PoaProgram;
use App\Scopes\Company;
use App\Traits\Jobs;
use Livewire\Component;

class PoaCreateActivity extends Component
{
    use Jobs;

    public $poaId = null;

    public $poaProgramId = null;

    public $poaProgramName = '';

    public $programIndicators = [];

    public $poaActivityName = null;

//    public $poaActivityCode = null;

    public $poaActivityUserInChargeId = null;

    public $poaActivityIndicatorId = null;

    public $poaActivityIndicatorName = '';

    public $poaActivityCost = null;

    public $poaActivityImpact = null;

    public $poaActivityComplexity = null;

    public $programs;

    public $locations = [];

    public $typeLocation;

    public $selectedLocationId = null;

    public $selectedLocationName = '';

    public $limitLocation = 10;

    public $searchLocation = '';

    public $users;

    public $activityTemplates = [];

    public $selectedTemplate = null;

    public $code=null;

    protected $listeners = ['loadIndicators'];

    public function mount(int $poaId)
    {
        $this->poaId = $poaId;
        $this->programs=PoaProgram::with(['planDetail'])->where('poa_id',$poaId)->get();
        $this->users = User::enabled()->get();

    }

    public function render()
    {
        return view('livewire.poa.activity.poa-create-activity');
    }

    public function updatedCode($value)
    {
        self::loadActivityTemplates();
    }

    public function updatedPoaActivityName($value)
    {
        self::loadActivityTemplates();
    }

    public function resetTemplate()
    {
        $this->activityTemplates = [];
    }

    private function loadActivityTemplates()
    {
        $this->activityTemplates = PoaActivityTemplate::where('code', 'iLike', '%' . $this->code . '%')
            ->where('name', 'iLike', '%' . $this->poaActivityName . '%')->limit(10)->orderBy('code')->get();
    }

    public function updatingPoaProgramId($value)
    {
        if ($this->poaProgramId != $value) {
            $this->poaActivityIndicatorId = null;
            $this->poaActivityIndicatorName = '';
        }
    }

    public function updatedPoaProgramId($value)
    {
        foreach ($this->programs as $program) {
            if ($program['id'] == $value) {
                $this->poaProgramName = $program->planDetail->name;
            }
        }

        $this->programIndicators = PoaIndicatorConfig::selected()->where([
            ['poa_id', '=', $this->poaId],
            ['program_id', '=', $value],
        ])->with('indicator')->get();

    }

    public function updatedPoaActivityIndicatorId($value)
    {
        foreach ($this->programIndicators as $programIndicator) {
            if ($programIndicator->indicator->id == $value) {
                $this->poaActivityIndicatorName = $programIndicator->indicator->name;
            }
        }
    }

    public function updatedTypeLocation($value)
    {
        $this->selectedLocationId = null;
        $this->selectedLocationName = '';
        $this->searchLocation = '';
        self::locations();
    }

    public function updatedSearchLocation($value)
    {
        self::locations();
    }

    public function updatedSelectedLocationId($value)
    {
        $this->selectedLocationName = $this->locations->where('id', $value)->first()->getPath();
        $this->searchLocation = '';
        self::locations();
    }

    private function locations()
    {
        $this->locations = CatalogGeographicClassifier::when($this->typeLocation, function ($q) {
            $q->where('type', $this->typeLocation);
        })->when($this->searchLocation != '', function ($q) {
            $q->where(function ($q) {
                $q->where('full_code', 'iLike', '%' . $this->searchLocation . '%')
                    ->orWhere('description', 'iLike', '%' . $this->searchLocation . '%');
            });
        })->limit($this->limitLocation)->get();
    }

    /**
     * Load Program Indicators
     *
     */
    public function loadIndicators($poaProgramId)
    {
        $this->poaProgramId = $poaProgramId;
        self::updatedPoaProgramId($poaProgramId);
    }

    /**
     * Store POA program activity
     *
     */
    public function submitActivity()
    {
        $this->validate([
            'poaActivityName' => 'required|max:255',
            'code' => 'required|numeric',
            'poaActivityCost' => 'nullable|numeric',
            'poaActivityImpact' => 'required',
            'poaActivityComplexity' => 'required',
            'poaActivityUserInChargeId'=>'required',
            'poaProgramId'=>'required',
            'poaActivityIndicatorId'=>'required',
        ]);

        $actTemplateCode = PoaActivityTemplate::where('code', $this->code)->first();
        $actTemplatenName = PoaActivityTemplate::where('name', $this->poaActivityName)->first();
        
        /**
         * Si existe el codigo Y no existe el nombre significa que el nombre de 
         * actividad lo esta ingresando y no seleccionando, entonces devuelve error
         * 
         * Si el codigo existe y el nombre tambien significa que lo selecciono o ingreso uno 
         * igual al que ya existe lo cual no es problema.
         */
        if($actTemplateCode !== null && $actTemplatenName === null) {

            $this->dispatchBrowserEvent('alert', ['title' => "Ya existe un catalogo de actividad con este código", 'icon' => 'error']);
            return;
        }
        $act = PoaActivity::where('poa_program_id', $this->poaProgramId)
                    ->where('indicator_id', $this->poaActivityIndicatorId)
                    ->where('code', $this->code)
                    ->where('location_id', $this->selectedLocationId)->first();
        if($act !== null) {
            $this->dispatchBrowserEvent('alert', ['title' => "El código de actividad ya está en uso para este indicador", 'icon' => 'error']);
            
            return;
        }

        // Get Indicator unit ID
        $indicatorUnitId = Indicator::withoutGlobalScope(Company::class)->find($this->poaActivityIndicatorId)->indicatorUnit->id;

        // Get Program plan_detail_id
        $planDetailId = PoaProgram::find($this->poaProgramId)->plan_detail_id;

        $data = [
            'poa_program_id' => $this->poaProgramId,
            'indicator_unit_id' => $indicatorUnitId,
            'indicator_id' => $this->poaActivityIndicatorId,
            'location_id' => $this->selectedLocationId,
            'plan_detail_id' => $planDetailId,
            'name' => $this->poaActivityName,
            'code' => $this->code,
            'user_id_in_charge' => $this->poaActivityUserInChargeId,
            'status' => PoaActivity::STATUS_SCHEDULED,
            'cost' => $this->poaActivityCost,
            'impact' => $this->poaActivityImpact,
            'complexity' => $this->poaActivityComplexity,
            'company_id' => session('company_id'),
            'description' => 'Texto descripccion',
        ];

        $response = $this->ajaxDispatch(new CreatePoaActivity($data));

        if ($response['success']) {
            flash(trans_choice('messages.success.added', 1, ['type' => trans_choice('general.activities', 1)]))->success()->livewire($this);
            $this->resetForm();
            $this->emit('activityCreated');
            $this->emit('toggleModalCreateActivity');
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
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset(
            [
                'poaProgramId',
                'programIndicators',
                'code',
                'poaActivityName',
                'poaActivityUserInChargeId',
                'poaActivityIndicatorId',
                'selectedLocationId',
                'poaActivityCost',
                'poaActivityImpact',
                'poaActivityComplexity',
                'selectedTemplate',
                'locations',
                'poaActivityIndicatorName',
                'selectedLocationName',
                'typeLocation',
            ]
        );
    }

    public function selectTemplateActivity(PoaActivityTemplate $template)
    {
        $this->code = $template->code;
        $this->poaActivityName = $template->name;
        $this->poaActivityCost = $template->cost;
        $this->poaActivityImpact = $template->impact;
        $this->poaActivityComplexity = $template->complexity;
        $this->activityTemplates = [];
    }

}