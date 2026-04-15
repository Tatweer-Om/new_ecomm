<?php

namespace Modules\Size\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Size\Database\Factories\SizeFactory;

class Size extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'size_name',
        'size_name_ar',
        'size_code',
        'description',
        'added_by',
        'updated_by',
        'add_date',
        'update_date',
        'user_id'
    ];

    // protected static function newFactory(): SizeFactory
    // {
    //     // return SizeFactory::new();
    // }
}
