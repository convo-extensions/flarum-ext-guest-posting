<?php

namespace Alter\GuestPosting\Providers;

use Alter\GuestPosting\GuestManager;
use Flarum\Discussion\Event\Saving as SavingDiscussion;
use Flarum\Discussion\Event\Started;
use Flarum\Foundation\AbstractServiceProvider;
use Flarum\Post\Event\Posted;
use Flarum\Post\Event\Saving as SavingPost;

class SaveDiscussionPost extends AbstractServiceProvider
{
    public function register()
    {
        $this->app['events']->listen(SavingDiscussion::class, [$this, 'savingDiscussion']);
        $this->app['events']->listen(Started::class, [$this, 'savedDiscussion']);
        $this->app['events']->listen(SavingPost::class, [$this, 'savingPost']);
        $this->app['events']->listen(Posted::class, [$this, 'savedPost']);
    }

    public function savingDiscussion(SavingDiscussion $event)
    {
        if ($event->actor->isGuest() && !$event->discussion->exists) {
            $event->discussion->guest_username = GuestManager::getUsername();

            // Flarum uses the ID from the Guest model which is 0 and breaks the integrity
            // We need to use dissociate and not just set user_id to null otherwise a serialization of Guest can be returned by the API
            $event->discussion->user()->dissociate();
        }

        GuestManager::saveDiscussionId($event->discussion->id);
    }

    public function savedDiscussion(Started $event)
    {
        if ($event->actor->isGuest()) {
            GuestManager::saveDiscussionId($event->discussion->id);
        }
    }

    public function savingPost(SavingPost $event)
    {
        if ($event->actor->isGuest() && !$event->post->exists) {
            $event->post->guest_username = GuestManager::getUsername();
            $event->post->user()->dissociate();
        }
    }

    public function savedPost(Posted $event)
    {
        if ($event->actor->isGuest()) {
            GuestManager::savePostId($event->post->id);
        }
    }
}
