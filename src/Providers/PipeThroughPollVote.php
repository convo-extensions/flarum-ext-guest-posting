<?php

namespace Alter\GuestPosting\Providers;

use Alter\GuestPosting\Commands\GuestVotePollHandler;
use Flarum\Foundation\AbstractServiceProvider;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;

class PipeThroughPollVote extends AbstractServiceProvider
{
    public function register()
    {
        // Workaround for https://github.com/flarum/core/issues/2480
        $this->container->resolving(BusDispatcher::class, function (BusDispatcher $bus) {
            $bus->pipeThrough([
                GuestVotePollHandler::class,
            ]);
        });
    }
}
