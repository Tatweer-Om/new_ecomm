<?php

namespace Modules\Setting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Setting\Database\Factories\SmsFactory;

class Sms extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'sms_status',
        'sms',
        'added_by',
        'add_date',
        'updated_by',
        'update_date',
        'user_id',
        'created_at',
        'updated_at',
    ];

    // protected static function newFactory(): SmsFactory
    // {
    //     // return SmsFactory::new();
    // }
}
