<?php

namespace App\Http\Livewire\Strategy;

use App\Models\Indicators\Indicator\Indicator;
use App\Models\Strategy\Plan;
use App\Models\Strategy\PlanDetail;
use App\Models\Strategy\PlanRegisteredTemplateDetails;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class StrategyShowIndicatorsPlanDetails extends Component
{

    public $planDetailId = null;

    public $search = '';

    public $type = null;

    public $planRegisteredTemplateDetailsBreadcrumbs;

    protected $listeners = ['renderPlanDetailIndicators' => 'render'];

    public function mount($planDetailId = null, $type = null, $navigation = null)
    {
        $planDetail = PlanDetail::with(['planRegistered','plan'])->find($planDetailId);
        $planRegisteredTemplateDetail = $planDetail->planRegistered;
        $element = array();
        $parent_id = $planDetail->parent_id;
        $count = count($navigation);
        if (!$parent_id){
            $navigation[$count - 1]['link'] =  route('plans.detail',
                [
                    'plan' => $planDetail->plan->id,
                    'level' => $planRegisteredTemplateDetail->level,
                    'planDetailId' => $parent_id,
                ]);
        }else{
            $navigation[$count - 1]['link'] = route('plans.detail',
                [
                    'plan' => $planRegisteredTemplateDetail->id,
                    'planDetailId' => $parent_id,
                    'detail' => $planRegisteredTemplateDetail->id
                ]);
        }

        if ($type==Indicator::CATEGORY_OPERATIVE){
            $element['name'] = "Indicadores Operativos";

        }else{
            $element['name'] = "Indicadores Tácticos";

        }
        $element['link'] = "";
        $element['first'] = 0;
        array_push($navigation, $element);

        $this->planRegisteredTemplateDetailsBreadcrumbs = $navigation;
        $this->planDetailId = $planDetailId;
        $this->type = $type;
    }

    public function render()
    {
        $search = $this->search;
        $planDetail = PlanDetail::find($this->planDetailId);
        $indicators = Indicator::where('indicatorable_id', $this->planDetailId)
            ->where('indicatorable_type', PlanDetail::class)
            ->where('category', $this->type)
            ->when($search, function ($q, $search) {
                $q->where('name', 'iLIKE', '%' . $search . '%');
            })->collect();
        return view('livewire.strategy.strategy-show-indicators-plan-details',
        [
         'indicators'=>$indicators,
         'planDetail'=>$planDetail,
        ]);

    }
}
