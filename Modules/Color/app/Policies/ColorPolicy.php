<?php

namespace Modules\Color\Policies;

use Modules\Color\Models\Color;
use Modules\User\Models\User;

class ColorPolicy
{
    public function viewAny(?User $user): bool
    {
        return $this->tenantCanManageColors($user);
    }

    public function view(?User $user, Color $color): bool
    {
        return $this->tenantCanManageColors($user);
    }

    public function create(?User $user): bool
    {
        return $this->tenantCanManageColors($user);
    }

    public function update(?User $user, Color $color): bool
    {
        return $this->tenantCanManageColors($user);
    }

    public function delete(?User $user, Color $color): bool
    {
        return $this->tenantCanManageColors($user);
    }

    public function restore(?User $user, mixed $color = null): bool
    {
        return $this->tenantCanManageColors($user);
    }

    protected function tenantCanManageColors(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return isset($user->dress) && (int) $user->dress === 1;
    }
}
