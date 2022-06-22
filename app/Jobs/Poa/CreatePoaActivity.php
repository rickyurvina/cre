<?php

namespace App\Jobs\Poa;

use App\Abstracts\Job;
use App\Models\Indicators\GoalIndicator\GoalIndicators;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaActivityIndicator;
use Exception;
use Illuminate\Support\Facades\DB;

class CreatePoaActivity extends Job
{
    protected $poaProgramActivity;

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
            $this->poaProgramActivity = PoaActivity::create($this->request->all());
            $data = [
                'cost' => $this->poaProgramActivity->cost,
                'impact' => $this->poaProgramActivity->impact,
                'complexity' => $this->poaProgramActivity->complexity,
            ];
            $this->ajaxDispatch(new UpdatePoaActivityWeight($this->poaProgramActivity->id, $this->poaProgramActivity->program->poa->id, $data));

            $currentYear=now()->year;
            $goalIndicators = GoalIndicators::where('indicators_id', $this->request['indicator_id'])->where('year',$currentYear)->get();

            $indice = 0;
            foreach ($goalIndicators as $goalIndicator) {
                $data = [
                    'poa_activity_id' => $this->poaProgramActivity->id,
                    'indicator_id' => $this->request['indicator_id'],
                    'goal_indicator_id' => $goalIndicator->id,
                    'period' => $indice + 1,
                    'year' => $goalIndicator->year,
                    'start_date' => $goalIndicator->start_date,
                    'end_date' => $goalIndicator->end_date,
                    'company_id' => $this->request['company_id'],
                ];
                PoaActivityIndicator::create($data);
                $indice++;
                if ($indice == 12) {
                    $indice = 0;
                }
            }
            DB::commit();
            return $this->poaProgramActivity;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }
}
