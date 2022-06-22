<?php

namespace App\Listeners;

use App\Events\ProjectSubsidiaryUpdated;
use App\Models\Projects\Activities\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateDeleteTasksOfProject
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
     * @param ProjectSubsidiaryUpdated $event
     * @return void
     */
    public function handle(ProjectSubsidiaryUpdated $event)
    {
        //
        $project = $event->project;
        $subsidiaries = $project->subsidiaries->where('company_id', '!=', $project->company_id);
        $primaryTasks = Task::where('project_id', $project->id)->where('type', 'task')->get();
        foreach ($subsidiaries->pluck('company_id') as $item) {
            $existTask = Task::withoutGlobalScope(\App\Scopes\Company::class)->where('project_id', $project->id)->where('company_id', $item)->where('type', 'task')->get();
            if ($existTask->count() <= 0) {
                foreach ($primaryTasks as $task) {
                    Task::create([
                        'code' => $task->code.$item,
                        'text' => $task->text,
                        'description' => $task->description,
                        'duration' => $task->duration,
                        'progress' => $task->progress,
                        'start_date' => $task->start_date,
                        'end_date' => $task->end_date,
                        'parent' => $task->parent,
                        'type' => $task->type,
                        'sortorder' => $task->sortorder,
                        'open' => $task->open,
                        'color' => $task->color,
                        'status' => $task->status,
                        'impact' => $task->impact,
                        'complexity' => $task->complexity,
                        'amount' => $task->amount,
                        'weight' => $task->weight,
                        'project_id' => $task->project_id,
                        'company_id' => $item,
                        'owner' => $task->owner,
                        'indicator_id' => $task->indicator_id,
                        'taskable_id' => $task->taskable_id,
                        'taskable_type' => $task->taskable_type,
                        'objective_id' => $task->objective_id,
                        'owner_id' => $task->owner_id,
                    ]);
                }
            }
        }
    }
}
