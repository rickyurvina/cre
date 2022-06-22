<?php

namespace App\Jobs\Poa;

use App\Abstracts\Job;
use App\Models\Poa\PoaMatrixReportAgreementCommitment;
use Illuminate\Support\Facades\DB;


class DeletePoaMatrixReportAgreementsCommitments extends Job
{
    protected $model;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function () {
            $aux = PoaMatrixReportAgreementCommitment::find($this->model['id']);
            $aux->delete();
        });

        return true;
    }
}
