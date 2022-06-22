<?php

namespace App\Models\Poa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoaMatrixReportAgreementCommitment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'poa_matrix_report_agreement_commitment';
   
    /**
     * Fillable fields.
     *
     * @var string[]
     */
    protected $fillable = [
        'id_poa_activity_piat_report',
        'description',
        'responsable',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['description', 'responsable'];

    /**
     * PoaActivityPiatPlan poaActivities
     *
     * @return BelongsTo
     */
    public function poaActivityPiatReport(): BelongsTo
    {
        return $this->belongsTo(PoaActivityPiatReport::class, 'id_poa_activity_piat_report');
    }

    /**
     * PoaActivityPiatPlan responsable
     *
     * @return BelongsTo
     */
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable');
    }
}
