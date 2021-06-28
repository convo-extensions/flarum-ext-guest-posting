<?php

namespace Alter\GuestPosting\Commands;

use Alter\GuestPosting\GuestManager;
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

        $actor->assertCan('vote', $poll);

        $previousVoteOptionId = GuestManager::getPollVote($poll->id);

        if ($previousVoteOptionId) {
            $actor->assertCan('changeVote', $poll);
        }

        /**
         * @var VotePollHandler $original
         */
        $original = resolve(VotePollHandler::class);

        if ($optionId !== null) {
            if ($previousVoteOptionId) {
                $oldVote = $this->deletePreviousGuestVote($poll, $previousVoteOptionId);

                $oldVote->option->refreshVoteCount()->save();
                $original->pushUpdatedOption($oldVote->option);
            }

            $vote = $poll->votes()->create([
                'user_id' => null,
                'option_id' => $optionId,
            ]);

            // Forget the relation in case is was loaded for $previousOption
            $vote->unsetRelation('option');

            $vote->option->refreshVoteCount()->save();

            resolve('events')->dispatch(new PollWasVoted($actor, $poll, $vote, $vote !== null));

            $original->pushNewVote($vote);
            $original->pushUpdatedOption($vote->option);

            // We don't actually need a username for this feature, but getting a username is what starts a guest session
            GuestManager::getUsername();
            GuestManager::savePollVote($poll->id, $optionId);
        } else if ($previousVoteOptionId) {
            // We get the model from the method even if it was deleted
            // It's the easiest to retrieve the VoteOption model without making a new query builder
            $oldVote = $this->deletePreviousGuestVote($poll, $previousVoteOptionId);

            $oldVote->option->refreshVoteCount()->save();
            $original->pushUpdatedOption($oldVote->option);

            GuestManager::savePollVote($poll->id, null);
        }

        $poll->refreshVoteCount()->save();

        return $poll;
    }

    protected function deletePreviousGuestVote(Poll $poll, $optionId): ?PollVote
    {
        /**
         * Multiple votes could match "no user" + "that option", but it's not a problem. We'll simply delete one of them
         * @var $vote PollVote|null
         */
        $vote = $poll->votes()->whereNull('user_id')->where('option_id', $optionId)->first();

        if ($vote) {
            $vote->delete();

            return $vote;
        }

        return null;
    }
}
