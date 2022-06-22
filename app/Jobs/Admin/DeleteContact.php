<?php

namespace App\Jobs\Admin;

use App\Abstracts\Job;
use App\Models\Admin\Contact;
use Illuminate\Support\Facades\DB;
use Throwable;

class DeleteContact extends Job
{
    public $request;

    /**
     * Create a new job instance.
     *
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Throwable
     *
     */
    public function handle()
    {
        DB::transaction(function () {
            Contact::destroy($this->request->id);
        });
    }
}
