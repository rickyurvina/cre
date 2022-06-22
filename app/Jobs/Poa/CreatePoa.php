<?php

namespace App\Jobs\Poa;

use App\Abstracts\Job;
use App\Models\Poa\Poa;
use Exception;

class CreatePoa extends Job
{
    protected $poa;

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
     * @return mixed
     * @throws Exception
     */
    public function handle()
    {
        try {
            $this->poa = Poa::create($this->request->all());
            return $this->poa;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
