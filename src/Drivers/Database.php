<?php

namespace OwenIt\Auditing\Drivers;

use Illuminate\Support\Facades\Config;
use OwenIt\Auditing\Contracts\Audit;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\AuditDriver;

class Database implements AuditDriver
{
    /**
     * {@inheritdoc}
     */
    public function audit(Auditable $model): ?Audit
    {
        $implementation = Config::get('audit.implementation', \OwenIt\Auditing\Models\Audit::class);

        return call_user_func([$implementation, 'create'], $model->toAudit());
    }

    /**
     * {@inheritdoc}
     */
    public function prune(Auditable $model): bool
    {
        if (($threshold = $model->getAuditThreshold()) > 0) {
            $forRemoval = $model->audits()
                ->latest()
                ->get()
                ->slice($threshold)
                ->pluck(Config::get('audit.db_fields.audit_id'));

            if (!$forRemoval->isEmpty()) {
                return $model->audits()
                    ->whereIn(Config::get('audit.db_fields.audit_id'), $forRemoval)
                    ->delete() > 0;
            }
        }

        return false;
    }
}
