<?php

namespace Alter\GuestPosting;

use Flarum\Post\CommentPost;
use s9e\TextFormatter\Configurator;

class ConfigureMentions
{
    public function __invoke(Configurator $config)
    {
        if (!$config->tags->exists('POSTMENTION')) {
            return;
        }

        $tag = $config->tags->get('POSTMENTION');

        $tag->filterChain
            // Orignal addPostId filter is first, and we want to insert after it
            // It's important we run AFTER the original, because the javascript filter needs to override the displayname from the original
            // We can't insert at the end of the filterChain because TextFormatter never runs it on PHP side
            ->insert(1, [static::class, 'addPostId'])
            ->setJS('function(tag) { return flarum.extensions["alter-guest-posting"].filterPostMentions(tag); }');
    }

    public static function addPostId($tag)
    {
        $post = CommentPost::query()->find($tag->getAttribute('id'));

        if ($post && $post->guest_username) {
            $tag->setAttribute('discussionid', (int)$post->discussion_id);
            $tag->setAttribute('number', (int)$post->number);
            $tag->setAttribute('displayname', $post->guest_username);

            return true;
        }
    }
}
