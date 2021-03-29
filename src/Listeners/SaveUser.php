<?php

namespace Alter\GuestPosting\Listeners;

use Alter\GuestPosting\GuestManager;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\Event\Saving;
use Flarum\User\User;
use Illuminate\Support\Arr;

class SaveUser
{
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function handle(Saving $event)
    {
        if (Arr::get($event->data, 'attributes.importGuestContent')) {
            if ($this->settings->get('guest-posting.enableImport')) {
                $event->user->afterSave(function (User $user) {
                    GuestManager::setContentOwner($user);
                });
            }
        }
    }
}
