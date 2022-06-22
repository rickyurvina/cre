<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Models\Projects\Activities\Task;
use Illuminate\Support\Facades\DB;
use Exception;

class DuplicateActivity
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
     * @param \App\Events\TaskCreated $event
     * @return Task
     */
    public function handle(TaskCreated $event)
    {
        //
        try {
            DB::beginTransaction();
            $task = $event->task;
            $project = $task->project;
            $subsidiaries = $project->subsidiaries;
            if ($subsidiaries->count() > 1) {
                foreach ($subsidiaries->where('company_id', '<>', session('company_id')) as $company) {
                    $searchTask = Task::where('code', $task->code)->where('company_id', $company->company_id)->pluck('id');
                    if ($searchTask->count() < 1) {
                        $newTask = $task->replicate();
                        $newTask->company_id = $company->company_id;
                        $newTask->code = $task->code.$company->company_id;
                        $newTask->save();
                    }
                }
            }
            DB::commit();
            return $task;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }

    }
}
