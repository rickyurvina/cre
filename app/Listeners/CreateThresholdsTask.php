<?php

namespace App\Listeners;

use App\Events\ResultCreated;
use App\Events\TaskCreated;
use App\Models\Projects\Activities\Task;
use App\Models\Projects\Configuration\ProjectThreshold;
use App\Models\Projects\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateThresholdsTask
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
     * @param  \App\Events\ResultCreated  $event
     * @return void
     */
    public function handle(TaskCreated $event)
    {
        //
        $task = $event->task;
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
                'description' => 'Umbral de medida de la actividad ' . $task->text,
                'progress_physic' => 0,
                'thresholdable_id' => $task->id,
                'thresholdable_type' => Task::class,
                'properties' => $properties
            ];
        ProjectThreshold::create($data);
    }
}
