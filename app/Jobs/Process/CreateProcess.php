<?php

namespace App\Jobs\Process;

use App\Abstracts\Job;
use App\Models\Process\Process;

class CreateProcess extends Job
{
    protected $request;
    protected $process;

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
     * @return Process
     */
    public function handle(): Process
    {
        $this->process = Process::create($this->request->all());
        return $this->process;
    }
}