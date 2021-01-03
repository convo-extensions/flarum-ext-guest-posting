<?php

namespace Alter\GuestPosting\Listeners;

use Flarum\Discussion\Discussion;
use Flarum\Discussion\Event\Deleted as DiscussionDeleted;
use Flarum\Discussion\Event\Started;
use Flarum\Post\Event\Deleted as PostDeleted;
use Flarum\Post\Event\Posted;
use Flarum\User\User;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * Copy of core UserMetadataUpdater with a patch
 * Workaround for https://github.com/flarum/core/issues/2506
 */
class FixedUserMetadataUpdater
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Posted::class, [$this, 'whenPostWasPosted']);
        $events->listen(PostDeleted::class, [$this, 'whenPostWasDeleted']);
        $events->listen(Started::class, [$this, 'whenDiscussionWasStarted']);
        $events->listen(DiscussionDeleted::class, [$this, 'whenDiscussionWasDeleted']);
    }

    public function whenPostWasPosted(Posted $event)
    {
        $this->updateCommentsCount($event->post->user);
    }

    public function whenPostWasDeleted(PostDeleted $event)
    {
        $this->updateCommentsCount($event->post->user);
    }

    public function whenDiscussionWasStarted(Started $event)
    {
        $this->updateDiscussionsCount($event->discussion);
    }

    public function whenDiscussionWasDeleted(DiscussionDeleted $event)
    {
        $this->updateDiscussionsCount($event->discussion);
        $this->updateCommentsCount($event->discussion->user);
    }

    private function updateCommentsCount(User $user = null)
    {
        if ($user && $user->exists) {
            $user->refreshCommentCount()->save();
        }
    }

    private function updateDiscussionsCount(Discussion $discussion)
    {
        $user = $discussion->user;

        if ($user && $user->exists) {
            $user->refreshDiscussionCount()->save();
        }
    }
}
