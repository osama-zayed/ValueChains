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
        'hijri_created_at',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $hijriToday = Hijri::Date('o', Carbon::now());
            $model->hijri_created_at = $hijriToday;
        });
    }
}
