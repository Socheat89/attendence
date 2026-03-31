<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Avoid infinite loop by using hasUser instead of check
        if (Auth::hasUser()) {
            $user = Auth::user();
            if ($user->company_id) {
                $builder->where($model->getTable() . '.company_id', $user->company_id);
            } elseif (!$user->is_super_admin) {
                // SECURITY ENFORCEMENT: Block data access if user has no company and is not a Super Admin
                $builder->where($model->getTable() . '.company_id', -1);
            }
        }
    }
}
