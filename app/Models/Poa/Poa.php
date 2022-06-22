<?php

namespace App\Models\Poa;

use App\Abstracts\Model;
use App\Models\Admin\Department;
use App\Models\Auth\User;
use App\States\Poa\Approved;
use App\States\Poa\Execution;
use App\States\Poa\InProgress;
use App\States\Poa\Planning;
use App\States\Poa\PoaPhase;
use App\States\Poa\PoaState;
use App\States\Poa\Reviewed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Plank\Mediable\Mediable;
use Spatie\ModelStates\HasStates;

class Poa extends Model
{
    use Mediable, HasStates, HasFactory;

    const PHASE_PLANNING = 'PLANIFICACIÓN';
    const PHASE_EXECUTION = 'EJECUCIÓN';
    const STATUS_IN_PROGRESS = 'En proceso';
    const STATUS_APPROVED = 'En proceso';
    const STATUS_IN_PROCESS = 'EN PROCESO';
    const STATUS_REVIEWED = 'REVISADO';
    const STATUS_ACCEPTED = 'APROBADO';


    const STATUSES = [
        InProgress::class,
        Reviewed::class,
        Approved::class
    ];

    const STATUES_LABELS = [
      self::STATUS_IN_PROCESS,
      self::STATUS_REVIEWED,
      self::STATUS_ACCEPTED,
    ];

    const PHASES = [
        Planning::class,
        Execution::class
    ];

    protected $table = 'poa_poas';

    protected $casts = [
        'status' => PoaState::class,
        'phase' => PoaPhase::class
    ];


    /**
     * Fillable fields.
     *
     * @var string[]
     */
    protected $fillable = [
        'year',
        'name',
        'user_id_in_charge',
        'company_id',
        'status',
        'phase',
        'reviewed',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['year', 'name', 'responsible', 'status', 'reviewed', 'progress'];

    /**
     * Scope to only include active currencies.
     *
     * @param Builder $query
     *
     * @return Builder
     */


    public function scopeEnabled(Builder $query): Builder
    {
        return $query->whereIn('status', Poa::STATUES_LABELS);
    }

    /**
     * Poa Responsible
     *
     * @return BelongsTo
     */
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_in_charge');
    }

    /**
     * POA programs
     *
     * @return HasMany
     */
    public function programs(): HasMany
    {
        return $this->hasMany(PoaProgram::class)->orderBy('id');
    }

    public function configs(): HasMany
    {
        return $this->hasMany(PoaIndicatorConfig::class)->orderBy('id');
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'poa_departments', 'poa_id', 'department_id');
    }

    public function poaIndicatorConfigs()
    {
        return $this->hasMany(PoaIndicatorConfig::class, 'poa_id');
    }

    public function calcProgress()
    {
        $progress = 0;
        foreach ($this->programs as $program) {
            $progress += $program->progress * $program->weight;
        }
        $this->progress = $progress;
        $this->save();
        return $progress;
    }

    public function statusChanges(): Collection
    {
        return $this->activities()->where([
            ['description', '=', 'updated'],
            ['properties->attributes->status', '!=', ''],
        ])->get();
    }

    public static function statusColor(string $status)
    {
        foreach (self::STATUSES as $st) {
            if ($st::$name == $status) {
                return $st::color();
            }
        }
        return '';
    }
}
