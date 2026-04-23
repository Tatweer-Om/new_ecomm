<?php

namespace Modules\Branch\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Branch\Database\Factories\BranchFactory;

class Branch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'branch_name',
        'contact',
        'email',
        'address',
        'added_by',
        'updated_by',
        'add_date',
        'update_date',
        'user_id'
    ];

    // protected static function newFactory(): BranchFactory
    // {
    //     // return BranchFactory::new();
    // }
}
