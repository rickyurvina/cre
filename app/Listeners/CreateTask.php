<?php

namespace App\Listeners;

use App\Events\ActionStakeholderCreated;
use App\Models\Projects\Stakeholders\ProjectStakeholderActions;
use App\Models\Projects\Activities\Task;

class CreateTask
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
     * @param ActionStakeholderCreated $event
     * @return void
     */
    public function handle(ActionStakeholderCreated $event)
    {
        //
        $action = $event->action;
        $project = $action->stakeholder->project;
        $task = Task::find($event->action->task_id);
        $code = self::generateCode($event);
        $task2 = new Task();
        $task2->code = $code;
        $task2->text = $action->name;
        $task2->start_date = $action->start_date;
        $task2->end_date = $action->end_date;
        $task2->duration = 1;
        $task2->progress = 0;
        $task2->weight = 0;
        $task2->type = 'task';
        $task2->status = Task::STATUS_PROGRAMMED;
        $task2->parent = $task->id;
        $task2->sortorder = Task::max("sortorder") + 1;
        $task2->project_id = $project->id;
        $task2->company_id = session('company_id');
        $task2->taskable_id = $action->id;
        $task2->taskable_type = ProjectStakeholderActions::class;

        $task2->save();
    }

    public function generateCode($event)
    {
        $idAction = $event->action->id;
        $idStakeholder = $event->action->stakeholder->id;
        return 'CSA' . $idAction . $idStakeholder;
    }
}
