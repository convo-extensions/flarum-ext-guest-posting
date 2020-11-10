<?php

namespace Alter\GuestPosting\Providers;

use Flarum\Api\Event\Serializing;
use Flarum\Api\Serializer\DiscussionSerializer;
use Flarum\Api\Serializer\PostSerializer;
use Flarum\Foundation\AbstractServiceProvider;

class DiscussionPostAttribute extends AbstractServiceProvider
{
    public function register()
    {
        $this->app['events']->listen(Serializing::class, [$this, 'serializing']);
    }

    public function serializing(Serializing $event)
    {
        if ($event->isSerializer(DiscussionSerializer::class) || $event->isSerializer(PostSerializer::class)) {
            $event->attributes['guest_username'] = $event->model->guest_username;
        }
    }
}
