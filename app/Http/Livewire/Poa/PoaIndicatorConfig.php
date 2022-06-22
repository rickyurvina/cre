<?php

namespace App\Http\Livewire\Poa;

use App\Jobs\Poa\CreatePoaProgram;
use App\Models\Indicators\Indicator\Indicator;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaProgram;
use App\Models\Strategy\Plan;
use App\Models\Poa\PoaIndicatorConfig as PoaIndicatorConfigs;
use App\Models\Strategy\PlanRegisteredTemplateDetails;
use App\Models\Strategy\PlanTemplate;
use Livewire\Component;
use App\Traits\Jobs;

class PoaIndicatorConfig extends Component
{
    use Jobs;

    public $poaId = null;

    public $data = [];

    public $weight = 0;

    public function mount()
    {
        $aux = $this->configPoa($this->poaId);
        foreach ($aux as $a) {
            array_push($this->data, $a);
        }
    }

    public function render()
    {
        return view('livewire.poa.poa-indicator-config');
    }

    public function checkActivities($indicatorId, $programId, $pos)
    {
        if (!($this->data[$pos]['id'])) { //Unchecked
            $poaActivities = PoaActivity::where('poa_program_id', $programId)
                ->where('indicator_id', $indicatorId)
                ->first();
            if ($poaActivities) {

                $this->data[$pos]['id'] = $this->data[$pos]['indicatorId'];
                $this->dispatchBrowserEvent('alert', ['title' => trans('general.error_message_uncheck_indicator'), 'icon' => 'error']);
            }
        }
    }

    public function saveConfig()
    {
        $contProgramsSelected = 0;
        $this->validate([
            'data' => 'required|array',
            'data.*.reason' => 'required_without:data.*.id',
        ]);
        $this->validate([
            'data' => 'required|array',
            'data.*.reason' => 'required_if:data.*.id,==,false',
        ]);

        //Recuperar los programas seleccionados
        $lastPlanDetailId = "";
        for ($i = 0; $i < count($this->data); $i++) {
            if (!($this->data[$i]['id'] == null)) {
                if ($lastPlanDetailId != $this->data[$i]['planDetailId']) {
                    $contProgramsSelected++;
                    $lastPlanDetailId = $this->data[$i]['planDetailId'];
                }
            }
        }

        if ($contProgramsSelected > 0) {
            $this->weight = 100.00 / $contProgramsSelected;
        }
        for ($i = 0; $i < count($this->data); $i++) {
            $poaIndicatorConfigs = new PoaIndicatorConfigs();
            $poaIndicatorConfigs->poa_id = $this->poaId;
            $poaIndicatorConfigs->indicator_id = $this->data[$i]['indicatorId'];
            if ($this->data[$i]['id'] == null) {
                $poaIndicatorConfigs->selected = false;
                $poaIndicatorConfigs->reason = $this->data[$i]['reason'];
            } else {
                $poaIndicatorConfigs->selected = true;
                $program = PoaProgram::where('plan_detail_id', $this->data[$i]['planDetailId'])
                    ->where('poa_id', $this->poaId)
                    ->first();
                if ($program) {
                    $programId = $program->id;
                } else {
                    $programId = $this->selectProgram($this->data[$i]['planDetailId']);
                }
                $poaIndicatorConfigs->program_id = $programId;
            }
            PoaIndicatorConfigs::updateOrCreate(['poa_id' => $this->poaId,
                'indicator_id' => $this->data[$i]['indicatorId']],
                [
                    'poa_id' => (int)$this->poaId,
                    'indicator_id' => $this->data[$i]['indicatorId'],
                    'program_id' => $this->data[$i]['id'] == null ? null : $poaIndicatorConfigs->program_id,
                    'selected' => $this->data[$i]['id'] == null ? false : true,
                    'reason' => $this->data[$i]['id'] == null ? $this->data[$i]['reason'] : null
                ]);
        }
        flash(trans('general.ok_config_indicator'))->success();
        return redirect()->route('poa.poas');
    }

    private function selectProgram(int $id)
    {
        $data = [
            'poa_id' => $this->poaId,
            'plan_detail_id' => $id,
            'weight' => $this->weight,
            'color' => config('constants.catalog.COLOR_PALETTE')[array_rand(config('constants.catalog.COLOR_PALETTE'), 1)],
            'company_id' => session('company_id'),
        ];

        $response = $this->ajaxDispatch(new CreatePoaProgram($data));
        return $response['data']->id;
    }

    private function configPoa()
    {
        $arrayObjective2Summary = [];
        $sorted = [];
        $plans = Plan::where('plan_type', PlanTemplate::PLAN_STRATEGY_CRE)->where('status', Plan::ACTIVE)->first();
        if ($plans) {
            $planDetails = $plans->planDetails;
            $programTemplateId = PlanRegisteredTemplateDetails::where('program', true)
                ->where('plan_id', $plans->id)
                ->first();
            $programTemplatePoaIndicator = PlanRegisteredTemplateDetails::where('poa_indicators', true)
                ->where('plan_id', $plans->id)
                ->first();
            foreach ($planDetails->where('plan_registered_template_detail_id', $programTemplateId->id) as $planDetail) {
                if ($planDetail->plan_registered_template_detail_id === $programTemplatePoaIndicator->id) {
                    $indicators = $planDetail->indicators;
                    foreach ($indicators as $indicator) {
                        $element = [];
                        $element['id'] = null;
                        $element['specificObjectiveId'] = $planDetail->parent->id;
                        $element['planDetailId'] = $planDetail->id;
                        $element['planDetailName'] = $planDetail->name;
                        $element['specificGoal'] = $planDetail->parent->name;
                        $element['indicatorName'] = $indicator->name;
                        $element['indicatorId'] = $indicator->id;
                        $element['programId'] = -1;
                        $element['national'] = $indicator->national;
                        $poaIndicatorConfig = PoaIndicatorConfigs::where('poa_id', $this->poaId)
                            ->where('indicator_id', $indicator->id)
                            ->first();

                        if ($poaIndicatorConfig) {
                            $element['reason'] = $poaIndicatorConfig->reason;
                            $element['selected'] = $poaIndicatorConfig->selected;
                            if ($poaIndicatorConfig->program_id) {
                                $element['programId'] = $poaIndicatorConfig->program_id;
                            }
                            if ($poaIndicatorConfig->selected) {
                                $element['id'] = $element['indicatorId'];
                            }
                        } else {
                            $element['reason'] = "";
                            $element['selected'] = "";
                        }
                        if ($indicator->national) {
                            $element['id'] = $element['indicatorId'];
                        }
                        array_push($arrayObjective2Summary, $element);
                    }
                } else {
                    foreach ($planDetail->children as $childPlan) {
                        if ($childPlan->plan_registered_template_detail_id === $programTemplatePoaIndicator->id) {
                            $indicators = $childPlan->indicators;
                            foreach ($indicators as $indicator) {
                                $element = [];
                                $element['id'] = null;
                                $element['specificObjectiveId'] = $planDetail->parent->id;
                                $element['planDetailId'] = $planDetail->id;
                                $element['planDetailName'] = $planDetail->name;
                                $element['specificGoal'] = $planDetail->parent->name;
                                $element['indicatorName'] = $indicator->name;
                                $element['indicatorId'] = $indicator->id;
                                $element['programId'] = -1;
                                $element['national'] = $indicator->national;
                                $poaIndicatorConfig = PoaIndicatorConfigs::where('poa_id', $this->poaId)
                                    ->where('indicator_id', $indicator->id)
                                    ->first();

                                if ($poaIndicatorConfig) {
                                    $element['reason'] = $poaIndicatorConfig->reason;
                                    $element['selected'] = $poaIndicatorConfig->selected;
                                    if ($poaIndicatorConfig->program_id) {
                                        $element['programId'] = $poaIndicatorConfig->program_id;
                                    }
                                    if ($poaIndicatorConfig->selected) {
                                        $element['id'] = $element['indicatorId'];
                                    }
                                } else {
                                    $element['reason'] = "";
                                    $element['selected'] = "";
                                }
                                if ($indicator->national) {
                                    $element['id'] = $element['indicatorId'];
                                }
                                array_push($arrayObjective2Summary, $element);
                            }
                        }
                    }
                }
            }
            $sorted = $this->array_order_by($arrayObjective2Summary, 'specificObjectiveId', SORT_ASC, 'planDetailId', SORT_ASC);
        }
        return $sorted;
    }

    private function array_order_by()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row) {
                    $tmp[$key] = $row[$field];
                }
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }

}
