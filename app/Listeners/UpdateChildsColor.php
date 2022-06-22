<?php

namespace App\Listeners;

use App\Events\TaskColorUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateChildsColor
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
     * @param \App\Events\TaskColorUpdated $event
     * @return void
     */
    public function handle(TaskColorUpdated $event)
    {

        try {
            DB::beginTransaction();
            $task = $event->task;
            $task->childs()->update(['color' => $task->color]);
            DB::commit();
            return $task;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }
}
