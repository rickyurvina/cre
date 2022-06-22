<?php

namespace App\Http\Livewire\Strategy;

use App\Models\Strategy\Plan;
use App\Models\Strategy\PlanDetail;
use App\Models\Strategy\PlanRegisteredTemplateDetails;
use Illuminate\Support\Collection;
use Livewire\Component;

class ReportIndicatorsStrategy extends Component
{
    public array $selectedObjectives = [];
    public array $selectedPrograms = [];
    public array $selectedPlans = [];
    public ?Collection $listOfObjectives = null;

    public $search = '';

    public function updatedSelectedPlans()
    {
        $planRegistered = PlanRegisteredTemplateDetails::with(['planDetails'])->whereIn('plan_id', $this->selectedPlans)
            ->where('indicators', true)->pluck('id');
        $this->listOfObjectives = PlanDetail::whereIn('plan_registered_template_detail_id', $planRegistered)->get();
    }

    public function filter()
    {
        $this->emit('toggleDropDownFilter');
    }

    public function cleanFilter($type)
    {
        $this->reset([
            'selectedPlans',
            'selectedObjectives',
        ]);
        $this->filter();
    }

    public function render()
    {
        $search = $this->search;
        $selectedObjectives = $this->selectedObjectives;
        $plans = Plan::with([
            'planDetails' => function ($q) use ($selectedObjectives) {
                $q->when($selectedObjectives, function ($query) use ($selectedObjectives) {
                    $query->whereIn('plan_details.id', $selectedObjectives);
                });
            },
            'planDetails.indicators' => function ($q) use ($search) {
                $q->when($search != '', function ($q) use ($search) {
                    $q->where('indicators.name', 'iLIKE', '%' . $search . '%');
                });

            }])->when($this->selectedPlans, function ($q) {
            $q->whereIn('id', $this->selectedPlans);
        })->Incompany()->get();

        $plansFilter = Plan::Incompany()->get();

        $existIndicators = false;
        foreach ($plans as $plan) {
            foreach ($plan->planDetails as $planDetail) {
                if (count($planDetail->indicators) > 0) {
                    $existIndicators = true;
                }
            }
        }
        return view('livewire.strategy.report-indicators-strategy', compact('plans', 'plansFilter', 'existIndicators'));
    }
}
