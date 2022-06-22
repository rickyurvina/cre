<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use App\Models\Projects\Configuration\ProjectThreshold;
use App\Models\Projects\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateProjectThreshold
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
        $properties =
            [
                'time' =>
                    [
                        'min' => 50,
                        'max' => 70
                    ],
                'progress' =>
                    [
                        'min' => 50,
                        'max' => 70
                    ],
            ];
        $data =
            [
                'description' => 'Umbral de medida del Proyecto ' . $project->name,
                'progress_physic' => 0,
                'thresholdable_id' => $project->id,
                'thresholdable_type' => Project::class,
                'properties' => $properties
            ];
        ProjectThreshold::create($data);

    }
}
