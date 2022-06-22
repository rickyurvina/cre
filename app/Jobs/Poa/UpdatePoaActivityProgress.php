<?php

namespace App\Jobs\Poa;

use App\Abstracts\Job;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaActivityIndicator;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdatePoaActivityProgress extends Job
{
    protected bool $poaActivityProgressResult;

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
            $progressTotal = 0;
            foreach ($this->request as $item) {
                if ($item['menWomenProgressType']) {
                    $item['progress'] = ($item['menProgress'] > 0 ? $item['menProgress'] : 0) + ($item['womenProgress'] > 0 ? $item['womenProgress'] : 0);
                }
                PoaActivityIndicator::where('id', $item['id'])
                    ->update([
                        'progress' => $item['progress'],
                        'men_progress' => $item['menProgress'],
                        'women_progress' => $item['womenProgress'],
                    ]);
                $progressTotal += $item['progress'];
            }
            $poaActivity = PoaActivity::find($this->id);
            $poaActivity->progress = $progressTotal;
            $poaActivity->save();
            DB::commit();
            $this->poaActivityProgressResult = true;
        } catch (Exception $exception) {
            $this->poaActivityProgressResult = false;
            throw new Exception($exception->getMessage());
        }
        return $this->poaActivityProgressResult;
    }
}
