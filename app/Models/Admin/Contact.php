<?php

namespace App\Models\Admin;

use App\Abstracts\Model;
use App\Models\Projects\Stakeholders\ProjectCommunicationMatrix;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Plank\Mediable\Mediable;

class Contact extends Model
{
    use HasFactory, Mediable;

    protected bool $tenantable = true;

    protected $table = 'contacts';

    protected $fillable = [
        'names',
        'surnames',
        'job_title',
        'email',
        'business_phone',
        'personal_phone',
        'gender',
        'date_birth',
        'photo',
        'personal_notes',
        'enabled',
        'company_id',
        'employer_cost',
        'competencies',
        'working_skills',
        'work_experience',
        'contract_type',
        'contract_start',
        'contract_end',
    ];

    protected $casts = [
        'competencies' => 'array',
        'working_skills' => 'array',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['names', 'surnames', 'email', 'created_at', 'enabled'];

    /**
     * Always return a valid picture when we retrieve it
     *
     * @param $value
     *
     * @return mixed
     */
    public function getPictureAttribute($value)
    {

        if (!empty($value) && !$this->hasMedia('picture')) {
            return $value;
        } elseif (!$this->hasMedia('picture')) {
            return false;
        }

        return $this->getMedia('picture')->last();
    }

    /**
     * Always return a full_name when we retrieve it
     *
     * @param $value
     *
     * @return string
     */
    public function getFullNameAttribute($value): string
    {
        return "{$this->names} {$this->surnames}";
    }

    public function getFullName()
    {
        return $this->names . " " . $this->surnames;
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'contact_departments', 'user_id', 'department_id');
    }

    public function communicationsMatrix()
    {
        return $this->hasMany(ProjectCommunicationMatrix::class, 'user_id');
    }

}
