<?php

namespace App\Listeners;

use App\Events\ProjectActivityWeightChanged;
use App\Models\Projects\Activities\Task;
use App\Traits\Jobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateProjectActivityWeight
{
    use Jobs;
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
     * @param  ProjectActivityWeightChanged  $event
     * @return void
     */
    public function handle(ProjectActivityWeightChanged $event)
    {
        //
        $task=$event->task;
        $data=[
            'cost'=>$task->amout ?? 0,
            'impact'=>$task->impact,
            'complexity'=>$task->complexity,
        ];
        $this->ajaxDispatch(new \App\Jobs\UpdateProjectActivityWeight($task, $data));

    }
}
