<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class loans extends Model
{
    use HasFactory;
    protected $fillable = [
        'number',
        'user_id',
        'sponsor_1',
        'sponsor_2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function boot() {
        parent::boot();
    
        static::updated(function ($loan) {
            if ($loan->number == 0) {
                $loan->delete();
            }
        });
    }    
}
