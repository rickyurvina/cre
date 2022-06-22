<?php

namespace App\Jobs\Projects;

use App\Abstracts\Job;
use App\Models\Projects\ProjectAcquisitions;
use Exception;

class ProjectDeleteAcquisition extends Job
{
    protected $projectAcquisition;

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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->projectAcquisition = ProjectAcquisitions::find($this->request);
            $this->projectAcquisition->delete();
            return $this->projectAcquisition;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
