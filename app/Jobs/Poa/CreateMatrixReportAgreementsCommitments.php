<?php

namespace App\Jobs\Poa;

use App\Abstracts\Job;
use App\Models\Indicators\GoalIndicator\GoalIndicators;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaActivityIndicator;
use App\Models\Poa\PoaActivityPiat;
use App\Models\Poa\PoaActivityPiatReport;
use App\Models\Poa\PoaMatrixReportAgreementCommitment;
use Exception;
use Illuminate\Support\Facades\DB;

class CreateMatrixReportAgreementsCommitments extends Job
{
    protected $matrixReportAgreComm;
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
            foreach($this->request->all() as $item) {
                PoaMatrixReportAgreementCommitment::updateOrCreate(['id' => $item['id']], $item);
            }
            // $this->matrixReportAgreComm = PoaMatrixReportAgreementCommitment::updateOrCreate(['id' => $this->request->id], 
            //                                                             $this->request->all());

            DB::commit();
            // $this->matrixReportAgreComm =  true;

        } catch (Exception $exception) {
            DB::rollBack();
            $this->piatResult = false;
            throw new Exception($exception->getMessage());
        }
    }
}
