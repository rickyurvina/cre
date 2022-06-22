<?php

namespace App\Listeners;

use App\Events\ResultCreated;
use App\Models\Projects\ProjectReferentialBudget;
use App\Models\Projects\Activities\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateActivityOfResult
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
     * @param ResultCreated $event
     * @return void
     */
    public function handle(ResultCreated $event)
    {
        //
        $task = $event->task;
        $referentialBudgetItem = new ProjectReferentialBudget;
        $referentialBudgetItem->name = $task->text;
        $referentialBudgetItem->project_id = $task->project->id;
        $referentialBudgetItem->task_id = $task->id;
        $referentialBudgetItem->save();
    }
}
