<?php

namespace Modules\Color\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

class Color extends Model
{
    protected $table = 'colors';

    protected $fillable = [
        'tenant_id',
        'color_name',
        'color_name_ar',
        'color_code',
        'delete_status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'delete_status' => 'boolean',
            'tenant_id'    => 'string',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder): void {
            if (function_exists('tenant') && tenant()) {
                $builder->where($builder->qualifyColumn('tenant_id'), tenant('id'));
            }
        });

        static::addGlobalScope('active', function (Builder $builder): void {
            $builder->where($builder->qualifyColumn('delete_status'), false);
        });

        static::creating(function (Color $model): void {
            if (function_exists('tenant') && tenant() && empty($model->tenant_id)) {
                $model->tenant_id = tenant('id');
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeWithDeleted(Builder $builder): Builder
    {
        return $builder->withoutGlobalScope('active');
    }

    public static function queryForRestore(int|string $id): ?self
    {
        return static::withoutGlobalScope('active')
            ->whereKey($id)
            ->first();
    }
}
