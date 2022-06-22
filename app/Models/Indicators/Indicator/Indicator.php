<?php

namespace App\Models\Indicators\Indicator;

use App\Abstracts\Model;
use App\Models\Auth\User;
use App\Models\Indicators\GoalIndicator\GoalIndicators;
use App\Models\Indicators\Observations\IndicatorObservations;
use App\Models\Indicators\Sources\IndicatorSource;
use App\Models\Indicators\Threshold\Threshold;
use App\Models\Indicators\Units\IndicatorUnits;
use App\Models\Poa\PoaActivity;
use App\Models\Poa\PoaIndicatorConfig;
use App\Models\Projects\Project;
use App\Traits\LogToProject;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Indicator extends Model
{
    use HasFactory, LogsActivity, LogToProject;

    const FREQUENCY_WEEKLY = "52";
    const FREQUENCY_MONTHLY = "12";
    const FREQUENCY_QUARTERLY = "4";
    const FREQUENCY_FOUR_MONTHLY = "3";
    const FREQUENCY_BIANNUAL = "2";
    const FREQUENCY_ANNUAL = "1";

    const HIERACHY_IMPACT = 'impact';
    const HIERACHY_PROCESS = 'process';
    const HIERACHY_PRODUCT = 'product';

    const TYPE_MANUAL = "Manual";
    const TYPE_HOMOLOGATE = 'Homologated';
    const TYPE_GROUPED = 'Grouped';

    const TYPE_TOLERANCE = 'Tolerance';
    const TYPE_ASCENDING = 'Ascending';
    const TYPE_DESCENDING = 'Descending';

    const TYPE_DISCREET = 'discreet';
    const TYPE_ACCUMULATED = 'accumulated';

    const GOALS_CLOSED = 'closed';
    const GOALS_OPEN = 'open';

    const TYPE_AGGREGATION_SUM = "sum";
    const TYPE_AGGREGATION_WEIGHTED = "weighted";
    const TYPE_AGGREGATION_WIGHTED_SUM = "weighted_sum";

    const CATEGORY_TACTICAL = 'Táctico';
    const CATEGORY_OPERATIVE = 'Operativo';

    const MONTHLY='Mensualmente';
    const QUARTERYLY='Trimestral';
    const FOUR_MONTHLY='Cuatrimestral';
    const BIANNUAL='Semestralmente';
    const ANNUAL='Anualmente';


    protected $casts = [
        'documents' => 'array',
        'start_date' => 'date:Y-m',
        'end_date' => 'date:Y-m',
        'f_start_date' => 'date:Y-m',
        'f_end_date' => 'date:Y-m',
        'threshold_properties' => 'array',
    ];

    const FREQUENCIES = [
        52 => [
            1 => 'SEM 1',
            2 => 'SEM 2',
            3 => 'SEM 3',
            4 => 'SEM 4',
            5 => 'SEM 5',
            6 => 'SEM 6',
            7 => 'SEM 7',
            8 => 'SEM 8',
            9 => 'SEM 9',
            10 => 'SEM 10',
            11 => 'SEM 11',
            12 => 'SEM 12',
            13 => 'SEM 13',
            14 => 'SEM 14',
            15 => 'SEM 15',
            16 => 'SEM 16',
            17 => 'SEM 17',
            18 => 'SEM 18',
            19 => 'SEM 19',
            20 => 'SEM 20',
            21 => 'SEM 21',
            22 => 'SEM 22',
            23 => 'SEM 23',
            24 => 'SEM 24',
            25 => 'SEM 25',
            26 => 'SEM 26',
            27 => 'SEM 27',
            28 => 'SEM 28',
            29 => 'SEM 29',
            30 => 'SEM 30',
            31 => 'SEM 31',
            32 => 'SEM 32',
            33 => 'SEM 33',
            34 => 'SEM 34',
            35 => 'SEM 35',
            36 => 'SEM 36',
            37 => 'SEM 37',
            38 => 'SEM 38',
            39 => 'SEM 39',
            40 => 'SEM 40',
            41 => 'SEM 41',
            42 => 'SEM 42',
            43 => 'SEM 43',
            44 => 'SEM 44',
            45 => 'SEM 45',
            46 => 'SEM 46',
            47 => 'SEM 47',
            48 => 'SEM 48',
            49 => 'SEM 49',
            50 => 'SEM 50',
            51 => 'SEM 51',
            52 => 'SEM 52',
        ],
        12 => [
            1 => 'Ene',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Abr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Ago',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dic',
        ],
        4 => [
            1 => 'TRIM I',
            2 => 'TRIM II',
            3 => 'TRIM III',
            4 => 'TRIM IV',
        ],
        3 => [
            1 => 'CUAT I',
            2 => 'CUAT II',
            3 => 'CUAT III',
        ],
        2 => [
            1 => 'Sem I',
            2 => 'Sem II',
        ],
        1 => [
            1 => 'Año I',
            2 => 'Año II',
            3 => 'Año III',
            4 => 'Año IV',
            5 => 'Año IV',
            6 => 'Año VI',
        ]
    ];

    const TYPE_FREQUENCIES=
        [
            12=>self::MONTHLY,
            4=>self::QUARTERYLY,
            3=>self::FOUR_MONTHLY,
            2=>self::BIANNUAL,
            1=>self::ANNUAL,
        ];

    const PERIODS = [
        12 => 'MESES',
        4 => 'TRIMESTRES',
        2 => 'SEMESTRES',
        1 => 'AÑOS',
    ];

    /**
     * @var bool
     */

    protected $fillable = [
        'parent_id',
        'name',
        'code',
        'total_goal_value',
        'total_actual_value',
        'user_id',
        'start_date',
        'end_date',
        'f_start_date',
        'f_end_date',
        'base_line',
        'type',
        'indicator_units_id',
        'indicator_sources_id',
        'thresholds_id',
        'threshold_type',
        'baseline_year',
        'results',
        'indicatorable_id',
        'indicatorable_type',
        'frequency',
        'documents',
        'goals_closed',
        'company_id',
        'threshold_properties',
        'type_of_aggregation',
        'category',
        'national',
        'self_managed',
    ];

    public static function boot()
    {
        parent::boot();
        static::deleted(function ($model) {
            $model->indicatorGoals->each->delete();
        });

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id', 'asc');
        });
    }

    public function parent()
    {
        return $this->belongsTo($this, 'parent_id');
    }

    public function indicatorable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Obtener las metas del indicador
     *
     * @return HasMany
     */
    public function indicatorGoals()
    {
        return $this->hasMany(GoalIndicators::class, 'indicators_id')->orderBy('id', 'ASC');
    }

    public function getByTypePlanDetail()
    {
        return $this->where('indicatorable_type', 'App\\Models\\Strategy\\PlanDetail')->get();
    }

    public function getByTypePlanDetailFiltered($filters)
    {
        return $this->whereIn('indicatorable_id', $filters)->where('indicatorable_type', 'App\\Models\\Strategy\\PlanDetail')->get();
    }

    /**
     * Obtener las indicadores que son padres
     *
     * @return HasMany
     */
    public function indicatorParents(): HasMany
    {
        return $this->hasMany(IndicatorParentChild::class, 'parent_indicator')->orderBy('id', 'ASC');
    }

    public function isParent(): bool
    {
        return is_null(IndicatorParentChild::where('parent_indicator', $this->id)->first());
    }

    /**
     * Obtener las indicadores que son padres
     *
     * @return HasMany
     */
    public function indicatorChild(): HasMany
    {
        return $this->hasMany(IndicatorParentChild::class, 'child_indicator')->orderBy('id', 'ASC');
    }

    /**
     * Obtener las indicadores que son padres
     *
     * @return HasMany
     */
    public function indicatorParent(): HasMany
    {
        return $this->hasMany(IndicatorParentChild::class, 'parent_indicator')->orderBy('id', 'ASC');
    }

    /**
     * Obtener la unidad de medida.
     *
     * @return BelongsTo
     */
    public function indicatorUnit(): BelongsTo
    {
        return $this->belongsTo(IndicatorUnits::class, 'indicator_units_id')->orderBy('id', 'ASC');
    }

    /**
     * Obtener el usuario que creó el indicador
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtener la fuente del usuario
     *
     * @return BelongsTo
     */
    public function sourceIndicator(): BelongsTo
    {
        return $this->belongsTo(IndicatorSource::class, 'indicator_sources_id');
    }


    /**
     * Obtener el umbral
     *
     * @return BelongsTo
     */
    public function threshold(): BelongsTo
    {
        return $this->belongsTo(Threshold::class, 'thresholds_id');
    }

    /**
     * Obtener las indicadores padres
     *
     * @return HasMany
     */
    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class, 'parent_id');
    }

    /**
     * Obtener las actividades poa
     *
     * @return HasMany
     */
    public function poaActivities(): HasMany
    {
        return $this->hasMany(PoaActivity::class, 'indicator_id');
    }

    public function poaConfigs(): HasMany
    {
        return $this->hasMany(PoaIndicatorConfig::class, 'indicator_id');
    }

    /**
     * Obtener las observaciones del indciador
     *
     * @return HasMany
     */
    public function observations(): HasMany
    {
        return $this->hasMany(IndicatorObservations::class, 'indicators_id');
    }

    /**
     * Obtener si algun indicador tiene registrado algun avance
     *
     * @return bool
     */
    public function progress(): bool
    {
        $hasProgress = false;
        $this->indicatorGoals->each(function ($goal) use (&$hasProgress) {
            if ($goal->actual_value != null) {
                $hasProgress = true;
                return false;
            }
        });
        return $hasProgress;
    }

    /**
     * Obtener si algun indicador tiene registrado metas
     *
     * @return bool
     */
    public function hasRegisteredGoals(): bool
    {
        $hasProgress = false;
        $this->indicatorGoals->each(function ($goal) use (&$hasProgress) {
            if ($goal->goal_value != null) {
                $hasProgress = true;
                return false;
            }
        });
        return $hasProgress;
    }

    /**
     * Obtener la suma del avance de los indicadores
     *
     * @return int|mixed
     */
    public function progressIndicator()
    {
        $progress = 0;
        $this->indicatorGoals->each(function ($goal) use (&$progress) {
            if ($goal->actual_value != null) {
                $progress += $goal->actual_value;
            }
        });
        return $progress;
    }

    /**
     * Obtener la suma de los metas registradas
     *
     * @return
     */
    public function goalsRegister()
    {
        $progress = 0;
        $this->indicatorGoals->each(function ($goal) use (&$progress) {
            if ($goal->goal_value != null) {
                $progress += $goal->goal_value;
            }
            if ($goal->min != null) {
                $progress += $goal->min;
            }
        });
        return $progress;
    }

    /**
     * Obtener el mes del periodo en el que fue registrado los goals
     *
     * @return string
     */
    public function lastMonthRegister(): string
    {
        $date = '';
        $this->indicatorGoals->each(function ($goal) use (&$date) {
            if ($goal->actual_value != null) {
                $date = Carbon::parse($goal->updated_at);
                $date = $date->format('F');
            }
        });
        return $date;
    }


    /**
     * Obtiene las fechas de los goals del indicador
     *
     * @return
     */

    function numberOfPeriods($frequency = null, $modify = null)
    {
        $begin = new DateTime($this->f_start_date);
        $end = new DateTime($this->f_end_date);
        $end = $end->modify($modify);
        $interval = new DateInterval($frequency);
        $daterange = new DatePeriod($begin, $interval, $end);
        $result = array();
        $i = 0;
        foreach ($daterange as $date) {
            $result[$i] = $date->format("d-m-Y");
            $i++;
        }
        return $result;
    }

    /**
     * Crea el nombre de la frecuencia
     *
     * @return
     */
    public function getFrecuency()
    {
        if ($this->frequency == Indicator::FREQUENCY_WEEKLY) {
            return "Semanal";
        } else if ($this->frequency == Indicator::FREQUENCY_MONTHLY) {
            return "Mensual";
        } else if ($this->frequency == Indicator::FREQUENCY_QUARTERLY) {
            return "Trimestral";
        } else if ($this->frequency == Indicator::FREQUENCY_FOUR_MONTHLY) {
            return "Cuatrimestral";
        } else if ($this->frequency == Indicator::FREQUENCY_BIANNUAL) {
            return "Semestral";
        } else if ($this->frequency == Indicator::FREQUENCY_ANNUAL) {
            return "Anual";
        }
    }

    /**
     * Crea el nombre de la frecuencia
     *
     * @return
     */
    public function getTypeAgragation()
    {
        if ($this->type_of_aggregation == Indicator::TYPE_AGGREGATION_SUM) {
            return "Suma";
        } else if ($this->type_of_aggregation == Indicator::TYPE_AGGREGATION_WEIGHTED) {
            return "Ponderado";
        } else if ($this->type_of_aggregation == Indicator::TYPE_AGGREGATION_WIGHTED_SUM) {
            return "Suma Ponderada";
        }
    }

    /**
     *Obtiene el estado en prcentaje del indicador
     *
     * @return
     */
    public function getStateIndicator()
    {
        if (($this->threshold_type == Indicator::TYPE_ASCENDING) || ($this->threshold_type == Indicator::TYPE_DESCENDING)) {
            return $this->getPercentageAscending();
        } else {
            return $this->getStateTolerance();
        }
    }

    /**
     * Calcula el procentaje de avance para indicadores ascendentes y descendetes del acumulado
     *
     * @return
     */

    public function getPercentageAscending()
    {
        $goal_value = $this->goalsRegister();
        $actual_value = $this->progressIndicator();
        $result = 0;
        if ($actual_value != 0 && $goal_value != 0) {
            $result = $actual_value / $goal_value * 100;

        }
        return $this->getMessageStateAscending($result);
    }

    /**
     *Verifica el porcentaje del resultado vs el umbral del indicador para saber el estado del indicador
     *
     * @return
     */
    public function getMessageStateAscending($result)
    {
        $properties = $this->threshold_properties;
        $min = $properties[1]['min'];
        $max = $properties[1]['max'];
        $rss = array();
        if ($result < $min) {
            return $rss[0] = ['badge-danger', number_format($result, 2) . '%'];
        } else if ($result > $max) {
            return $rss[0] = ['badge-success', number_format($result, 2) . '%'];
        } else {
            return $rss[0] = ['badge-warning', number_format($result, 2) . '%'];
        }
    }

    /**
     *Verifica el porcentaje del resultado vs el umbral del indicador para saber el estado del indicador
     *
     * @return
     */
    public function getMessageStateTolerance($result)
    {
        $properties = $this->threshold_properties;
        $min = $properties[1]['min'];
        $max = $properties[1]['max'];
        $rss = array();

        if ($result >= $min && $result <= $max) {
            return $rss[0] = ['badge-warning', number_format($result, 2) . '%'];
        } else if ($result > $max) {
            return $rss[0] = ['badge-danger', number_format($result, 2) . '%'];
        } else {
            return $rss[0] = ['badge-success', '0%'];
        }

    }

    /**
     * Calcula el procentaje de avance para indicadores ascendentes y descendetes del acumulado
     *
     * @return
     */
    public function getStateTolerance()
    {
        //verificar cual es el ultimo registro que se ingreso
        $goal = $this->lastGoalRegistered();
        $min = $goal['min'];
        $max = $goal['max'];
        $actual_value = $goal['actual_value'];
        $abs = 0;
        $percentage = 0;
        if ($actual_value < $min) {
            if ($min > 0) {
                $percentage = ($actual_value / $max * 100);
                $abs = abs(100 - $percentage);
            }
        } else if ($actual_value > $max) {
            if ($max > 0) {
                $percentage = ($actual_value / $max * 100);
                $abs = abs(100 - $percentage);
            }
        }
        return $this->getMessageStateTolerance($abs);
    }

    /**
     * Obtener el mes del periodo en el que fue registrado los goals
     *
     * @return
     */
    public function lastGoalRegistered(): array
    {
        $id = 0;
        $actual_value = 0;
        $min = 0;
        $max = 0;
        $this->indicatorGoals->each(function ($goal) use (&$id, &$actual_value, &$min, &$max) {
            if ($goal->actual_value != null) {
                $id = $goal->id;
                $actual_value = $goal->actual_value;
                $min = $goal->min;
                $max = $goal->max;
            }
        });
        return [
            'id' => $id,
            'actual_value' => $actual_value,
            'min' => $min,
            'max' => $max
        ];
    }

    public function calcStartEndDateF($startDate_, $endDate_, $frequency)
    {
        $ts1 = strtotime($startDate_);
        $ts2 = strtotime($endDate_);
        $month1 = intval(date('m', $ts1));
        $month2 = intval(date('m', $ts2));
        $startDate = Carbon::parse($startDate_)->startOfMonth()->toDateString();
        $lastDayofMonth = Carbon::parse($endDate_)->endOfMonth()->toDateString();
        $endDate = $lastDayofMonth;
        //Si es semesral
        if ($frequency == 2) {
            if ($month1 <= 6) {
                $mount = $month1 - 1;
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $mount . " month"));
            } else {
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $month1 . " month"));
                $startDate = date("Y-m-d", strtotime($startDate . "+ " . 7 . " month"));
            }
            if ($month2 <= 6) {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 6 . " month"));
            } else {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 12 . " month"));
            }
        }
        if ($frequency == 1) {//si es anual
            $mount = $month1 - 1;
            $startDate = date("Y-m-d", strtotime($startDate . "- " . $mount . " month"));
            $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
            $endDate = date("Y-m-d", strtotime($endDate . "+ " . 12 . " month"));
        }
        if ($frequency == 4) {//Si es trimtestral
            if ($month1 <= 3) {
                $mount = $month1 - 1;
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $mount . " month"));
            } else if ($month1 > 3 && $month1 <= 6) {
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $month1 . " month"));
                $startDate = date("Y-m-d", strtotime($startDate . "+ " . 4 . " month"));

            } else if ($month1 > 6 && $month1 <= 9) {
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $month1 . " month"));
                $startDate = date("Y-m-d", strtotime($startDate . "+ " . 7 . " month"));
            } else if ($month1 > 9 && $month1 <= 12) {
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $month1 . " month"));
                $startDate = date("Y-m-d", strtotime($startDate . "+ " . 10 . " month"));
            }
            if ($month2 <= 3) {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 3 . " month"));
            } else if ($month2 > 3 && $month2 <= 6) {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 6 . " month"));
            } else if ($month2 > 6 && $month2 <= 9) {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 9 . " month"));
            } else if ($month2 > 9 && $month2 <= 12) {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 12 . " month"));
            }
        }
        if ($frequency == 3) {//Si es cuatrimetstal
            if ($month1 <= 4) {
                $mount = $month1 - 1;
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $mount . " month"));
            } else if ($month1 > 4 && $month1 <= 8) {
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $month1 . " month"));
                $startDate = date("Y-m-d", strtotime($startDate . "+ " . 5 . " month"));

            } else if ($month1 > 9) {
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $month1 . " month"));
                $startDate = date("Y-m-d", strtotime($startDate . "+ " . 9 . " month"));
            }
            if ($month2 <= 4) {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 4 . " month"));
            } else if ($month2 > 4 && $month2 <= 9) {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 8 . " month"));
            } else if ($month2 > 9) {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 12 . " month"));
            }
        }
        return [
            'f_start_date' => $startDate,
            'f_end_date' => $endDate
        ];
    }

    public function calcNumberOfPeriods($indicator, $value, $starDate, $endDate)
    {
        if ($value == 1) {//si es anual
            return $indicator->numberOfPeriodsF($starDate, $endDate, 'P1Y', '+0 year');
        } else if ($value == 2) {//si es semestral
            return $indicator->numberOfPeriodsF($starDate, $endDate, 'P6M', '+0 month');
        } else if ($value == 4) {//si es trimestral
            return $indicator->numberOfPeriodsF($starDate, $endDate, 'P3M', '+0 month');
        } else if ($value == 12) {
            return $indicator->numberOfPeriodsF($starDate, $endDate, 'P1M', '+0 month');
        } else if ($value == 3) {//si es trimestral
            return $indicator->numberOfPeriodsF($starDate, $endDate, 'P4M', '+0 month');
        }
    }

    /**
     * @throws Exception
     */
    function numberOfPeriodsF($startDate, $endDate, $frequency = null, $modify = null)
    {
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end = $end->modify($modify);
        $interval = new DateInterval($frequency);
        $daterange = new DatePeriod($begin, $interval, $end);
        $result = array();
        $i = 0;
        foreach ($daterange as $date) {
            $result[$i] = $date->format("d-m-Y");
            $i++;
        }
        return $result;
    }

    public function calcNumberOfPeriodStartC($startDate, $endDate, $frequency)
    {
        $ts1 = strtotime($startDate);
        $month1 = intval(date('m', $ts1));

        if ($frequency == 2) {
            if ($month1 <= 6) {
                return 1;
            } else {
                return 2;
            }
        } else if ($frequency == 4) {
            if ($month1 <= 4) {
                return 1;
            } else if ($month1 >= 4 && $month1 < 7) {
                return 4;
            } else if ($month1 >= 7 && $month1 < 10) {
                return 7;
            } else {
                return 10;
            }
        } else if ($frequency == 12) {
            return $month1;
        } else if ($frequency == 3) {
            if ($month1 <= 4) {
                return 1;
            } else if ($month1 >= 5 && $month1 < 8) {
                return 5;
            } else {
                return 9;
            }
        }
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        switch ($eventName) {
            case 'updated':
                return Project::UPDATED;
                break;
            case 'created':
                return Project::CREATED;
                break;
            case 'deleted':
                return Project::DELETED;
                break;
        }
    }
}
