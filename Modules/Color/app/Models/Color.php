<?php

namespace Modules\Color\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Color\Database\Factories\ColorFactory;

class Color extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'color_name',
        'color_name_ar',
        'color_code',
        'added_by',
        'updated_by',
        'add_date',
        'update_date',
        'user_id'
    ];

    // protected static function newFactory(): ColorFactory
    // {
    //     // return ColorFactory::new();
    // }
}
