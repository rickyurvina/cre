<?php

namespace App\Models\Poa;

use App\Abstracts\Model;
use App\Events\ActivityProcessed;
use App\Models\Admin\Company;
use App\Models\Auth\User;
use App\Models\Budget\Account;
use App\Models\Comment;
use App\Models\Common\CatalogDetail;
use App\Models\Common\CatalogGeographicClassifier;
use App\Models\Indicators\Indicator\Indicator;
use App\Models\Indicators\Units\IndicatorUnits;
use App\Models\Strategy\PlanDetail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Plank\Mediable\Mediable;

class PoaActivity extends Model
{
    use Mediable;

    const STATUS_SCHEDULED = 'PROGRAMADA';
    const STATUS_IN_PROGRESS = 'EN PROGRESO';
    const STATUS_FINISHED = 'TERMINADA';

    const UPDATED = 'La actividad fue actualizada';
    const CREATED = 'La actividad fue creada';
    const DELETED = 'La actividad fue eliminada';

    const STATUS_RISK = 'Riesgo';
    const STATUS_ON_TIME = 'A tiempo';
    const STATUS_WARNING = 'Alerta';

    const CATEGORIES = [
        3 => [
            'text' => 'Alto',
            'icon' => 'fal fa-chevron-up color-danger-500',
        ],
        2 => [
            'text' => 'Medio',
            'icon' => 'fal fa-equals color-warning-500',
        ],
        1 => [
            'text' => 'Bajo',
            'icon' => 'fal fa-chevron-down color-blue',
        ]
    ];

    /**
     * Fillable fields.
     *
     * @var string[]
     */
    protected $fillable = [
        'poa_program_id',
        'indicator_unit_id',
        'indicator_id',
        'location_id',
        'plan_detail_id',
        'name',
        'code',
        'community',
        'description',
        'beneficiary_id',
        'user_id_in_charge',
        'status',
        'goal',
        'cost',
        'impact',
        'state',
        'complexity',
        'company_id',
        'progress',
        'project_activity_id',
    ];

    public static function boot()
    {
        parent::boot();
        static::deleted(function ($model) {
            $model->poaActivityIndicator->each->delete();
            event(new ActivityProcessed($model->indicator_id));
        });
        static::updated(function ($model) {
            event(new ActivityProcessed($model->indicator_id));
        });
    }

    /**
     * Indicator activity supports
     *
     * @return BelongsTo
     */
    public function indicator()
    {
        return $this->belongsTo(Indicator::class)->withoutGlobalScope(\App\Scopes\Company::class);
    }

    /**
     * Obtener la unidad de medida.
     *
     * @return BelongsTo
     */
    public function indicatorUnit(): BelongsTo
    {
        return $this->belongsTo(IndicatorUnits::class, 'indicator_unit_id')->orderBy('id', 'ASC');
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

    /**
     * Activity responsible
     *
     * @return BelongsTo
     */
    public function responsible()
    {
        return $this->belongsTo(User::class, 'user_id_in_charge');
    }

    /**
     * Activity Catalog Detail
     *
     * @returns BelongsTo
     */
    public function catalog()
    {
        return $this->belongsTo(CatalogDetail::class, 'beneficiary_id');
    }

    /**
     * POA Program activity plan detail
     *
     * @return BelongsTo
     */
    public function planDetail(): BelongsTo
    {
        return $this->belongsTo(PlanDetail::class, 'plan_detail_id');
    }

    /**
     * POA Program activity programl
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(PoaProgram::class, 'poa_program_id')->withoutGlobalScope(\App\Scopes\Company::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->withoutGlobalScope(\App\Scopes\Company::class);
    }

    public function accounts(): MorphMany
    {
        return $this->morphMany(Account::class, 'accountable');
    }


    public function activityRequests()
    {
        return $this->hasMany(PoaIndicatorGoalChangeRequest::class, 'poa_activity_id');
    }

    public function progress()
    {
        $goal = $this->poaActivityIndicator->where('period', '<=', date("m"))->sum('goal');
        if ($goal > 0) {
            return $this->progress / $goal * 100;

        } else {
            return 0;
        }
    }

    /**
     * Obtiene el objetivo total de todas las actividades
     *
     */
    public function getGoal()
    {
        return $this->withoutGlobalScope(\App\Scopes\Company::class)->sum('goal') > 0 ? $this->withoutGlobalScope(\App\Scopes\Company::class)->sum('goal') : 1;
    }

    /**
     * Obtiene los detalles de cada actividad
     *
     * @return HasMany
     */
    public function poaActivityIndicator()
    {
        return $this->hasMany(PoaActivityIndicator::class, 'poa_activity_id')->orderBy('id', 'ASC');
    }

    public function beneficiaries()
    {
        return $this->belongsToMany(CatalogDetail::class, 'poa_activity_beneficiaries', 'poa_activity_id', 'beneficiary_id');

    }

    public function getStatus(): array
    {
        $min = $this->indicator->threshold_properties[1]['min'];
        $max = $this->indicator->threshold_properties[1]['max'];
        if ($this->poaActivityIndicator->where('period', '<=', date("m"))->sum('goal') > 0) {
            $percentageAdvance = $this->progress / $this->poaActivityIndicator->where('period', '<=', date("m"))->sum('goal') * 100;
            if ($percentageAdvance < $min) {
                $this->state = PoaActivity::STATUS_RISK;
                $this->save();
                return [PoaActivity::STATUS_RISK, '#D52B1E', '#292b2c'];
            } else {
                if ($percentageAdvance >= $min && $percentageAdvance < $max) {
                    $this->state = PoaActivity::STATUS_WARNING;
                    $this->save();
                    return [PoaActivity::STATUS_WARNING, '#f9a100', '#292b2c'];
                } else {
                    $this->state = PoaActivity::STATUS_ON_TIME;
                    $this->save();
                    return [PoaActivity::STATUS_ON_TIME, '#47b73a', '#292b2c'];
                }
            }
        } else {
            return ['', '#FFFFFF', '#292b2c'];
        }
    }

    /**
     * Obtiene la ejecuccion general
     *
     */
    public function generalExecution($filter = null)
    {
        $total_actual = 0;
        $total_goal = $this->getGoal();
        $province = $filter['province'] ?? null;
        $canton = $filter['canton'] ?? null;
        $program = $filter['program'] ?? null;
        if (!is_null($province)) {
            $company = Company::when($province, function ($q, $province) {
                $q->where('id', $province);
            })->get()->first();
            $children = $company->children()->pluck('id', 'id');
        }
        $valueToProgressFilter = $this->withoutGlobalScope(\App\Scopes\Company::class)
            ->when($children ?? null, function ($q, $children) {
                $q->whereIn('company_id', $children);
            })
            ->when($program, function ($q, $program) {
                $q->whereIn('plan_detail_id', $program);
            })
            ->when($canton, function ($q, $canton) {
                $q->where('company_id', $canton);
            })
            ->get();

        $total_actual = $this->getProgressFiltered($valueToProgressFilter, $filter['time'] ?? null);

        return $total_actual / $total_goal * 100;
    }

    /**
     * Obtiene los goal de los cantones
     *
     */
    public function getGoalCanton($id)
    {
        return $this->where('company_id', $id)->get()->sum('goal');
    }

    /**
     * Obtiene el avance de los cantones
     *
     */
    public function getProgressCanton($id, $filter = null)
    {
        $program = $filter['program'] ?? null;
        $time = $filter['time'] ?? null;
        $poaByCompany = $this->where('company_id', $id)->when($program, function ($q, $program) {
            $q->whereIn('plan_detail_id', $program);
        })->get();
        if (is_null($time)) {//si es que no hay tiempo de filtro
            return $poaByCompany->sum('progress');
        } else {//si es que ha tiempo de filtro
            return $this->getProgressFiltered($poaByCompany, $filter['time']);
        }
    }

    public function getProgressFiltered($poaActivity, $filter = null)
    {
        $poaActivityIndicator = new PoaActivityIndicator;
        return $poaActivityIndicator->getProgressGoals($poaActivity->pluck('id', 'id'), 'progress', $filter);
    }

    /**
     * Obtiene el array de actividades de personas alcanzadas
     *
     */
    public function getActivitiesPeopleReached($id, $filter = null)
    {
        $canton = $filter['canton'] ?? null;
        $program = $filter['program'] ?? null;
        $province = $filter['province'] ?? null;
        $cantones = null;
        if (!is_null($province)) {
            $company = Company::find($province);
            $cantones = $company->children()->get()->pluck('id', 'id');
        }
        return $this->withoutGlobalScope(\App\Scopes\Company::class)
            ->when($cantones ?? null, function ($q, $cantones) {
                $q->whereIn('company_id', $cantones);
            })
            ->when($id, function ($q, $id) {
                $q->where('indicator_unit_id', $id);
            })
            ->when($program, function ($q, $program) {
                $q->whereIn('plan_detail_id', $program);
            })
            ->when($canton, function ($q, $canton) {
                $q->where('company_id', $canton);
            })
            ->pluck('id', 'id');
    }


    public function getNumeroPersonasAlcanzadasPograma($idsPrograms, $id, $filter = null)
    {
        $personasPorProgama = array();
        $j = 0;
        $groups = array();
        $groups2 = array();
        $progress = 0;
        $canton = $filter['canton'] ?? null;
        $program_ = $filter['program'] ?? null;
        $province = $filter['province'] ?? null;
        $children = null;
        foreach ($idsPrograms as $program) {
            if (!is_null($province)) {
                $company = Company::find($province);
                $children = $company->children()->pluck('id', 'id');
            }
            $poaActivity = $this->where('plan_detail_id', $program['id'])->where('indicator_unit_id', $id)
                ->withoutGlobalScope(\App\Scopes\Company::class)
                ->when($children, function ($q, $children) {
                    $q->whereIn('company_id', $children);
                })
                ->when($program_, function ($q, $program_) {
                    $q->whereIn('plan_detail_id', $program_);
                })
                ->when($canton, function ($q, $canton) {
                    $q->where('company_id', $canton);
                })->get();
            $goalValue = $poaActivity->sum('goal');
            if ($goalValue > 0) {
                if (!isset($filter['time'])) {
                    $progress = $poaActivity->sum('progress');
                } else {
                    foreach ($poaActivity as $p) {
                        $progress = $this->getProgressFiltered($poaActivity, $filter['time']);
                    }
                }
                $personasPorProgama[$j] = [
                    'name' => $program['name'],
                    'progress' => $progress,
                    'goal' => $goalValue,
                    'id' => $program['id']
                ];
                $progress = 0;
            }
            $j++;
        }

        $i = 0;
        foreach ($personasPorProgama as $item) {
            $key = $i;
            if (!array_key_exists($key, $groups)) {
                $groups[$key] = array(
                    'name' => $item['name'],
                    'progress' => $item['progress'],
                    'goal' => $item['goal'],
                    'color' => '',
                    'advance' => 0,
                    'id' => $item['id'],
                );
                $i++;
            } else {
                $groups2[$key]['progress'] = $groups[$key]['progress'] + $item['progress'];
                $groups[$key]['goal'] = $groups[$key]['goal'] + $item['goal'];
            }
        }
        foreach ($groups as $index => $group) {
            $key = $index;
            $groups[$key]['advance'] = $group['progress'];
            $group['progress'] = number_format($group['progress'] / $group['goal'] * 100, 1);
            $groups[$key]['color'] = "#0046AD";
            $groups[$key]['progress'] = $group['progress'];
            $groups[$key]['id'] = $group['id'];
        }
        return $groups;
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        switch ($eventName) {
            case 'updated':
                return PoaActivity::UPDATED;
                break;
            case 'created':
                return PoaActivity::CREATED;
                break;
            case 'deleted':
                return PoaActivity::DELETED;
                break;
        }
    }

    public function assignProgress()
    {
        $goal = $this->poaActivityIndicator->sum('goal');
        if ($goal) {
            if ($goal != $this->goal) {
                $this->goal = $goal;
            }
        } else {
            $this->goal = 0;
        }
        return $this->goal;
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

    public function getTotalBudget()
    {
        $accounts = $this->accounts;
        $total = 0;
        foreach ($accounts as $account) {
            $total += $account->balance->getAmount();
        }
        $total = money($total);

        return $total;
    }
}
