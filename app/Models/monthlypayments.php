<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class monthlypayments extends Model
{
    use HasFactory;
    protected $table = 'monthlypayments';
    protected $fillable = [
        'number',
        'user_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
