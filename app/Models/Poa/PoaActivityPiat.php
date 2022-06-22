<?php

namespace App\Models\Poa;

use App\States\Poa\Approved;
use App\States\Poa\ApprovedPiat;
use App\States\Poa\Pending;
use App\States\Poa\PiatState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\ModelStates\HasStates;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoaActivityPiat extends Model
{
    use HasFactory, HasStates, SoftDeletes;

    const STATUS_PENDING='Pendiente';
    const STATUS_APPROVED='Aprobado';

    const STATUSES = [
        Pending::class,
        ApprovedPiat::class
    ];

    protected $table = 'poa_activity_piat';

    protected $casts = [
        'status' => PiatState::class,
    ];

    /**
     * Fillable fields.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'place',
        'date',
        'initial_time',
        'end_time',
        'province',
        'canton',
        'parish',
        'id_poa_activities',
        'number_male_respo',
        'number_female_respo',
        'males_beneficiaries',
        'females_beneficiaries',
        'males_volunteers',
        'females_volunteers',
        'goals',
        'status',
        'created_by',
        'approved_by',
        'is_terminated'
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'place', 'date', 'initial_time', 'province', 'canton', 'parish', 
    'status', 'responsable_to_create', 'responsable_to_approve'];

    /**
     * Scope to only include active currencies.
     *
     * @param Builder $query
     *
     * @return Builder
     */



    public function scopeEnabled(Builder $query): Builder
    {
        return $query->whereIn('status', PoaActivityPiat::STATUSES);
    }

    /**
     * PoaActivityPiat poaActivities
     *
     * @return BelongsTo
     */
    public function poaActivities(): BelongsTo
    {
        return $this->belongsTo(PoaActivity::class, 'id_poa_activities');
    }

    /**
     * PoaActivityPiat responsableToCreate
     *
     * @return BelongsTo
     */
    public function responsableToCreate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * PoaActivityPiat responsableToApprove
     *
     * @return BelongsTo
     */
    public function responsableToApprove(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * PoaActivityPiat plans
     *
     * @return HasMany
     */
    public function poaActivityPiatPlan(): HasMany
    {
        return $this->hasMany(PoaActivityPiatPlan::class, 'id_poa_activity_piat');
    }

    /**
     * PoaActivityPiat requirements
     *
     * @return HasMany
     */
    public function poaActivityPiatRequirements(): HasMany
    {
        return $this->hasMany(PoaActivityPiatRequirements::class, 'id_poa_activity_piat');
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
