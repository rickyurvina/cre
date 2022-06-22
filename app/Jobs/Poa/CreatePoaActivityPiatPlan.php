<?php

namespace App\Jobs\Poa;

use App\Abstracts\Job;
use App\Models\Indicators\GoalIndicator\GoalIndicators;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaActivityIndicator;
use App\Models\Poa\PoaActivityPiat;
use App\Models\Poa\PoaActivityPiatPlan;
use Exception;
use Illuminate\Support\Facades\DB;

class CreatePoaActivityPiatPlan extends Job
{
    protected $poaActivityPiatPlan;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($requestPlan)
    {
        $this->request = $this->getRequestInstance($requestPlan);
    }

    /**
     * Execute the job.
     *
     * @return mixed
     * @throws Exception        $this->request = $this->getRequestInstance($request);

     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $this->poaActivityPiatPlan = PoaActivityPiatPlan::create($this->request->all());
            DB::commit();
            return $this->poaActivityPiatPlan;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }
}
