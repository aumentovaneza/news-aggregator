<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFeedSetting extends Model
{
    protected $fillable = [
        'user_id',
        'sort_date_by',
        'show_source_from',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
