<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use App\Models\Projects\ProjectMemberSubsidiary;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateProjectMemberSubsidiary
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
     * @param  \App\Events\ProjectCreated  $event
     * @return void
     */
    public function handle(ProjectCreated $event)
    {
        //
        $project = $event->project;
        $projectMemberSubsidiary = new ProjectMemberSubsidiary();
        $projectMemberSubsidiary->project_id = $project->id;
        $projectMemberSubsidiary->company_id = session('company_id');
        $projectMemberSubsidiary->save();
    }
}
