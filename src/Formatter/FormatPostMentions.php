<?php

namespace Alter\GuestPosting\Formatter;

use Psr\Http\Message\ServerRequestInterface;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Utils;

class FormatPostMentions
{
    public function __invoke(Renderer $renderer, $context, $xml, ServerRequestInterface $request = null): string
    {
        $post = $context;

        return Utils::replaceAttributes($xml, 'POSTMENTION', function ($attributes) use ($post) {
            $post = $post->mentionsPosts->find($attributes['id']);

            // Flarum replaces the display name with core.lib.username.deleted_text if there's a post but no user
            // We will revert it back to what we stored in the displayname attribute
            // Since we can't access the original XML anymore because we run after Mentions, we just pull it back from the post
            if ($post && $post->guest_username) {
                $attributes['displayname'] = $post->guest_username;
            }

            return $attributes;
        });
    }
}
