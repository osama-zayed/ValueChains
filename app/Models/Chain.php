<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Alkoumi\LaravelHijriDate\Hijri;
use Carbon\Carbon;

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
        'user_id',
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
     * Get the Domain with the Chain.
     */
    public function domains()
    {
        return $this->belongsToMany(Domain::class, 'chain_domain')
            ->withTimestamps();
    }
    /**
     * Get the Ring with the Chain.
     */
    public function rings()
    {
        return $this->belongsToMany(Ring::class, 'chain_ring')
            ->withTimestamps();
    }
    /**
     * Get the user with the Chain.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
