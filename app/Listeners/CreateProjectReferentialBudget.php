<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use App\Models\Projects\Project;
use App\Models\Projects\ProjectReferentialBudget;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateProjectReferentialBudget
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
     * @param \App\Events\ProjectCreated $event
     * @return void
     */
    public function handle(ProjectCreated $event)
    {
        //
        $project = $event->project;
        foreach (Project::PROJECT_EXPENSES as $expense) {
            $referentialBudgetItem = new ProjectReferentialBudget;
            $referentialBudgetItem->name = $expense;
            $referentialBudgetItem->project_id = $project->id;
            $referentialBudgetItem->save();
        }
    }
}
