<?php

namespace App\Listeners;

use App\Events\TaskUpdated;
use App\Models\Projects\Activities\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\App;

class ActionsTaskUpdated
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
     * @param TaskUpdated $event
     * @return void
     */
    public function handle(TaskUpdated $event)
    {
        //
        $model = $event->model;
        if ($model->taskable_id && isset($model->getChanges()['text'])) {
            $class = App::make($model->taskable_type)->find($model->taskable_id);
            $class->name = $model->text;
            $class->save();
        }
        if (isset($model->getChanges()['impact']) || isset($model->getChanges()['complexity']) || isset($model->getChanges()['amount'])){


        }
        if ($model->parent !== 'root') {
            $parent = $model->parentOfTask()->first();
            $tasks = Task::where('parent', $model->parent)->get();
            $parentProgress = 0;
            foreach ($tasks as $ts) {
                $parentProgress += $ts->progress * $ts->weight;
            }
            $parent->progress = $parentProgress;
            $parent->save();
        }
    }
}
