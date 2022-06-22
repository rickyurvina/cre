<?php

namespace App\Listeners;

use App\Events\IndicatorProccessed;
use App\Models\Indicators\Indicator\Indicator;
use App\Models\Indicators\Indicator\IndicatorParentChild;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateIndicatorParent
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param IndicatorProccessed $event
     * @return void
     */
    public function handle(IndicatorProccessed $event)
    {
        $this->updateParents($event->indicator);
    }

    public function updateParents(Indicator $indicator)
    {

        $parents = $indicator->indicatorChild()->get();
        if (count($parents) > 0) {
            foreach ($parents as $parent) {
                $parents_ = IndicatorParentChild::where('parent_indicator', $parent->parent_indicator)
                    ->pluck('child_indicator', 'child_indicator');
                $indicators = Indicator::withoutGlobalScope(\App\Scopes\Company::class)->whereIn('id', $parents_)->get();
                $indicatorParent = Indicator::withoutGlobalScope(\App\Scopes\Company::class)->where('id', $parent->parent_indicator)->first();
                $indicatorParent->total_goal_value = $indicators->sum('total_goal_value');
                $indicatorParent->total_actual_value = $indicators->sum('total_actual_value');
                $indicatorParent->save();
                $goalIndicatorsParent = $indicatorParent->indicatorGoals;
                $goalIndicatorsChild = $indicator->indicatorGoals;
                foreach ($goalIndicatorsParent as $goalIndicator) {
                    $goalIndicator->goal_value = $goalIndicatorsChild->where('period', $goalIndicator->period)->sum('goal_value');
                    $goalIndicator->actual_value = $goalIndicatorsChild->where('period', $goalIndicator->period)->sum('actual_value');
                    $goalIndicator->save();
                }
            }
            $child = $indicatorParent->indicatorChild()->get();
            if (count($child) > 0) {
                $this->updateParents($indicatorParent);
            }
        }
    }
}
