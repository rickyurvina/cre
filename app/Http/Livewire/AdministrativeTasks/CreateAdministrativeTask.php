<?php

namespace App\Http\Livewire\AdministrativeTasks;

use App\Models\AdministrativeTasks\AdministrativeTask;
use App\Models\Auth\User;
use App\Models\Projects\Project;
use App\Models\Projects\Stakeholders\ProjectCommunicationMatrix;
use App\Traits\Jobs;
use Carbon\Carbon;
use Livewire\Component;
use function view;

class CreateAdministrativeTask extends Component
{
    use Jobs;

    public $project;
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

    public function mount(Project $project = null)
    {
        if ($project) {
            $this->project = $project;
        }
        $this->users = User::get();
    }

    public function render()
    {
        return view('livewire.administrativeTasks.create-administrative-task');
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
        $projectId = null;
        if ($this->project->id) {
            $projectId = $this->project->id;
        }
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
            'project_id' => $projectId,
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
        $user = User::find($this->user_id);
        if ($response['success']) {
            if ($user) {
                $notificationArray = [];
                $notificationArray[0] = [
                    'via' => ['database'],
                    'database' => [
                        'username' => $user->name,
                        'title' => trans('general.activity_assignment'),
                        'description' => __($user->name . ' ' . trans('general.you_have_been_assigned_for_the_activity') . ' ' .
                            $this->name . trans('general.in_the_project') . ' ' . $this->project->name . ' ' . (trans('general.with_execution_date')) . ' ' . $this->start_date),
                        'url' => route('projects.administrativeTasks', $this->project->id),
                        'salutation' => trans('general.salutation'),
                    ]];
                $notificationArray[1] = [
                    'via' => ['mail'],
                    'mail' => [
                        'subject' => __('activity_assignment'),
                        'greeting' => __('general.dear'),
                        'line' => ($user->name . ' ' . trans('general.you_have_been_assigned_for_the_activity') . ' ' .
                            $this->name . trans('general.in_the_project') . ' ' . $this->project->name . (trans('general.with_execution_date')) . ' ' . $this->start_date),
                        'salutation' => trans('general.salutation'),
                        'url' => route('projects.administrativeTasks', $this->project->id),
                    ]
                ];
                foreach ($notificationArray as $notification) {
                    $notificationData = [
                        'user' => $user,
                        'notificationArray' => $notification,
                    ];
                    $this->ajaxDispatch(new \App\Jobs\SendNotification($notificationData));
                }
            }

            flash(trans_choice('messages.success.added', 1, ['type' => trans_choice('general.activities', 1)]))->success();
        } else {
            flash('messages.error', 1)->error();
        }

        if ($this->project->type == Project::TYPE_MISSIONARY_PROJECT || $this->project->type == Project::TYPE_EMERGENCY) {
            return redirect()->route('projects.administrativeTasks', $this->project->id);
        } else {
            return redirect()->route('projects.administrativeTasksInternal', $this->project->id);
        }
    }

}
