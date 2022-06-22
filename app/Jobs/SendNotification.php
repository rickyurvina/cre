<?php

namespace App\Jobs;

use App\Abstracts\Job;
use Illuminate\Support\Facades\DB;
use Exception;

class SendNotification extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.Se
     *
     * @return void
     */
    public function handle()
    {
        //
        try {
            DB::beginTransaction();
            $notificationArray = $this->request->notificationArray;
            $user = $this->request->user;
            $user->notify(new \App\Notifications\CreateNotification($notificationArray));
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());

        }
    }
}
