<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    /**
     * The table Domain with the model.
     *
     * @var string
     */
    protected $table = 'activities';

    /**
     * The attributes that are mass Domain.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'target_value',
        'target_indicator',
        'activity_weight',
        'domain_id',
        'chain_id',
        'user_id',
        'project_id',
        'hijri_created_at',
    ];

     /**
     * Get the project with the Activity.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
     /**
     * Get the Domain with the Activity.
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
    /**
     * Get the chain with the Activity.
     */
    public function chain()
    {
        return $this->belongsTo(Chain::class);
    }
    /**
     * Get the user with the Activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
