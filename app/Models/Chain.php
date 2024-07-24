<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chain extends Model
{
    use HasFactory;

    /**
     * The table Domain with the model.
     *
     * @var string
     */
    protected $table = 'chains';

    /**
     * The attributes that are mass Domain.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'Goals',
        'name',
        'hijri_created_at',
        'domain_id',
        'user_id',
    ];
     
    /**
     * Get the Domain with the Chain.
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
     /**
     * Get the user with the Chain.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
