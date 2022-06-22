<?php

namespace App\Models\Budget\Catalogs;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class FinancingSourceClassifier extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected bool $tenantable = false;

    protected $table='bdg_financing_source_classifiers';

    protected $casts = ['code' => 'string'];

    protected $fillable = [
        'code',
        'description'
    ];

    /**
     * Inicializar modelo
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderCode', function (Builder $builder) {
            $builder->orderBy('code', 'asc');
        });
    }

}
