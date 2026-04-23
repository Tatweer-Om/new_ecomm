<?php

namespace Modules\Color\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public const TABLE_COLORS = 'colors';

    public const ACTION_UPDATE = 'update';

    public const ACTION_DELETE = 'delete';

    public $timestamps = false;

    protected $table = 'histories';

    protected $fillable = [
        'table_name',
        'record_id',
        'action',
        'user_id',
        'old_data',
        'new_data',
        'created_at',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'created_at' => 'datetime',
    ];
}
