<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class College extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable =
        [
            'government_id',
            'name',
            'info',
        ];

    public function government()
    {
        return $this->belongsTo(Government::class, 'government_id')
            ->withTrashed()
            ;
    }
}
