<?php

namespace App\Models\Projects\Activities;

use App\Abstracts\Model;
use App\Events\ResultCreated;
use App\Events\TaskColorUpdated;
use App\Events\TaskCreated;
use App\Events\TaskUpdated;
use App\Events\TaskUpdatedCreateGoals;
use App\Events\TaskUpdatedThresholds;
use App\Models\Auth\User;
use App\Models\Budget\Account;
use App\Models\Comment;
use App\Models\Common\CatalogGeographicClassifier;
use App\Models\Indicators\Indicator\Indicator;
use App\Models\Projects\Catalogs\ProjectLineActionService;
use App\Models\Projects\Catalogs\ProjectLineActionServiceActivity;
use App\Models\Projects\Configuration\ProjectThreshold;
use App\Models\Projects\Objectives\ProjectObjectives;
use App\Models\Projects\Project;
use App\Models\Projects\ProjectReferentialBudget;
use App\Scopes\Company;
use App\Traits\LogToProject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Plank\Mediable\Mediable;
use Spatie\Activitylog\Traits\LogsActivity;

class Task extends Model
{
    use HasFactory, Mediable, LogsActivity, LogToProject;


    protected bool $tenantable = false;

    const STATUS_PROGRAMMED = 'Programada';
    const STATUS_PROGRESS = 'En progreso';
    const STATUS_FINISHED = 'Terminada';
    const STATUS_FINISHED_DELAY = 'Terminada con Retraso';

    const STATUSES =
        [
            self::STATUS_PROGRAMMED => self::STATUS_PROGRAMMED,
            self::STATUS_PROGRESS => self::STATUS_PROGRESS,
            self::STATUS_FINISHED => self::STATUS_FINISHED,
            self::STATUS_FINISHED_DELAY => self::STATUS_FINISHED_DELAY,
        ];
    const STATUSES_DD =
        [
            self::STATUS_PROGRAMMED => [
                'text' => self::STATUS_PROGRAMMED,
                'icon' => '',
                'style' => 'badge badge-info'
            ],
            self::STATUS_FINISHED => [
                'text' => self::STATUS_FINISHED,
                'icon' => '',
                'style' => 'badge badge-success'
            ],
        ];
    const STATUS_BG = [
        self::STATUS_PROGRAMMED => 'color-primary-700',
        self::STATUS_PROGRESS => 'color-secondary-700',
        self::STATUS_FINISHED => 'color-success-700',
        self::STATUS_FINISHED_DELAY => 'color-success-700',
    ];
    const STATUS_BGC = [
        self::STATUS_PROGRAMMED => 'badge-primary',
        self::STATUS_PROGRESS => 'badge-secondary',
        self::STATUS_FINISHED => 'badge-success',
        self::STATUS_FINISHED_DELAY => 'badge-success',
    ];

    const CATEGORIES = [
        3 => [
            'text' => '',
            'icon' => 'fal fa-chevron-up color-danger-500',
        ],
        2 => [
            'text' => '',
            'icon' => 'fal fa-equals color-warning-500',
        ],
        1 => [
            'text' => '',
            'icon' => 'fal fa-chevron-down color-blue',
        ]
    ];

    protected $fillable =
        [
            'code',
            'text',
            'description',
            'duration',
            'progress',
            'start_date',
            'end_date',
            'parent',
            'type',
            'sortorder',
            'open',
            'color',
            'status',
            'impact',
            'complexity',
            'assumptions',
            'amount',
            'weight',
            'project_id',
            'company_id',
            'owner',
            'indicator_id',
            'taskable_id',
            'taskable_type',
            'taskable_type',
            'objective_id',
            'owner_id',
            'location_id',
            'planning',
        ];

    protected $casts = [
        'owner' => 'array',
        'planning' => 'array',
        'referential_budget' => 'array',
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d'
    ];

    protected $dateFormat = 'Y-m-d';

    protected $table = 'prj_tasks';

    const UPDATED = 'La tarea fue actualizada';
    const CREATED = 'La tarea fue creada';
    const DELETED = 'La tarea fue eliminada';

    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->text = mb_strtoupper($model->text);
            $model->description = mb_strtoupper($model->description);
            $model->code = mb_strtoupper($model->code);
            $model->weight = 0;
            $model->complexity = 1;
            $model->impact = 1;
            $model->amount = null;
            $model->assumptions = mb_strtoupper($model->assumptions);
        });
        static::updating(function ($model) {
            $model->text = mb_strtoupper($model->text);
            $model->code = mb_strtoupper($model->code);
            $model->description = mb_strtoupper($model->description);
            $model->assumptions = mb_strtoupper($model->assumptions);
        });
    }

    protected static function booted()
    {
        static::updated(function ($model) {
            TaskUpdated::dispatch($model);
            if ((isset($model->getChanges()['start_date'])) || (isset($model->getChanges()['end_date']))) {
                TaskUpdatedCreateGoals::dispatch($model);
            }
            if ((isset($model->getChanges()['start_date'])) || (isset($model->getChanges()['end_date'])) || (isset($model->getChanges()['progress']))) {
                if ($model->threshold->count() > 0) {
                    TaskUpdatedThresholds::dispatch($model);
                }
            }
            if (isset($model->getChanges()['color']) && $model->childs->count() > 0) {
                TaskColorUpdated::dispatch($model);
            }
        });
        static::deleted(function ($model) {
            if ($model->taskable_id) {
                $class = App::make($model->taskable_type)->find($model->taskable_id);
                if ($class && $model->taskable_type != ProjectLineActionServiceActivity::class) {
                    $class->delete();
                }
            }
        });

        static::deleting(function ($model) {
            $model->goals->each->delete();
            $model->activitiesTask->each->delete();
            $model->threshold->each->delete();
            $model->indicators->each->delete();
            $model->services->each->delete();
            if ($model->type == 'project' && $model->projectReferentialBudget) {
                $model->projectReferentialBudget->delete();
            }
        });

        static::created(function ($model) {
            if (($model->type == 'project' && $model->parent != 'root')) {
                ResultCreated::dispatch($model);
            }
            if ($model->type === 'task') {
                $model->status = self::STATUS_PROGRAMMED;
                if ($model->parentOfTask) {
                    $model->color = $model->parentOfTask->color;
                }
                $model->save();
                TaskCreated::dispatch($model);
            }
        });

        static::addGlobalScope('sortOrder', function (Builder $builder) {
            $builder->orderBy('sortorder');
        });
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->withoutGlobalScope(\App\Scopes\Company::class);
    }

    public function childs()
    {
        return $this->hasMany(Task::class, 'parent', 'id')->withoutGlobalScope(Company::class);
    }

    public function parentOfTask()
    {
        return $this->belongsTo(Task::class, 'parent', 'id')->withoutGlobalScope(Company::class);
    }

    /**
     * Task owner
     *
     * @return BelongsTo
     */
    public function responsible()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        switch ($eventName) {
            case 'updated':
                return Task::UPDATED;
                break;
            case 'created':
                return Task::CREATED;
                break;
            case 'deleted':
                return Task::DELETED;
                break;
        }
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class, 'indicator_id');
    }

    /**
     * Get all of the plan details indicators.
     *
     * @return MorphMany
     */
    public function indicators(): MorphMany
    {
        return $this->morphMany(Indicator::class, 'indicatorable')->withoutGlobalScope(Company::class);
    }

    /**
     * Get all of the plan details indicators.
     *
     * @return MorphMany
     */
    public function accounts(): MorphMany
    {
        return $this->morphMany(Account::class, 'accountable');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function objective()
    {
        return $this->belongsTo(ProjectObjectives::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(ProjectLineActionService::class, 'prj_tasks_services', 'task_id', 'service_id');
    }

    public function goals()
    {
        return $this->hasMany(TaskDetails::class, 'prj_task_id')->orderBy('id');
    }

    public function workLogs()
    {
        return $this->hasMany(TaskWorkLog::class, 'prj_task_id');
    }

    public function activitiesTask()
    {
        return $this->hasMany(ActivityTask::class, 'prj_task_id')->orderBy('id', 'asc');
    }

    public function getDifferenceStartEndDates()
    {
        $date1 = $this->start_date;
        $date2 = $this->end_date;
        $diff = $date1->diff($date2);
        $diff = ($diff->days * 24) + ($diff->i);
        return $diff;
    }

    public function getProgressTimeUpDate()
    {
        if ($this->start_date < now()) {
            $date1 = $this->start_date;
            $date2 = now();
            $diff = $date1->diff($date2);
            $diff = ($diff->days * 24) + ($diff->i);
            $diffStartEndDates = $this->getDifferenceStartEndDates();
            if ($diffStartEndDates > 0) {
                return intval($diff / $diffStartEndDates * 100) > 100 ? 100 : intval($diff / $diffStartEndDates * 100);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function calcSemaphore()
    {
        $time = $this->getProgressTimeUpDate();
        $progressPhysic = $this->progress;
        if ($this->threshold->count()>0) {
            $properties = $this->threshold->first()->properties;
            if ($progressPhysic >= $properties['progress']['max'] && $time >= $properties['time']['max']) {
                return 'color-success-700';
            } else if (($progressPhysic >= $properties['progress']['min'] && $progressPhysic < $properties['progress']['max']) && ($time >= $properties['time']['min'] && $time < $properties['progress']['max'])) {
                return 'color-warning-700';
            } else if ($progressPhysic < $properties['progress']['min'] && $time > $properties['time']['max']) {
                return 'color-danger-700';
            } else if ($progressPhysic < $properties['progress']['max'] && $time >= $properties['time']['max']) {
                return 'color-danger-700';
            } else if ($progressPhysic >= $properties['progress']['max']) {
                return 'color-success-700';
            } else {
                return 'color-info-700';
            }
        } else {
            return 'color-info-700';
        }

    }

    public function getProgressPhysic()
    {
        if ($this->goal > 0) {
            return intval($this->advance / $this->goal * 100);
        } else {
            return 0;
        }
    }

    /**
     * Location activity supports
     *
     * @return BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(CatalogGeographicClassifier::class, 'location_id');
    }


//funcion para validar si se puede crear una partida presupuestaria a la actividad
    public function validateCrateBudget()
    {
        if ($this->indicator && $this->location_id) {
            return true;
        } else {
            return false;
        }
    }

    public function getTotalBudget(string $status)
    {
        $accounts = $this->accounts;
        $total = 0;
        foreach ($accounts as $account) {
            $total += $account->balanceDraft($status)->getAmount();
        }
        $total = money($total);
        return $total;
    }

    public function getBalanceEncoded()
    {
        $accounts = $this->accounts;
        $total = 0;
        foreach ($accounts as $account) {
            $total += $account->getBalanceEncoded()->getAmount();
        }
        $total = money($total);
        return $total;
    }

    public function getBalanceAs()
    {
        $accounts = $this->accounts;
        $total = 0;
        foreach ($accounts as $account) {
            $total += $account->getBalanceAsAttribute()->getAmount();
        }
        $total = money($total);
        return $total;
    }

    public function getPercentageBudget()
    {
        if ($this->getBalanceEncoded()->getAmount() > 0) {
            return intval($this->getBalanceAs()->getAmount() / $this->getBalanceEncoded()->getAmount() * 100) ?? 0;
        } else {
            return 0;
        }
    }

    public function getCertifiedValues()
    {
        $accounts = $this->accounts;
        $total = 0;
        foreach ($accounts as $account) {
            $total += $account->getCertifiedValues()->getAmount();
        }
        $total = money($total);
        return $total;
    }

    public function threshold()
    {
        return $this->morphMany(ProjectThreshold::class, 'thresholdable');
    }

    public function projectReferentialBudget()
    {
        return $this->belongsTo(ProjectReferentialBudget::class, 'task_id');
    }
}
