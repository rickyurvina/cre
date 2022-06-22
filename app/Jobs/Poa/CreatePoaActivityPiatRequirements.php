<?php

namespace App\Jobs\Poa;

use App\Abstracts\Job;
use App\Models\Indicators\GoalIndicator\GoalIndicators;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaActivityIndicator;
use App\Models\Poa\PoaActivityPiat;
use App\Models\Poa\PoaActivityPiatPlan;
use App\Models\Poa\PoaActivityPiatRequirements;
use Exception;
use Illuminate\Support\Facades\DB;

class CreatePoaActivityPiatRequirements extends Job
{
    protected $poaActivityPiatRequirement;

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
     * @throws Exception        $this->request = $this->getRequestInstance($request);

     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $this->poaActivityPiatRequirement = PoaActivityPiatRequirements::create($this->request->all());
            DB::commit();
            return $this->poaActivityPiatRequirement;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }
}
