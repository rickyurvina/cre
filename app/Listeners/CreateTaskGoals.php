<?php

namespace App\Listeners;

use App\Events\TaskUpdatedCreateGoals;
use App\Models\Projects\Activities\TaskDetails;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateTaskGoals
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
     * @param  TaskUpdatedCreateGoals  $event
     * @return void
     */
    public function handle(TaskUpdatedCreateGoals $event)
    {
        //
        $task=$event->task;
        $numberMonths=$this->numberOfPeriods('P1M','+0 month', $task);
        $task->goals->each->delete();

        foreach ($numberMonths as $item){
            TaskDetails::create([
                'prj_task_id'=>$task->id,
                'period'=>$item,
                'company_id'=>$task->company_id,
            ]);

        }

    }
    function numberOfPeriods($frequency = null, $modify = null, $task)
    {
        $begin = new DateTime($task->start_date);
        $end = new DateTime($task->end_date);
        $end = $end->modify($modify);
        $interval = new DateInterval($frequency);
        $daterange = new DatePeriod($begin, $interval, $end);
        $result = array();
        $i = 0;
        foreach ($daterange as $date) {
            $result[$i] = $date->format("d-m-Y");
            $i++;
        }
        return $result;
    }
}
