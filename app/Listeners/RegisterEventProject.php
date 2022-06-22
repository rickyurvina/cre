<?php

namespace App\Listeners;

use App\Events\ProjectStatusUpdated;
use App\Models\Projects\ProjectsEvents;
use Illuminate\Support\Facades\DB;
use Exception;

class RegisterEventProject
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
     * @param ProjectStatusUpdated $event
     * @return void
     */
    public function handle(ProjectStatusUpdated $event)
    {
        try {
            DB::beginTransaction();
            $data = $event->data;
            $eventProject = new ProjectsEvents;
            $eventProject->name = $data['name'];
            $eventProject->category = $data['category'] ?? '';
            $eventProject->description = $data['description'] ?? '';
            $eventProject->prj_projects_id = $data['prj_projects_id'] ?? '';
            $eventProject->date = now();
            $eventProject->user_id = user()->id;
            $eventProject->save();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }
}
