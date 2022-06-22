<?php

namespace App\Listeners;

use App\Events\TaskOfResultCreated;
use App\Models\Projects\Activities\Task;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateActivityOfTask
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
     * @param TaskOfResultCreated $event
     * @return void
     */
    public function handle(TaskOfResultCreated $event)
    {
        $date = now();
        $carbon_date = Carbon::parse($date);
        $carbon_date->addHours(9);
        $activity = $event->activity;
        $project = $activity->result->objective->project;
        $result = $activity->result;
        $task = new Task();
        $task->text = $activity->name;
        $task->start_date = now();
        $task->end_date = $carbon_date;
        $task->type = 'task';
        $task->duration = 1;
        $task->progress = 0;
        $task->weight = 0;
        $task->parent = $result->task->id;
        $task->sortorder = Task::max("sortorder") + 1;
        $task->project_id = $project->id;
        $task->company_id = session('company_id');
        $task->taskable_id = $activity->id;
        $task->taskable_type = ProjectObjectivesResultsActivity::class;
        $task->save();
    }
}
