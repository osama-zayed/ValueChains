<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The table Domain with the model.
     *
     * @var string
     */
    protected $table = 'projects';

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
        'user_id',
    ];


    /**
     * Get the Domain with the Project.
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
    /**
     * Get the chain with the Project.
     */
    public function chain()
    {
        return $this->belongsTo(Chain::class);
    }
    /**
     * Get the user with the Project.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
