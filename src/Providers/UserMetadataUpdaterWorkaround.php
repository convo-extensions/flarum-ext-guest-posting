<?php

namespace Alter\GuestPosting\Providers;

use Alter\GuestPosting\Listeners\FixedUserMetadataUpdater;
use Flarum\Foundation\AbstractServiceProvider;
use Flarum\User\UserMetadataUpdater;

/**
 * UserMetadataUpdater is registered in UserServiceProvider::boot
 * and is resolved through the container by the dispatcher
 * so this gives us a chance to replace it with our patch
 */
class UserMetadataUpdaterWorkaround extends AbstractServiceProvider
{
    public function register()
    {
        $this->app->bind(UserMetadataUpdater::class, FixedUserMetadataUpdater::class);
    }
}
