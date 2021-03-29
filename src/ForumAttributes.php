<?php

namespace Alter\GuestPosting;

use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Settings\SettingsRepositoryInterface;

class ForumAttributes
{
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function __invoke(ForumSerializer $serializer): array
    {
        $attributes = [];

        if ($serializer->getActor()->isGuest() && $this->settings->get('guest-posting.enableImport')) {
            if ($count = GuestManager::postCount()) {
                $attributes['guestPostCount'] = $count;
            }
            if ($count = GuestManager::voteCount()) {
                $attributes['guestVoteCount'] = $count;
            }
        }

        return $attributes;
    }
}
