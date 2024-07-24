<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    /**
     * Get the Domain with the driver.
     */
    public function association()
    {
        return $this->belongsTo(User::class);
    }
}
