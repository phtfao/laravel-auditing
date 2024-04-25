<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Config;
use OwenIt\Auditing\Models\Audit;
use OwenIt\Auditing\Tests\Models\Article;
use OwenIt\Auditing\Tests\Models\User;

/*
|--------------------------------------------------------------------------
| Audit Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(Audit::class, function (Faker $faker) {
    
    return [
        Config::get('audit.db_fields.audit_user_id') => function () {
            return factory(User::class)->create()->id;
        },
        Config::get('audit.db_fields.audit_user_type')    => User::class,
        Config::get('audit.db_fields.audit_event')        => 'updated',
        Config::get('audit.db_fields.audit_auditable_id') => function () {
            return factory(Article::class)->create()->id;
        },
        Config::get('audit.db_fields.audit_auditable_type') => Article::class,
        Config::get('audit.db_fields.audit_old_values')     => [],
        Config::get('audit.db_fields.audit_new_values')     => [],
        'url'            => $faker->url,
        'ip_address'     => $faker->ipv4,
        'user_agent'     => $faker->userAgent,
        Config::get('audit.db_fields.audit_tags')           => implode(',', $faker->words(4)),
    ];
});
