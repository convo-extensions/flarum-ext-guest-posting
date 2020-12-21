<?php

namespace Alter\GuestPosting\Commands;

use Alter\GuestPosting\GuestManager;
use Flarum\User\Exception\PermissionDeniedException;
use FoF\Polls\Commands\VotePoll;
use FoF\Polls\Commands\VotePollHandler;
use FoF\Polls\Events\PollWasVoted;
use FoF\Polls\Poll;
use FoF\Polls\PollVote;
use Illuminate\Support\Arr;

class GuestVotePollHandler
{
    public function handle($command, $next)
    {
        // Do not try to override other commands
        if (!($command instanceof VotePoll)) {
            return $next($command);
        }

        $actor = $command->actor;

        // Protects against guest voting if not enabled
        // By placing it before isGuest check, it fixes https://github.com/FriendsOfFlarum/polls/issues/28
        $actor->assertCan('votePolls');

        // If it's not a guest situation, let the original command handle the logic
        if (!$actor->isGuest()) {
            return $next($command);
        }

        /**
         * @var $poll Poll
         */
        $poll = Poll::query()->findOrFail($command->pollId);
        $data = $command->data;

        $optionId = Arr::get($data, 'optionId');

        if ($poll->hasEnded()) {
            throw new PermissionDeniedException();
        }

        $previousVoteOptionId = GuestManager::getPollVote($poll->id);

        if ($optionId !== null) {
            if ($previousVoteOptionId) {
                $this->deletePreviousGuestVote($poll, $previousVoteOptionId);
            }

            $vote = $poll->votes()->create([
                'user_id' => null,
                'option_id' => $optionId,
            ]);

            app('events')->dispatch(new PollWasVoted($actor, $poll, $vote, $vote !== null));

            $original = app(VotePollHandler::class);
            $original->pushNewVote($vote);

            // We don't actually need a username for this feature, but getting a username is what starts a guest session
            GuestManager::getUsername();
            GuestManager::savePollVote($poll->id, $optionId);
        } else if ($previousVoteOptionId) {
            $this->deletePreviousGuestVote($poll, $previousVoteOptionId);

            GuestManager::savePollVote($poll->id, null);
        }

        return $poll;
    }

    protected function deletePreviousGuestVote(Poll $poll, $optionId)
    {
        /**
         * Multiple votes could match "no user" + "that option", but it's not a problem. We'll simply delete one of them
         * @var $vote PollVote|null
         */
        $vote = $poll->votes()->whereNull('user_id')->where('option_id', $optionId)->first();

        if ($vote) {
            $vote->delete();
        }
    }
}
