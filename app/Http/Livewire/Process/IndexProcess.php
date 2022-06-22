<?php

namespace App\Http\Livewire\Process;

use App\Abstracts\TableComponent;
use App\Jobs\Process\DeleteProcess;
use App\Models\Auth\User;
use App\Models\Process\Process;
use App\Traits\Jobs;

class IndexProcess extends TableComponent
{
    use  Jobs;

    public $search = '';

    protected $listeners = ['processCreated' => 'render'];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => ''],
        'sortDirection' => ['except' => '']
    ];

    public function render()
    {
        $processes = Process::with(['owner', 'indicators', 'department', 'activitiesProcess'])->whereHas('department', function ($q) {
            $q->whereIn('id', \user()->departments->pluck('id'));
        })->when($this->sortField, function ($q) {
            $q->orderBy($this->sortField, $this->sortDirection);
        })
            ->when($this->search, function ($query) {
                $query->where('code', 'iLIKE', '%' . $this->search . '%')
                    ->orWhere('name', 'iLIKE', '%' . $this->search . '%');
            })
            ->paginate(setting('default.list_limit', '25'));

        return view('livewire.process.index-process', compact('processes'));
    }

    public function delete($id)
    {
        $process = Process::find($id);
        $response = $this->ajaxDispatch(new DeleteProcess($process));
        if ($response['success']) {
            flash(trans_choice('messages.success.deleted', 0, ['type' => trans_choice('general.module_process', 1)]))->success()->livewire($this);;
        } else {
            flash($response['message'])->error()->livewire($this);;
        }
    }

}