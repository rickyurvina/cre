<?php

namespace App\Jobs\Risk;

use App\Abstracts\Job;
use App\Events\RiskCreatedEvent;
use App\Models\Risk\Risk;

class CreateRisk extends Job
{

    protected $request;
    protected Risk $risk;

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
     *
     * @return Risk
     */
    public function handle(): Risk
    {
        $this->risk = Risk::create($this->request->all());
        return $this->risk;
    }
}
