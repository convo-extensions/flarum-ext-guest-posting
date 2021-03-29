<?php

namespace Alter\GuestPosting;

use Flarum\Api\Serializer\DiscussionSerializer;
use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Api\Serializer\PostSerializer;
use Flarum\Discussion\Discussion;
use Flarum\Extend;
use Flarum\Post\Post;
use Flarum\User\Event\Saving;
use FoF\Polls\Api\Serializers\PollSerializer;
use FoF\Polls\Poll;

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

    (new Extend\ApiSerializer(ForumSerializer::class))
        ->attributes(ForumAttributes::class),

    (new Extend\ApiSerializer(DiscussionSerializer::class))
        ->attribute('guest_username', function (DiscussionSerializer $serializer, Discussion $discussion) {
            return $discussion->guest_username;
        }),

    (new Extend\ApiSerializer(PostSerializer::class))
        ->attribute('guest_username', function (PostSerializer $serializer, Post $post) {
            return $post->guest_username;
        }),

    (new Extend\ApiSerializer(PollSerializer::class))
        ->attributes(function (PollSerializer $serializer, Poll $poll): array {
            return [
                'guestVoteOption' => GuestManager::getPollVote($poll->id),
                'canGuestVote' => $serializer->getActor()->can('votePolls'),
            ];
        }),

    (new Extend\ServiceProvider())
        ->register(Providers\PipeThroughPollVote::class)
        ->register(Providers\SaveDiscussionPost::class),

    (new Extend\Event())
        ->listen(Saving::class, Listeners\SaveUser::class),

    (new Extend\Policy())
        ->modelPolicy(Post::class, Access\PostPolicy::class),
];
