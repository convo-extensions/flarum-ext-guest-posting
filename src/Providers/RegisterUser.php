<?php

namespace Alter\GuestPosting\Providers;

use Alter\GuestPosting\GuestManager;
use Flarum\Api\Event\Serializing;
use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Foundation\AbstractServiceProvider;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\Event\Saving;
use Flarum\User\User;
use Illuminate\Support\Arr;

class RegisterUser extends AbstractServiceProvider
{
    public function register()
    {
        $this->app['events']->listen(Saving::class, [$this, 'saving']);
        $this->app['events']->listen(Serializing::class, [$this, 'serializing']);
    }

    public function saving(Saving $event)
    {
        if (Arr::get($event->data, 'attributes.importGuestContent')) {
            /**
             * @var $settings SettingsRepositoryInterface
             */
            $settings = app(SettingsRepositoryInterface::class);

            if ($settings->get('guest-posting.enableImport')) {
                $event->user->afterSave(function (User $user) {
                    GuestManager::setContentOwner($user);
                });
            }
        }
    }

    public function serializing(Serializing $event)
    {
        if ($event->isSerializer(ForumSerializer::class)) {
            /**
             * @var $settings SettingsRepositoryInterface
             */
            $settings = app(SettingsRepositoryInterface::class);

            if ($event->actor->isGuest() && $settings->get('guest-posting.enableImport')) {
                if ($count = GuestManager::postCount()) {
                    $event->attributes['guestPostCount'] = $count;
                }
                if ($count = GuestManager::voteCount()) {
                    $event->attributes['guestVoteCount'] = $count;
                }
            }
        }
    }
}
