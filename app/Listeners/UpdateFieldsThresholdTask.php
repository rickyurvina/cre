<?php

namespace App\Listeners;

use App\Events\TaskUpdatedThresholds;
use Illuminate\Support\Facades\DB;
use Exception;

class UpdateFieldsThresholdTask
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
     * @param \App\Events\TaskUpdatedThresholds $event
     * @return void
     */
    public function handle(TaskUpdatedThresholds $event)
    {
        try {
            DB::beginTransaction();
            $task = $event->task;
            $threshold = $task->threshold->first();
            $threshold->start_date = $task->start_date;
            $threshold->end_date = $task->end_date;
            $threshold->progress_physic = $task->progress;
            $threshold->save();
            DB::commit();
            return $threshold;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
        
    }
}
