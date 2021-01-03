<?php

namespace Alter\GuestPosting;

use Flarum\Extend;
use Flarum\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher;

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

    (new Extend\Formatter)
        ->configure(ConfigureMentions::class),

    function (Application $app, Dispatcher $dispatcher) {
        $app->register(Providers\DiscussionPostAttribute::class);
        $app->register(Providers\PollAttributes::class);
        $app->register(Providers\RegisterUser::class);
        $app->register(Providers\SaveDiscussionPost::class);
        $app->register(Providers\UserMetadataUpdaterWorkaround::class);

        $dispatcher->subscribe(Access\PostPolicy::class);
    },
];
