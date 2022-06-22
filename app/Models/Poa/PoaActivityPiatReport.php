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

class PoaActivityPiatReport extends Model
{
    use HasFactory, HasStates, SoftDeletes;

    protected $table = 'poa_activity_piat_report';

    /**
     * Fillable fields.
     *
     * @var string[]
     */
    protected $fillable = [
        'id_poa_activity_piat',
        'accomplished',
        'description',
        'positive_evaluation',
        'evaluation_for_improvement',
        'date',
        'initial_time',
        'end_time',
        'created_by',
        'approved_by',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['accomplished', 'positive_evaluation', 'evaluation_for_improvement', 'created_by', 'approved_by'];

    /**
     * PoaActivityPiat poaActivities
     *
     * @return BelongsTo
     */
    public function poaActivityPiat(): BelongsTo
    {
        return $this->belongsTo(PoaActivityPiat::class, 'id_poa_activity_piat');
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
     * PoaActivityPiat Report
     *
     * @return HasMany
     */
    public function poaMatrixReportAgreementCommitment(): HasMany
    {
        return $this->hasMany(PoaMatrixReportAgreementCommitment::class, 'id_poa_activity_piat_report');
    }

    /**
     * Many to Many relationship with Beneficiaries
     *
     * @return BelongsToMany
     */
    public function poaMatrixReportBeneficiaries(): BelongsToMany
    {
        return $this->belongsToMany(PoaMatrixReportBeneficiaries::class, 'matrix_beneficiary_matrix_report', 'matrix_report_id', 'matrix_beneficiary_id')
            ->withPivot('observations', 'belong_to_board', 'participation_initial_time', 'participation_end_time')
            ->withTimestamps();
    }
}
