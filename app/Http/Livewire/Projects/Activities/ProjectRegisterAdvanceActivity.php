<?php

namespace App\Http\Livewire\Projects\Activities;

use App\Jobs\Projects\CreateTaskWorkLog;
use App\Models\Auth\User;
use App\Models\Budget\Account;
use App\Models\Budget\TransactionDetail;
use App\Models\Common\CatalogGeographicClassifier;
use App\Models\Poa\Poa;
use App\Models\Poa\PoaIndicatorConfig;
use App\Models\Poa\PoaProgram;
use App\Models\Projects\Activities\ActivityTask;
use App\Models\Projects\Activities\Task;
use App\Models\Projects\Catalogs\ProjectLineActionServiceActivity;
use App\Traits\Jobs;
use App\Traits\Uploads;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProjectRegisterAdvanceActivity extends Component
{
    use WithFileUploads, Uploads, Jobs;

    public ?Task $task = null;
    public $scores = [];
    public $programs = [];
    public $search = '';
    public $owner;
    public array $isSelected = [], $hours = [], $data = [], $goals = [], $progress = [];
    public $users;
    public $taskGoals;
    public $indicators;
    public $advance;
    public $period;
    public $showPanelWork = false;
    public $showAddActivity = false;
    public $valueWorkLog;
    public $valueWorkLogText;
    public $registerTime;
    public $taskDetail;
    public $widthProgress;
    public $project;
    public $workLogs;
    public $codeActivityTask;
    public $nameActivityTask;
    public $activitiesTask;
    public $code;
    public int $progressBarSubActiviites = 0;
    public int $workLogCount = 5;
    public int $countActivityTasks = 5;
    public array $hoursUsers = [];
    public $rule;
    public $transaction;
    public $expenses;
    public $poaId;
    public $credits;
    public $total = 0;
    public $poaActivityIndicatorId = null;
    public $poaActivityIndicatorName = '';
    public $isGantt = false;
    public $isCalendar = false;
    public $internal = false;
    public $poaProgramId = null;
    public $programIndicators = [];
    public $poaProgramName = '';
    public $selectedLocationName = '';
    public $searchLocation = '';
    public $locations = [];
    public $selectedLocationId = null;
    public $typeLocation;
    public $limitLocation = 10;
    public $sumGoals = 0;

    protected $listeners =
        [
            'openAdvance',
            'refreshPage' => '$refresh',
            'statusUpdatedSubActivity',
            'workLogEdited',
            'notifyUser',
            'fileAdded',
            'commentAdded'
        ];

    public function render()
    {
        $search = $this->search;
        if ($this->task) {
            $this->task->refresh();
            $this->task->load(['goals', 'project.members']);
            $this->users = $this->task->project->members->pluck('user');
            if ($this->users) {
                $this->users = User::WithMedia()->whereIn('id', $this->users->pluck('id'))->when($search, function ($q, $search) {
                    $q->where('name', 'iLIKE', '%' . $search . '%');
                })->get();
            }
            $this->rule = 'required|alpha_num|alpha_dash|max:6|' . Rule::unique('prj_tasks')
                    ->where('type', 'task')
                    ->where('parent', $this->task->parent)
                    ->where('deleted_at', null)
                    ->ignore($this->task->id);
        }
        return view('livewire.projects.activities.project-register-advance-activity');
    }

    public function workLogEdited()
    {
        flash('Valores Actualizados')->success()->livewire($this);
        $this->emitSelf('refreshPage');
    }

    public function openAdvance($payload)
    {
        if (isset($payload['isGantt'])) {
            $this->isGantt = true;
        }
        if (isset($payload['isCalendar'])) {
            $this->isCalendar = true;
        }
        if (isset($payload['internal'])) {
            $this->internal = true;
        }
        $this->task = Task::with([
            'comments',
            'project',
            'indicator',
            'goals',
            'responsible',
            'indicators',
            'objective.results',
            'services',
            'goals.comments',
            'workLogs.comments',
            'company',
            'activitiesTask',
        ])->find($payload['id']);
        $this->project = $this->task->project;
        if ($this->task->owner != null) {
            foreach ($this->task->owner as $item) {
                $this->isSelected += [$item['resource_id'] => true];
                $this->hours += [$item['resource_id'] => $item['value']];
            }
        }
        if ($this->task->goals->count() > 0) {
            $this->taskGoals = $this->task->goals;
            if ($this->taskGoals->sum('goal') > 0) {
                $this->goals = [];
                $this->progress = [];
                foreach ($this->taskGoals as $goal) {
                    $this->goals[$goal->id] = $goal->goal;
                    $this->progress[$goal->id] = $goal->progress;
                }
            }
        }

        if ($this->task) {
            if ($this->task->taskable_type == ProjectLineActionServiceActivity::class) {
                $this->indicators = ProjectLineActionServiceActivity::find($this->task->taskable_id)->service->lineAction->program->children()->get()->pluck('indicators')->collapse();
            }
            $this->registerTime = $this->task->workLogs->sum('value');
            if ($this->task->duration > 0) {
                $this->widthProgress = intval($this->registerTime / $this->task->duration * 100);
            }
            $this->workLogs = $this->task->workLogs;
            $this->activitiesTask = $this->task->activitiesTask;
            $this->poaActivityIndicatorId = $this->task->indicator_id;
            $this->owner = User::find($this->task->owner_id);
            $currentYear = (int)date('Y');
            $poa = Poa::where('year', $currentYear)->first();
            if ($poa) {
                $this->poaId = $poa->id;
                $this->programs = PoaProgram::with(['planDetail'])->where('poa_id', $this->poaId)->get();
            }

            if ($this->activitiesTask->count() > 0) {
                $this->progressBarSubActiviites = intval($this->activitiesTask->where('status', Task::STATUS_FINISHED)->count() / $this->activitiesTask->count() * 100);
            }
            if ($this->task->indicator) {
                $programId = $this->task->indicator->indicatorable->id;
                $this->poaActivityIndicatorName = $this->task->indicator->name;
                $this->poaProgramName = $this->task->indicator->indicatorable->parent->name;
                $this->loadIndicators($programId);
            }
        }
        if (!isset($payload['refresh'])) {
            $this->emit('toggleRegisterAdvanceActivity');
        }

        if ($this->task->location_id) {
            self::locationsMount();
            self::updatedSelectedLocationId($this->task->location_id);
        }
        $this->loadBudget();
    }

    /**
     * Load Program Indicators
     *
     */
    public function loadIndicators($poaProgramId)
    {
        $this->poaProgramId = $poaProgramId;
        self::updatedPoaProgramId($poaProgramId, true);
    }

    public function updatedPoaProgramId($value, bool $inMount = null)
    {
        foreach ($this->programs as $program) {
            if ($inMount == true) {
                if ($program['plan_detail_id'] == $value) {
                    $this->poaProgramName = $program['name'];
                    $this->poaProgramName = $program->planDetail->name;
                    $value = $program['id'];
                }
            } else {
                if ($program['id'] == $value) {
                    $this->poaProgramName = $program['name'];
                    $this->poaProgramName = $program->planDetail->name;
                }
            }

        }

        $this->programIndicators = PoaIndicatorConfig::selected()->where([
            ['poa_id', '=', $this->poaId],
            ['program_id', '=', $value],
        ])->with('indicator')->get();

    }

    /**
     * Reset Form on Cancel
     *
     */
    public function resetForm()
    {
        $this->reset(
            [
                'task',
                'scores',
                'programs',
                'search',
                'isSelected',
                'hours',
                'data',
                'goals',
                'progress',
                'users',
                'taskGoals',
                'indicators',
                'advance',
                'period',
                'showPanelWork',
                'valueWorkLog',
                'valueWorkLogText',
                'registerTime',
                'taskDetail',
                'hoursUsers',
                'workLogCount',
                'showAddActivity',
                'nameActivityTask',
                'codeActivityTask',
                'progressBarSubActiviites',
                'selectedLocationId',
                'selectedLocationName',
                'searchLocation',
                'locations',
            ]
        );
        if ($this->isGantt) {
            return redirect()->route('projects.activities', $this->project->id);
        } elseif ($this->isCalendar && $this->internal) {
            return redirect()->route('projects.calendarInternal', $this->project->id);
        } elseif ($this->isCalendar && !$this->internal) {
            return redirect()->route('projects.calendar', $this->project->id);
        } elseif ($this->internal) {
            return redirect()->route('projects.activities_resultsInternal', $this->project->id);
        } else {
            return redirect()->route('projects.activities_results', $this->project->id);
        }

    }

    public function notifyUser(int $userId)
    {
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $notificationArray = [];
                $notificationArray[0] = [
                    'via' => ['database'],
                    'database' => [
                        'username' => $user->name,
                        'title' => trans('general.activity_assignment'),
                        'description' => __($user->name . ' ' . trans('general.you_have_been_assigned_for_the_activity') . ' ' .
                            $this->task->text . ' ' . trans('general.in_the_project') . ' ' . $this->project->name . ' ' . (trans('general.with_execution_date')) . ' ' . $this->project->start_date->format('Y-m-d')),
                        'url' => route('projects.index'),
                        'salutation' => trans('general.salutation'),
                    ]];
                $notificationArray[1] = [
                    'via' => ['mail'],
                    'mail' => [
                        'subject' => trans('general.activity_assignment'),
                        'greeting' => trans('general.dear'),
                        'line' => ($user->name . ' ' . trans('general.you_have_been_assigned_for_the_activity') . ' ' .
                            $this->task->text . ' ' . trans('general.in_the_project') . ' ' . $this->project->name . ' ' . (trans('general.with_execution_date')) . ' ' . $this->project->start_date->format('Y-m-d')),
                        'salutation' => trans('general.salutation'),
                        'url' => ('projects.index'),
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
        }


    }

    public function fileAdded()
    {
        if ($this->owner) {
            $notificationArray = [];
            $notificationArray[0] = [
                'via' => ['database'],
                'database' => [
                    'username' => $this->owner->name,
                    'title' => trans('files_were_added'),
                    'description' => ($this->owner->name . ' ' . trans('general.files_were_added') . ' ' . trans('general.in_the_activity') . ' ' .
                        $this->task->text . ' ' . trans('general.in_the_project') . ' ' . $this->project->name . '.'),
                    'url' => route('projects.activities_results', $this->project->id),
                    'salutation' => trans('general.salutation'),
                ]];
            $notificationArray[1] = [
                'via' => ['mail'],
                'mail' => [
                    'subject' => trans('files_were_added'),
                    'greeting' => trans('general.dear'),
                    'line' => ($this->owner->name . ' ' . trans('general.files_were_added') . ' ' . trans('general.in_the_activity') . ' ' .
                        $this->task->text . ' ' . trans('general.in_the_project') . ' ' . $this->project->name . '.'),
                    'salutation' => trans('general.salutation'),
                    'url' => route('projects.activities_results', $this->project->id),
                ]
            ];
            foreach ($notificationArray as $notification) {
                $notificationData = [
                    'user' => $this->owner,
                    'notificationArray' => $notification,
                ];
                $this->ajaxDispatch(new \App\Jobs\SendNotification($notificationData));
            }
        }
    }

    public function commentAdded()
    {
        if ($this->owner) {
            $notificationArray = [];
            $notificationArray[0] = [
                'via' => ['database'],
                'database' => [
                    'username' => $this->owner->name,
                    'title' => trans('comments_were_added'),
                    'description' => __($this->owner->name . ' ' . trans('general.comments_were_added') . ' ' . trans('general.in_the_activity') . ' ' .
                        $this->task->text . ' ' . trans('general.in_the_project') . ' ' . $this->project->name . '.'),
                    'url' => route('projects.activities_results', $this->project->id),
                    'salutation' => trans('general.salutation'),
                ]];
            $notificationArray[1] = [
                'via' => ['mail'],
                'mail' => [
                    'subject' => trans('comments_were_added'),
                    'greeting' => __('general.dear'),
                    'line' => __($this->owner->name . ' ' . trans('general.comments_were_added') . ' ' . trans('general.in_the_activity') . ' ' .
                        $this->task->text . ' ' . trans('general.in_the_project') . ' ' . $this->project->name . '.'),
                    'salutation' => trans('general.salutation'),
                    'url' => route('projects.activities_results', $this->project->id),
                ]
            ];
            foreach ($notificationArray as $notification) {
                $notificationData = [
                    'user' => $this->owner,
                    'notificationArray' => $notification,
                ];
                $notificationResponse = $this->ajaxDispatch(new \App\Jobs\SendNotification($notificationData));
            }
        }

    }

    public function saveUsers()
    {
        $this->data = [];
        foreach ($this->isSelected as $index => $item) {
            if ($item) {
                $this->data[] = [
                    'resource_id' => $index,
                    'value' => $this->hours[$index],
                ];
            }
        }
        $this->task->owner = $this->data;
        $this->task->save();
        flash('Valores Actualizados')->success()->livewire($this);
        $this->emit('openAdvance', ['id' => $this->task->id, 'refresh' => true]);
    }

    public function deleteWorkLog(int $id)
    {
        $data = [
            'id' => $id
        ];
        $this->ajaxDispatch(new \App\Jobs\Projects\DeleteWorkLog($data));
        $this->openAdvance(['id' => $this->task->id, 'refresh' => true]);
    }

    public function updateGoals()
    {
        $this->taskGoals = $this->task->goals;
        foreach ($this->goals as $index => $goal) {
            $taskGoal = $this->taskGoals->find($index);
            if ($taskGoal && $taskGoal->goal != $goal) {
                $taskGoal->goal = is_numeric($goal) ? $goal : null;
                $taskGoal->save();
            }
        }
        flash('Valores Actualizados')->success()->livewire($this);
        $this->emit('updateResultsActivities');
        self::openAdvance(['id' => $this->task->id, 'refresh' => true]);
    }

    public function updatedPeriod()
    {
        $this->taskDetail = $this->task->goals->find($this->period);
        $this->advance = $this->taskDetail->progress;
    }

    public function updateProgress()
    {
        $taskGoal = $this->task->goals->find($this->period);
        $oldProgress = $taskGoal->progress;
        $newValue = $oldProgress + $this->advance;
        $taskGoal->progress = $newValue;
        $taskGoal->save();
        flash('Valor Actualizado')->success()->livewire($this);
        $this->reset([
            'period',
            'advance',
            'taskDetail'
        ]);
        $this->emit('updateResultsActivities');
        $this->emit('openAdvance', ['id' => $this->task->id, 'refresh' => true]);
    }

    public function showPanelWorkLog()
    {
        $this->showPanelWork = true;
    }

    public function saveWorkLog()
    {
        $data = [
            'value' => $this->valueWorkLog,
            'description' => $this->valueWorkLogText,
            'user_id' => Auth::user()->id,
            'prj_task_id' => $this->task->id,
            'company_id' => session('company_id')
        ];
        $response = $this->ajaxDispatch(new CreateTaskWorkLog($data));
        if ($response['success']) {
            flash(trans_choice('messages.success.added', 1, ['type' => trans('general.hours_per_day')]))->success()->livewire($this);
            $this->showPanelWork = false;
            $this->reset([
                'valueWorkLog',
                'valueWorkLogText'
            ]);
            $this->registerTime = $this->task->workLogs->sum('value');
            $this->emitSelf('refreshPage');
            flash('Registro Guardado')->success()->livewire($this);
            $this->emit('openAdvance', ['id' => $this->task->id, 'refresh' => true]);
        } else {
            $this->dispatchBrowserEvent('alert', ['title' => $response['message'], 'icon' => 'error']);
        }
    }

    public function chargeWorkLog($bool = null)
    {
        if ($bool) {
            $this->reset(['workLogCount']);
        } else {
            $this->workLogCount += 5;
        }
    }

    public function saveActivityTask(int $id)
    {
        $this->validate([
            'nameActivityTask' => ['required', 'min:3', 'max:250'],
            'codeActivityTask' => ['required', 'alpha_num', 'alpha_dash', 'max:5'],
        ]);
        $data = [
            'name' => $this->nameActivityTask,
            'code' => $this->codeActivityTask,
            'status' => Task::STATUS_PROGRAMMED,
            'prj_task_id' => $id,
        ];
        $activityTask = new ActivityTask;
        $activityTask->create($data);
        flash('Actividad Agregada')->success()->livewire($this);
        $this->showAddActivity = false;
        $this->task->refresh();
        $this->activitiesTask = $this->task->activitiesTask;
        $this->reset(['nameActivityTask', 'codeActivityTask']);
        self::statusUpdatedSubActivity();
    }

    public function chargeActivityTask($bool = null)
    {
        if ($bool) {
            $this->reset(['countActivityTasks']);
        } else {
            $this->countActivityTasks += 5;
        }
    }

    public function statusUpdatedSubActivity()
    {
        $this->task->refresh();
        $this->activitiesTask = $this->task->activitiesTask;
        if ($this->activitiesTask->count() > 0) {
            $this->progressBarSubActiviites = intval($this->activitiesTask->where('status', Task::STATUS_FINISHED)->count() / $this->activitiesTask->count() * 100);
        } else {
            $this->progressBarSubActiviites = 0;
        }
        if ($this->progressBarSubActiviites > 0 && $this->progressBarSubActiviites < 100) {
            $this->task->status = Task::STATUS_PROGRESS;
        }
        $this->task->progress = $this->progressBarSubActiviites;
        $this->task->save();
    }

    public function loadBudget()
    {
        $expenses = Account::where([
                ['type', Account::TYPE_EXPENSE],
                ['accountable_id', $this->task->id],
                ['accountable_type', Task::class],
            ]
        );

        foreach ($expenses->get() as $expens) {
            $this->total += $expens->balance->getAmount();
        }

        $this->expenses = $expenses->get();
    }

    public function updatedPoaActivityIndicatorId($value)
    {
        foreach ($this->programIndicators as $programIndicator) {
            if ($programIndicator->indicator->id == $value) {
                $this->poaActivityIndicatorName = $programIndicator->indicator->name;
                $this->task->indicator_id = $programIndicator->indicator->id;
                $this->task->save();
            }
        }
    }

    public function deleteSubTaskActivity(int $id)
    {
        ActivityTask::destroy($id);
        self::statusUpdatedSubActivity();
        $this->openAdvance(['id' => $this->task->id, 'refresh' => true]);
    }

    public function updatedTypeLocation($value)
    {
        $this->selectedLocationId = null;
        $this->selectedLocationName = '';
        $this->searchLocation = '';
        self::locations();
    }

    public function updatedSearchLocation($value)
    {
        self::locations();
    }

    public function updatedSelectedLocationId($value)
    {
        $this->selectedLocationName = $this->locations->where('id', $value)->first()->getPath();
        $this->searchLocation = '';
        if ($value != $this->task->location_id) {
            $this->task->location_id = $value;
            $this->task->save();
        }
        self::locations();
    }

    private function locations()
    {
        $this->locations = CatalogGeographicClassifier::when($this->typeLocation, function ($q) {
            $q->where('type', $this->typeLocation);
        })->when($this->searchLocation != '', function ($q) {
            $q->where(function ($q) {
                $q->where('full_code', 'iLike', '%' . $this->searchLocation . '%')
                    ->orWhere('description', 'iLike', '%' . $this->searchLocation . '%');
            });
        })->limit($this->limitLocation)->get();
    }

    private function locationsMount()
    {
        $this->locations = CatalogGeographicClassifier::where('id', $this->task->location_id);
    }
}
