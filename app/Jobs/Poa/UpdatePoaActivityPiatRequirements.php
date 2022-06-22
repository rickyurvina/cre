<?php

namespace App\Jobs\Poa;


use App\Abstracts\Job;
use App\Models\Poa\PoaActivityPiatPlan;
use App\Models\Poa\PoaActivityPiatRequirements;
use Illuminate\Support\Facades\DB;

class UpdatePoaActivityPiatRequirements extends Job
{
    protected $piatResult;
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
            DB::beginTransaction();
            $this->piatResult = PoaActivityPiatRequirements::updateOrCreate(['id' => $this->request->id], 
                                                                    $this->request->all());
            DB::commit();
            $this->piatResult =  true;

        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }

        return $this->piatResult;
    }
}
