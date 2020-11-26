<?php

namespace Alter\GuestPosting\Access;

use Flarum\Post\Post;
use Flarum\User\AbstractPolicy;
use Flarum\User\User;

class PostPolicy extends AbstractPolicy
{
    protected $model = Post::class;

    public function edit(User $actor, Post $post)
    {
        // Flarum's default policy is based on $actor->can('reply') together with user id null == 0 which messes up with the Guest posting
        // We will hard-code that guests can never edit posts
        if ($actor->isGuest()) {
            return false;
        }
    }

    public function hide(User $actor, Post $post)
    {
        return $this->edit($actor, $post);
    }
}
