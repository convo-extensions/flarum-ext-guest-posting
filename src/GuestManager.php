<?php

namespace Alter\GuestPosting;

use Flarum\Discussion\Discussion;
use Flarum\Post\Post;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Arr;

class GuestManager
{
    protected static $username = null;
    protected static $discussionIds = [];
    protected static $postIds = [];
    protected static $dirty = false;

    public static function loadSession(Session $session)
    {
        self::$username = $session->get('username');
        self::$discussionIds = $session->get('discussionIds', []);
        self::$postIds = $session->get('postIds', []);
    }

    public static function saveSession(Session $session)
    {
        if (!self::$dirty) {
            return;
        }

        $session->put('username', self::$username);
        $session->put('discussionIds', self::$discussionIds);
        $session->put('postIds', self::$postIds);
        $session->save();
    }

    /**
     * Whether a guest session (with a temporary username and post) is active
     * @return bool
     */
    public static function isActive(): bool
    {
        return !!self::$username;
    }

    public static function getUsername(): string
    {
        if (!self::$username) {
            /**
             * @var $settings SettingsRepositoryInterface
             */
            $settings = app(SettingsRepositoryInterface::class);

            self::$username = Arr::random(explode("\n", $settings->get('guest-posting.usernames', '')));
            self::$dirty = true;
        }

        return self::$username;
    }

    public static function saveDiscussionId($id)
    {
        self::$discussionIds[] = $id;
        self::$dirty = true;
    }

    public static function savePostId($id)
    {
        self::$postIds[] = $id;
        self::$dirty = true;
    }

    /**
     * Post count for the registration prompt
     * @return int
     */
    public static function postCount(): int
    {
        return max(count(self::$discussionIds), count(self::$postIds));
    }

    /**
     * Re-assign guest posts to the newly registered user
     * @param User $user
     */
    public static function setContentOwner(User $user)
    {
        if (count(self::$discussionIds)) {
            Discussion::query()->whereIn('id', self::$discussionIds)->update([
                'guest_username' => null,
                'user_id' => $user->id,
            ]);
        }

        if (count(self::$postIds)) {
            Post::query()->whereIn('id', self::$postIds)->update([
                'guest_username' => null,
                'user_id' => $user->id,
            ]);
        }

        // We clear the data so the posts can't be moved to another account
        // We don't clear the username, it's not worth the complexity
        // We can leave the guest session expire together with the remembered guest username
        self::$discussionIds = [];
        self::$postIds = [];
        self::$dirty = true;
    }
}
