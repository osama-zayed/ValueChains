<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Alkoumi\LaravelHijriDate\Hijri;
use Carbon\Carbon;

class Procedure extends Model
{
    use HasFactory;

    /**
     * The table Domain with the model.
     *
     * @var string
     */
    protected $table = 'procedures';

    /**
     * The attributes that are mass Domain.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'hijri_created_at',
        'domain_id',
        'chain_id',
        'ring_id',
        'project_id',
        'activity_id',
        'user_id',
        'procedure_weight',
        'procedure_duration_days',
        'procedure_start_date',
        'procedure_end_date',
        'cost',
        'funding_source',
        'status',
        'attached_file',
        'supervisory_authority',
        'executing_agency',
        'verification_methods',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $hijriToday = Hijri::Date('o', Carbon::now());
            $model->hijri_created_at = $hijriToday;
        });
    }
    /**
     * Get the activity with the Procedure.
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }
    /**
     * Get the project with the Procedure.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    /**
     * Get the Domain with the Procedure.
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
    /**
     * Get the ring with the Procedure.
     */
    public function ring()
    {
        return $this->belongsTo(Ring::class);
    }
    /**
     * Get the chain with the Procedure.
     */
    public function chain()
    {
        return $this->belongsTo(Chain::class);
    }
    /**
     * Get the user with the Procedure.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
