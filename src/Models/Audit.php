<?php

namespace OwenIt\Auditing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * @property string $tags
 * @property string $event
 * @property array $new_values
 * @property array $old_values
 * @property mixed $user
 * @property mixed $auditable.
 */
class Audit extends Model implements \OwenIt\Auditing\Contracts\Audit
{
    use \OwenIt\Auditing\Audit;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'old_values'   => 'json',
        'new_values'   => 'json',
        // Note: Please do not add 'auditable_id' in here, as it will break non-integer PK models
    ];

    public function getSerializedDate($date)
    {
        return $this->serializeDate($date);
    }

    public function setPrimaryKey($value)
    {
        $this->primaryKey = $value;
    }

    public function setCasts($casts)
    {
        $this->casts = $casts;
    }

    /**
     * Get the name of the "created at" column.
     *
     * @return string|null
     */
    public function getCreatedAtColumn()
    {
        return Config::get('audit.db_fields.audit_created_at');
    }

    /**
     * Get the name of the "updated at" column.
     *
     * @return string|null
     */
    public function getUpdatedAtColumn()
    {
        return Config::get('audit.db_fields.audit_updated_at');
    }

    public static function boot()
    {
        parent::boot();
        static::saving(function ($audit) {
            $audit->setPrimaryKey(Config::get('audit.db_fields.audit_id'));
            $audit->setCasts([
                Config::get('audit.db_fields.audit_old_values') => 'json',
                Config::get('audit.db_fields.audit_new_values') => 'json'
            ]);
        });
    }
}
