<?php

namespace App\Jobs\Poa;

use App\Abstracts\Job;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaActivityIndicator;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdatePoaActivityGoal extends Job
{
    protected bool $poaActivityGoalResult;
    protected $id;
    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $request)
    {
        $this->id = $id;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return int
     * @throws Exception
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $goalTotal = 0;
            $progressTotal = 0;
            foreach ($this->request as $item) {
                PoaActivityIndicator::where('id', $item['id'])
                    ->update([
                        'goal' => $item['goal'],
                        'progress' => $item['progress'],
                    ]);
                $goalTotal += $item['goal'];
                $progressTotal += $item['progress'];
            }
            PoaActivity::find($this->id)
                ->update([
                    'goal' => $goalTotal,
                    'progress' => $progressTotal,
                ]);
            DB::commit();
            $this->poaActivityGoalResult = true;
        } catch (Exception $exception) {
            DB::rollBack();
            $this->poaActivityGoalResult = false;
            throw new Exception($exception->getMessage());
        }
        return $this->poaActivityGoalResult;
    }
}
