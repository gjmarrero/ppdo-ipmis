<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function projects() : HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function project_documents() : HasMany
    {
        return $this->hasMany(ProjectSupportingDocument::class);
    }

    public function validations() : HasMany
    {
        return $this->hasMany(Validation::class);
    }

    public function validation_supporting_documents() : HasMany
    {
        return $this->hasMany(ValidationSupportingDocument::class);
    }

    public function validation_responsible_persons() : HasMany
    {
        return $this->hasMany(ValidationResponsiblePerson::class);
    }

    public function funded_projects() : HasMany
    {
        return $this->hasMany(FundedProject::class);
    }

    public function environmental_clearances() : HasMany
    {
        return $this->hasMany(EnvironmentalClearance::class);
    }

    public function employee() : BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function preengineering_statuses () : HasMany
    {
        return $this->hasMany(PreengineeringStatus::class);
    }

    public function implementations () : HasMany
    {
        return $this->hasMany(Implementation::class);
    }
}
