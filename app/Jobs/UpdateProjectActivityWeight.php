<?php

namespace App\Jobs;

use App\Abstracts\Job;
use App\Models\Projects\Activities\Task;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateProjectActivityWeight extends Job
{

    protected bool $projectActivityWeightResult;
    protected $task;
    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Task $task, $request)
    {
        //
        $this->task = $task;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        try {
            DB::beginTransaction();
            $this->updateWeight();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            $this->projectActivityWeightResult = false;
            throw new Exception($exception->getMessage());
        }
        return $this->projectActivityWeightResult;
    }

    public function updateWeight()
    {
        $tasks = Task::where('parent', $this->task->parent);

        $min = $tasks->min('amount') ?? 0;
        $max = $tasks->max('amount') ?? 0;
        $tasks = Task::where('parent', $this->task->parent)->get();
        $difference = $max - $min;
        $allActivitiesHasCost = true;
        foreach ($tasks as $item) {
            if ($item->amount==null) {
                $allActivitiesHasCost = false;
                break;
            }
        }
        if (!$difference || $allActivitiesHasCost == false) {
            Task::where('parent', $this->task->parent)
                ->update(['weight' => 1 / $tasks->count()]);
        } else {
            foreach ($tasks as $item) {
                $normalizedCost = 1 + ($item->amount - $min) * 2 / ($max - $min);
                $weight = $normalizedCost + $item->impact + $item->complexity;
                Task::where('id', $item->id)
                    ->update(['weight' => $weight]);
            }
        }

        DB::commit();
        $updatedTasks = Task::where('parent', $this->task->parent)->get();
        $totalIndex = $updatedTasks->sum('weight');
        if ($totalIndex > 0) {
            foreach ($updatedTasks as $item) {
                $weight = $item->weight / $totalIndex;
                Task::where('id', $item->id)
                    ->update(['weight' => $weight]);
            }
        }
    }
}
