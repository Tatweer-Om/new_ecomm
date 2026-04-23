<?php

namespace Modules\Color\Services;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Modules\Color\Models\Color;
use Modules\Color\Models\History;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ColorService
{
    protected int $cacheTtlSeconds = 600;

    public function getPaginated(int $perPage = 10, int $page = 1): LengthAwarePaginator
    {
        $key = sprintf(
            'color:list:%s:%d:%d:v%d',
            $this->tenantCacheScope(),
            $perPage,
            $page,
            $this->currentCacheVersion()
        );

        return $this->rememberFromRedis(
            $key,
            $this->cacheTtlSeconds,
            fn (): LengthAwarePaginator => Color::query()->latest()->paginate($perPage, ['*'], 'page', $page)
        );
    }

    public function findById(int|string $id): Color
    {
        $key = sprintf(
            'color:item:%s:%s:v%d',
            $this->tenantCacheScope(),
            (string) $id,
            $this->currentCacheVersion()
        );

        return $this->rememberFromRedis(
            $key,
            $this->cacheTtlSeconds,
            fn (): Color => Color::query()->findOrFail($id)
        );
    }

    public function create(array $data): Color
    {
        return DB::transaction(function () use ($data): Color {
            if ($this->activeNameExists($data['color_name'])) {
                throw new HttpException(422, 'Color name already exists.');
            }

            $color = Color::create([
                'color_name'    => $data['color_name'],
                'color_name_ar' => $data['color_name_ar'],
                'color_code'    => $data['color_code'] ?? null,
                'created_by'    => $data['created_by'] ?? null,
                'delete_status' => false,
            ]);

            $this->bumpCacheVersion();

            return $color;
        });
    }

    public function update(Color $color, array $data): Color
    {
        return DB::transaction(function () use ($color, $data): Color {
            if ($this->activeNameExists($data['color_name'], $color->id)) {
                throw new HttpException(422, 'Color name already exists.');
            }

            $old = $this->rowSnapshot($color->fresh());

            $color->update([
                'color_name'    => $data['color_name'],
                'color_name_ar' => $data['color_name_ar'],
                'color_code'    => $data['color_code'] ?? null,
                'updated_by'    => $data['updated_by'] ?? null,
            ]);

            $fresh = $color->fresh();
            $userId = array_key_exists('updated_by', $data) ? $data['updated_by'] : null;
            $this->writeHistory(
                History::ACTION_UPDATE,
                $old,
                $this->rowSnapshot($fresh),
                $userId !== null ? (int) $userId : null,
                (int) $fresh->getKey()
            );

            $this->bumpCacheVersion();

            return $fresh;
        });
    }

    public function softDelete(Color $color, ?int $userId): Color
    {
        return DB::transaction(function () use ($color, $userId): Color {
            $old = $this->rowSnapshot($color->fresh());

            $color->update([
                'delete_status' => true,
                'updated_by'    => $userId,
            ]);

            $fresh = Color::withoutGlobalScope('active')->whereKey($color->getKey())->first();
            $this->writeHistory(
                History::ACTION_DELETE,
                $old,
                $this->rowSnapshot($fresh),
                $userId,
                (int) $color->getKey()
            );

            $this->bumpCacheVersion();

            return Color::withoutGlobalScope('active')->whereKey($color->getKey())->firstOrFail();
        });
    }

    public function restore(int|string $id, ?int $userId): Color
    {
        return DB::transaction(function () use ($id, $userId): Color {
            $color = Color::queryForRestore($id);

            if (! $color) {
                throw new HttpException(404, 'Color not found.');
            }

            if (! $color->delete_status) {
                throw new HttpException(422, 'Color is not deleted.');
            }

            if ($this->activeNameExists($color->color_name, $color->id)) {
                throw new HttpException(422, 'Cannot restore: another active color uses this name.');
            }

            $color->update([
                'delete_status' => false,
                'updated_by'    => $userId,
            ]);

            $this->bumpCacheVersion();

            return $color->fresh();
        });
    }

    protected function activeNameExists(string $name, null|int|string $exceptId = null): bool
    {
        $query = Color::query()->where('color_name', $name);

        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->exists();
    }

    protected function rowSnapshot(?Color $color): array
    {
        if (! $color) {
            return [];
        }

        return $color->only([
            'id',
            'tenant_id',
            'color_name',
            'color_name_ar',
            'color_code',
            'delete_status',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
        ]);
    }

    protected function writeHistory(
        string $action,
        array $oldData,
        array $newData,
        ?int $userId,
        int $recordId
    ): void {
        History::query()->create([
            'table_name' => History::TABLE_COLORS,
            'record_id'  => $recordId,
            'action'     => $action,
            'user_id'    => $userId,
            'old_data'   => $oldData,
            'new_data'   => $newData,
            'created_at' => now(),
        ]);
    }

    protected function tenantCacheScope(): string
    {
        if (function_exists('tenant') && tenant()) {
            return (string) tenant('id');
        }

        return 'central';
    }

    protected function cacheVersionKey(): string
    {
        return sprintf('color:version:%s', $this->tenantCacheScope());
    }

    protected function currentCacheVersion(): int
    {
        $version = $this->rememberFromRedis(
            $this->cacheVersionKey(),
            86400,
            fn (): int => 1
        );

        return (int) $version;
    }

    protected function bumpCacheVersion(): void
    {
        $key = $this->cacheVersionKey();

        try {
            Cache::store('redis')->add($key, 1, 86400);
            Cache::store('redis')->increment($key);
        } catch (Throwable) {
            // Graceful fallback: continue request even if Redis is unavailable.
        }
    }

    protected function rememberFromRedis(string $key, int $seconds, Closure $callback): mixed
    {
        try {
            return Cache::store('redis')->remember($key, $seconds, $callback);
        } catch (Throwable) {
            return $callback();
        }
    }
}
