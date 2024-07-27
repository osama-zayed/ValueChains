<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Butschster\HijriDate\Hijri;
use Alkoumi\LaravelHijriDate\Hijri;
use Carbon\Carbon;
class Ring extends Model
{
    use HasFactory;

    /**
     * The table Ring with the model.
     *
     * @var string
     */
    protected $table = 'rings';

    /**
     * The attributes that are mass Ring.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
    ];
        /**
     * Get the Chains with the Ring.
     */
    public function chains()
    {
        return $this->belongsToMany(Chain::class, 'chain_ring')
            ->withTimestamps();
    }
}
