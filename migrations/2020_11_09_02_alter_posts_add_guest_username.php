<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        if ($schema->hasColumn('posts', 'guest_username')) {
            return;
        }

        $schema->table('posts', function (Blueprint $table) {
            $table->string('guest_username', 150)->nullable();
        });
    },
    'down' => function (Builder $schema) {
        if (!$schema->hasColumn('posts', 'guest_username')) {
            return;
        }

        $schema->table('posts', function (Blueprint $table) {
            $table->dropColumn('guest_username');
        });
    }
];
