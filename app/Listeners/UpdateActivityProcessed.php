<?php

namespace App\Listeners;

use App\Events\ActivityProcessed;
use App\Events\IndicatorProccessed;
use App\Models\Indicators\Indicator\Indicator;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaActivityIndicator;
use App\Scopes\Company;

class UpdateActivityProcessed
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
     * @param ActivityProcessed $event
     *
     * @return void
     */
    public function handle(ActivityProcessed $event)
    {
        $poaActivities = PoaActivity::where('indicator_id', $event->activity)->get();
        $indicator = Indicator::withoutGlobalScope(Company::class)->find($event->activity);
        $indicator->total_goal_value =  $poaActivities->sum('goal');
        $indicator->total_actual_value = $poaActivities->sum('progress');
        $indicator->save();
        $poaActivitiesGoal = PoaActivityIndicator::where('indicator_id', $indicator->id)->get();
        $goalsIndicator=$indicator->indicatorGoals;
        foreach ($goalsIndicator as $goal) {
            $goal->goal_value = $poaActivitiesGoal->where('goal_indicator_id', $goal->id)->sum('goal');
            $goal->actual_value = $poaActivitiesGoal->where('goal_indicator_id', $goal->id)->sum('progress');
            $goal->save();
        }
        event(new IndicatorProccessed($indicator));
    }
}
