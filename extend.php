<?php

namespace Alter\GuestPosting;

use Flarum\Extend;
use Flarum\Foundation\Application;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__ . '/js/dist/admin.js'),

    new Extend\Locales(__DIR__ . '/resources/locale'),

    (new Extend\Middleware('forum'))
        ->add(GuestSessionMiddleware::class),

    (new Extend\Middleware('api'))
        ->add(GuestSessionMiddleware::class),

    function (Application $app) {
        $app->register(Providers\DiscussionPostAttribute::class);
        $app->register(Providers\RegisterUser::class);
        $app->register(Providers\SaveDiscussionPost::class);
    },
];
