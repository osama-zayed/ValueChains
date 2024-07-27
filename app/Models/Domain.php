<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Butschster\HijriDate\Hijri;
use Alkoumi\LaravelHijriDate\Hijri;
use Carbon\Carbon;

class Domain extends Model
{
    use HasFactory;

    /**
     * The table Domain with the model.
     *
     * @var string
     */
    protected $table = 'domains';

    /**
     * The attributes that are mass Domain.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * Get the Chains with the Domain.
     */
    public function chains()
    {
        return $this->belongsToMany(Chain::class, 'chain_domain')
            ->withTimestamps();
    }
}
