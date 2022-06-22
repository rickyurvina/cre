<?php

namespace App\Listeners;

use App\Events\ProjectColorUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdatedColorResults
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
     * @param  ProjectColorUpdated  $event
     * @return void
     */
    public function handle(ProjectColorUpdated $event)
    {
        //
        $objective=$event->objective;
        $results=$objective->results;

        $results->each(function ($item) use ($objective){
            $item->update(['color'=>$objective->color]);
        });
    }
}
