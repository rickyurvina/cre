<?php

namespace App\Http\Livewire\AdministrativeTasks;

use Livewire\Component;
use App\Models\AdministrativeTasks\AdministrativeTask;
use App\Models\Auth\User;
use App\Models\Projects\Stakeholders\ProjectCommunicationMatrix;
use App\Traits\Jobs;
use Carbon\Carbon;
use function view;

class GeneralCreateAdministrativeTask extends Component
{
    use Jobs;

    public $name;
    public $status;
    public $user_id;
    public $company_id;
    public $priority;
    public $start_date;
    public $end_date;
    public $description;
    public $frequency;
    public $frequency_number;
    public $frequency_limit;
    public $type;
    public $users;
    public $subTasks = [];

    protected $listeners = [
        'subTaskAdded',
    ];

    public function mount()
    {
        $this->users = User::get();
    }


    public function subTaskAdded($elements)
    {
        $this->subTasks = $elements;
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'user_id',
            'priority',
            'start_date',
            'end_date',
            'frequency',
            'description',
        ]);
    }

    public function submitTask()
    {
        $data = $this->validate([
            'name' => 'required',
            'user_id' => 'required',
            'priority' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|after:start_date',
        ]);
        $data += [
            'name' => $this->name,
            'user_id' => $this->user_id,
            'project_id' => null,
            'status' => \App\Models\AdministrativeTasks\AdministrativeTask::STATUS_PENDING,
            'type' => 'admt',
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'priority' => $this->priority,
            'frequency' => $this->frequency,
            'description' => $this->description,
            'company_id' => session('company_id'),
            'frequency_number' => $this->frequency_number,
            'subTasks' => $this->subTasks,
        ];
        $dateUntil = Carbon::parse(isset($this->frequency_limit) ? Carbon::parse($this->frequency_limit) : Carbon::parse('31-12-' . now()->year));
        $dateActivity = isset($this->start_date) ? Carbon::parse($this->start_date) : now();
        $dateActivityEnd = isset($this->end_date) ? Carbon::parse($this->end_date) : now();

        while ($dateActivity->lte($dateUntil)) {
            $data['start_date'] = $dateActivity;
            $data['end_date'] = $dateActivityEnd;

            if ($this->frequency) {
                $this->validate([
                    'frequency' => 'required',
                    'frequency_number' => 'required',
                    'frequency_limit' => 'required|after:end_date',
                ]);
                $response = $this->ajaxDispatch(new \App\Jobs\AdministrativeTasks\CreateAdministrativeTask($data));
                switch ($data['frequency']) {
                    case ProjectCommunicationMatrix::DAILY:
                        $dateActivity->addDays($data['frequency_number']);
                        if ($dateActivity->isSaturday()) {
                            $dateActivity->addDays(2);
                            break;
                        }
                        if ($dateActivity->isSunday()) {
                            $dateActivity->addDays(1);
                        }
                        break;
                    case  ProjectCommunicationMatrix::WEEKLY:
                        $dateActivity->addWeeks($data['frequency_number']);
                        break;
                    case  ProjectCommunicationMatrix::MONTHLY:
                        $dateActivity->addMonths($data['frequency_number']);
                        break;
                }
            } else {
                $response = $this->ajaxDispatch(new \App\Jobs\AdministrativeTasks\CreateAdministrativeTask($data));
                break;
            }
        }
        if ($response['success']) {
            flash('messages.success.added', 1)->success();
            return redirect()->route('admin.administrativeTasks');
        } else {
            flash('messages.error', 1)->error();
            return redirect()->route('admin.administrativeTasks');
        }
    }

    public function render()
    {
        return view('livewire.administrativeTasks.general-create-administrative-task');
    }
}
