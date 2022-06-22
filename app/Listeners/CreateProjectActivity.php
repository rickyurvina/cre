<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use App\Models\Projects\Activities\Task;
use App\Models\Projects\Project;
use Illuminate\Database\Eloquent\Model;

class CreateProjectActivity
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
     * @param ProjectCreated $event
     *
     * @return void
     */
    public function handle(ProjectCreated $event)
    {

        $project = $event->project;
        $root = new Task();
        $root->text = $project->name;
        $root->start_date = now()->format('Y-m-d');
        $root->end_date = now()->format('Y-m-d');
        $root->duration = 4;
        $root->type = 'project';
        $root->progress = 0;
        $root->weight = 0;
        $root->parent = 'root';
        $root->sortorder = 1;
        $root->project_id = $project->id;
        $root->company_id = session('company_id');
        $root->save();

    }

}
